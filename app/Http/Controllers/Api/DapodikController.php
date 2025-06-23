<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DapodikDataPokok;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Dapodik",
 *     description="API endpoints for Dapodik (Data Pokok Pendidikan) data"
 * )
 */
class DapodikController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dapodik/dashboard/summary",
     *     summary="Get Dapodik dashboard summary",
     *     tags={"Dapodik"},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard summary data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total_schools", type="integer", example=1250),
     *                 @OA\Property(property="total_teachers", type="integer", example=8500),
     *                 @OA\Property(property="total_students", type="integer", example=125000),
     *                 @OA\Property(property="total_classrooms", type="integer", example=4500),
     *                 @OA\Property(property="schools_by_status", type="object"),
     *                 @OA\Property(property="schools_by_type", type="object"),
     *                 @OA\Property(property="top_kabupaten", type="array")
     *             )
     *         )
     *     )
     * )
     */
    public function dashboardSummary(): JsonResponse
    {
        $cacheKey = 'dapodik_dashboard_summary';
        
        return Cache::remember($cacheKey, 3600, function () {
            $data = DapodikDataPokok::selectRaw('
                COUNT(*) as total_schools,
                SUM(guru) as total_teachers,
                SUM(tka_l + tka_p + tkb_l + tkb_p + t1_l + t1_p + t2_l + t2_p + t3_l + t3_p + t4_l + t4_p + t5_l + t5_p + t6_l + t6_p + t7_l + t7_p + t8_l + t8_p + t9_l + t9_p + t10_l + t10_p + t11_l + t11_p + t12_l + t12_p + t13_l + t13_p) as total_students,
                SUM(jumlah_ruang_kelas) as total_classrooms
            ')->first();

            $schoolsByStatus = DapodikDataPokok::selectRaw('status_sekolah, COUNT(*) as count')
                ->groupBy('status_sekolah')
                ->get()
                ->pluck('count', 'status_sekolah');

            $schoolsByType = DapodikDataPokok::selectRaw('bentuk_pendidikan, COUNT(*) as count')
                ->groupBy('bentuk_pendidikan')
                ->get()
                ->pluck('count', 'bentuk_pendidikan');

            $topKabupaten = DapodikDataPokok::selectRaw('kabupaten_kota, COUNT(*) as school_count')
                ->groupBy('kabupaten_kota')
                ->orderByDesc('school_count')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_schools' => $data->total_schools ?? 0,
                    'total_teachers' => $data->total_teachers ?? 0,
                    'total_students' => $data->total_students ?? 0,
                    'total_classrooms' => $data->total_classrooms ?? 0,
                    'schools_by_status' => $schoolsByStatus,
                    'schools_by_type' => $schoolsByType,
                    'top_kabupaten' => $topKabupaten
                ]
            ]);
        });
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/dashboard/statistics",
     *     summary="Get detailed statistics",
     *     tags={"Dapodik"},
     *     @OA\Response(
     *         response=200,
     *         description="Detailed statistics data"
     *     )
     * )
     */
    public function dashboardStatistics(): JsonResponse
    {
        $cacheKey = 'dapodik_dashboard_statistics';
        
        return Cache::remember($cacheKey, 3600, function () {
            $statistics = [
                'education_types' => DapodikDataPokok::selectRaw('bentuk_pendidikan, COUNT(*) as count')
                    ->groupBy('bentuk_pendidikan')
                    ->get(),
                'school_status' => DapodikDataPokok::selectRaw('status_sekolah, COUNT(*) as count')
                    ->groupBy('status_sekolah')
                    ->get(),
                'accreditation' => DapodikDataPokok::selectRaw('akreditasi, COUNT(*) as count')
                    ->whereNotNull('akreditasi')
                    ->groupBy('akreditasi')
                    ->get(),
                'students_by_grade' => DapodikDataPokok::selectRaw('
                    SUM(tka_l + tka_p) as tka,
                    SUM(tkb_l + tkb_p) as tkb,
                    SUM(t1_l + t1_p) as t1,
                    SUM(t2_l + t2_p) as t2,
                    SUM(t3_l + t3_p) as t3,
                    SUM(t4_l + t4_p) as t4,
                    SUM(t5_l + t5_p) as t5,
                    SUM(t6_l + t6_p) as t6,
                    SUM(t7_l + t7_p) as t7,
                    SUM(t8_l + t8_p) as t8,
                    SUM(t9_l + t9_p) as t9,
                    SUM(t10_l + t10_p) as t10,
                    SUM(t11_l + t11_p) as t11,
                    SUM(t12_l + t12_p) as t12,
                    SUM(t13_l + t13_p) as t13
                ')->first()
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        });
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/schools",
     *     summary="Get list of schools",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="page", in="query", description="Page number", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", description="Items per page", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", in="query", description="Search by school name or NPSN", @OA\Schema(type="string")),
     *     @OA\Parameter(name="kabupaten", in="query", description="Filter by kabupaten", @OA\Schema(type="string")),
     *     @OA\Parameter(name="kecamatan", in="query", description="Filter by kecamatan", @OA\Schema(type="string")),
     *     @OA\Parameter(name="bentuk_pendidikan", in="query", description="Filter by education type", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status_sekolah", in="query", description="Filter by school status", @OA\Schema(type="string")),
     *     @OA\Parameter(name="sort_by", in="query", description="Sort field", @OA\Schema(type="string")),
     *     @OA\Parameter(name="sort_order", in="query", description="Sort order (asc/desc)", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="List of schools")
     * )
     */
    public function schools(Request $request): JsonResponse
    {
        $query = DapodikDataPokok::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_satuan_pendidikan', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->filled('bentuk_pendidikan')) {
            $query->where('bentuk_pendidikan', $request->bentuk_pendidikan);
        }

        if ($request->filled('status_sekolah')) {
            $query->where('status_sekolah', $request->status_sekolah);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'nama_satuan_pendidikan');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $schools = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $schools->items(),
            'pagination' => [
                'current_page' => $schools->currentPage(),
                'last_page' => $schools->lastPage(),
                'per_page' => $schools->perPage(),
                'total' => $schools->total(),
                'from' => $schools->firstItem(),
                'to' => $schools->lastItem()
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/schools/{npsn}",
     *     summary="Get school detail by NPSN",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="npsn", in="path", required=true, description="School NPSN", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="School detail"),
     *     @OA\Response(response=404, description="School not found")
     * )
     */
    public function schoolDetail(string $npsn): JsonResponse
    {
        $school = DapodikDataPokok::where('npsn', $npsn)->first();

        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'Sekolah tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $school
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/schools/nearby",
     *     summary="Get nearby schools",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="lat", in="query", required=true, description="Latitude", @OA\Schema(type="number")),
     *     @OA\Parameter(name="lng", in="query", required=true, description="Longitude", @OA\Schema(type="number")),
     *     @OA\Parameter(name="radius", in="query", description="Radius in km", @OA\Schema(type="number")),
     *     @OA\Response(response=200, description="Nearby schools")
     * )
     */
    public function nearbySchools(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'nullable|numeric|min:0.1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->get('radius', 10); // Default 10km

        $schools = DapodikDataPokok::selectRaw('
            *,
            (6371 * acos(cos(radians(?)) * cos(radians(lintang)) * cos(radians(bujur) - radians(?)) + sin(radians(?)) * sin(radians(lintang)))) AS distance
        ', [$lat, $lng, $lat])
        ->whereNotNull('lintang')
        ->whereNotNull('bujur')
        ->having('distance', '<=', $radius)
        ->orderBy('distance')
        ->limit(20)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $schools
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/locations/kabupaten",
     *     summary="Get kabupaten list with school counts",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Kabupaten list")
     * )
     */
    public function kabupatenList(): JsonResponse
    {
        $kabupaten = DapodikDataPokok::selectRaw('kabupaten_kota, COUNT(*) as school_count')
            ->groupBy('kabupaten_kota')
            ->orderBy('school_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kabupaten
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/locations/kecamatan",
     *     summary="Get kecamatan list with school counts",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="kabupaten", in="query", description="Filter by kabupaten", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Kecamatan list")
     * )
     */
    public function kecamatanList(Request $request): JsonResponse
    {
        $query = DapodikDataPokok::selectRaw('kecamatan, COUNT(*) as school_count')
            ->groupBy('kecamatan');

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        $kecamatan = $query->orderBy('school_count', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $kecamatan
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/locations/desa",
     *     summary="Get desa list with school counts",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="kecamatan", in="query", description="Filter by kecamatan", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Desa list")
     * )
     */
    public function desaList(Request $request): JsonResponse
    {
        $query = DapodikDataPokok::selectRaw('desa, COUNT(*) as school_count')
            ->groupBy('desa');

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        $desa = $query->orderBy('school_count', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $desa
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/analytics/students",
     *     summary="Get student analytics",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="kabupaten", in="query", description="Filter by kabupaten", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Student analytics")
     * )
     */
    public function studentAnalytics(Request $request): JsonResponse
    {
        $query = DapodikDataPokok::selectRaw('
            SUM(tka_l + tka_p) as tka_total,
            SUM(tka_l) as tka_l,
            SUM(tka_p) as tka_p,
            SUM(tkb_l + tkb_p) as tkb_total,
            SUM(tkb_l) as tkb_l,
            SUM(tkb_p) as tkb_p,
            SUM(t1_l + t1_p) as t1_total,
            SUM(t1_l) as t1_l,
            SUM(t1_p) as t1_p,
            SUM(t2_l + t2_p) as t2_total,
            SUM(t2_l) as t2_l,
            SUM(t2_p) as t2_p,
            SUM(t3_l + t3_p) as t3_total,
            SUM(t3_l) as t3_l,
            SUM(t3_p) as t3_p,
            SUM(t4_l + t4_p) as t4_total,
            SUM(t4_l) as t4_l,
            SUM(t4_p) as t4_p,
            SUM(t5_l + t5_p) as t5_total,
            SUM(t5_l) as t5_l,
            SUM(t5_p) as t5_p,
            SUM(t6_l + t6_p) as t6_total,
            SUM(t6_l) as t6_l,
            SUM(t6_p) as t6_p,
            SUM(t7_l + t7_p) as t7_total,
            SUM(t7_l) as t7_l,
            SUM(t7_p) as t7_p,
            SUM(t8_l + t8_p) as t8_total,
            SUM(t8_l) as t8_l,
            SUM(t8_p) as t8_p,
            SUM(t9_l + t9_p) as t9_total,
            SUM(t9_l) as t9_l,
            SUM(t9_p) as t9_p,
            SUM(t10_l + t10_p) as t10_total,
            SUM(t10_l) as t10_l,
            SUM(t10_p) as t10_p,
            SUM(t11_l + t11_p) as t11_total,
            SUM(t11_l) as t11_l,
            SUM(t11_p) as t11_p,
            SUM(t12_l + t12_p) as t12_total,
            SUM(t12_l) as t12_l,
            SUM(t12_p) as t12_p,
            SUM(t13_l + t13_p) as t13_total,
            SUM(t13_l) as t13_l,
            SUM(t13_p) as t13_p
        ');

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        $analytics = $query->first();

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/analytics/teachers",
     *     summary="Get teacher analytics",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="kabupaten", in="query", description="Filter by kabupaten", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Teacher analytics")
     * )
     */
    public function teacherAnalytics(Request $request): JsonResponse
    {
        $query = DapodikDataPokok::selectRaw('
            SUM(guru) as total_teachers,
            SUM(tendik) as total_tendik,
            COUNT(*) as total_schools,
            AVG(guru) as avg_teachers_per_school,
            AVG(tendik) as avg_tendik_per_school
        ');

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        $analytics = $query->first();

        // Teacher distribution by education type
        $teacherByType = DapodikDataPokok::selectRaw('bentuk_pendidikan, SUM(guru) as total_teachers, COUNT(*) as school_count')
            ->groupBy('bentuk_pendidikan')
            ->orderByDesc('total_teachers')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $analytics,
                'by_education_type' => $teacherByType
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/analytics/classrooms",
     *     summary="Get classroom analytics",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Classroom analytics")
     * )
     */
    public function classroomAnalytics(): JsonResponse
    {
        $analytics = DapodikDataPokok::selectRaw('
            SUM(jumlah_ruang_kelas) as total_classrooms,
            SUM(rombel_t1 + rombel_t2 + rombel_t3 + rombel_t4 + rombel_t5 + rombel_t6 + rombel_t7 + rombel_t8 + rombel_t9 + rombel_t10 + rombel_t11 + rombel_t12 + rombel_t13 + rombel_tka + rombel_tkb + rombel_pkta + rombel_pktb + rombel_pktc) as total_rombel,
            AVG(jumlah_ruang_kelas) as avg_classrooms_per_school,
            AVG(rombel_t1 + rombel_t2 + rombel_t3 + rombel_t4 + rombel_t5 + rombel_t6 + rombel_t7 + rombel_t8 + rombel_t9 + rombel_t10 + rombel_t11 + rombel_t12 + rombel_t13 + rombel_tka + rombel_tkb + rombel_pkta + rombel_pktb + rombel_pktc) as avg_rombel_per_school
        ')->first();

        $rombelByGrade = DapodikDataPokok::selectRaw('
            SUM(rombel_tka) as tka,
            SUM(rombel_tkb) as tkb,
            SUM(rombel_t1) as t1,
            SUM(rombel_t2) as t2,
            SUM(rombel_t3) as t3,
            SUM(rombel_t4) as t4,
            SUM(rombel_t5) as t5,
            SUM(rombel_t6) as t6,
            SUM(rombel_t7) as t7,
            SUM(rombel_t8) as t8,
            SUM(rombel_t9) as t9,
            SUM(rombel_t10) as t10,
            SUM(rombel_t11) as t11,
            SUM(rombel_t12) as t12,
            SUM(rombel_t13) as t13,
            SUM(rombel_pkta) as pkta,
            SUM(rombel_pktb) as pktb,
            SUM(rombel_pktc) as pktc
        ')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $analytics,
                'rombel_by_grade' => $rombelByGrade
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/accreditation/summary",
     *     summary="Get accreditation summary",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Accreditation summary")
     * )
     */
    public function accreditationSummary(): JsonResponse
    {
        $summary = DapodikDataPokok::selectRaw('
            akreditasi,
            COUNT(*) as school_count,
            AVG(guru) as avg_teachers,
            AVG(jumlah_ruang_kelas) as avg_classrooms
        ')
        ->whereNotNull('akreditasi')
        ->groupBy('akreditasi')
        ->orderBy('akreditasi', 'desc')
        ->get();

        $bestAccredited = DapodikDataPokok::where('akreditasi', 'A')
            ->orderByDesc('guru')
            ->limit(10)
            ->get(['nama_satuan_pendidikan', 'npsn', 'akreditasi', 'guru', 'kabupaten_kota']);

        return response()->json([
            'success' => true,
            'data' => [
                'accreditation_distribution' => $summary,
                'best_accredited_schools' => $bestAccredited
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/accreditation/expiring",
     *     summary="Get schools with expiring accreditation",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Schools with expiring accreditation")
     * )
     */
    public function expiringAccreditation(): JsonResponse
    {
        $expiringSchools = DapodikDataPokok::whereNotNull('tmt_akreditasi')
            ->where('tmt_akreditasi', '<=', Carbon::now()->addYears(2))
            ->orderBy('tmt_akreditasi')
            ->get(['nama_satuan_pendidikan', 'npsn', 'akreditasi', 'tmt_akreditasi', 'kabupaten_kota']);

        return response()->json([
            'success' => true,
            'data' => $expiringSchools
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/compare/kabupaten",
     *     summary="Compare kabupaten data",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Kabupaten comparison data")
     * )
     */
    public function compareKabupaten(): JsonResponse
    {
        $comparison = DapodikDataPokok::selectRaw('
            kabupaten_kota,
            COUNT(*) as school_count,
            SUM(guru) as total_teachers,
            SUM(tendik) as total_tendik,
            SUM(tka_l + tka_p + tkb_l + tkb_p + t1_l + t1_p + t2_l + t2_p + t3_l + t3_p + t4_l + t4_p + t5_l + t5_p + t6_l + t6_p + t7_l + t7_p + t8_l + t8_p + t9_l + t9_p + t10_l + t10_p + t11_l + t11_p + t12_l + t12_p + t13_l + t13_p) as total_students,
            AVG(guru) as avg_teachers_per_school,
            AVG(tka_l + tka_p + tkb_l + tkb_p + t1_l + t1_p + t2_l + t2_p + t3_l + t3_p + t4_l + t4_p + t5_l + t5_p + t6_l + t6_p + t7_l + t7_p + t8_l + t8_p + t9_l + t9_p + t10_l + t10_p + t11_l + t11_p + t12_l + t12_p + t13_l + t13_p) as avg_students_per_school
        ')
        ->groupBy('kabupaten_kota')
        ->orderByDesc('school_count')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $comparison
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/compare/bentuk-pendidikan",
     *     summary="Compare education types",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Education type comparison data")
     * )
     */
    public function compareEducationTypes(): JsonResponse
    {
        $comparison = DapodikDataPokok::selectRaw('
            bentuk_pendidikan,
            COUNT(*) as school_count,
            SUM(guru) as total_teachers,
            SUM(tendik) as total_tendik,
            SUM(tka_l + tka_p + tkb_l + tkb_p + t1_l + t1_p + t2_l + t2_p + t3_l + t3_p + t4_l + t4_p + t5_l + t5_p + t6_l + t6_p + t7_l + t7_p + t8_l + t8_p + t9_l + t9_p + t10_l + t10_p + t11_l + t11_p + t12_l + t12_p + t13_l + t13_p) as total_students,
            AVG(guru) as avg_teachers_per_school,
            AVG(tka_l + tka_p + tkb_l + tkb_p + t1_l + t1_p + t2_l + t2_p + t3_l + t3_p + t4_l + t4_p + t5_l + t5_p + t6_l + t6_p + t7_l + t7_p + t8_l + t8_p + t9_l + t9_p + t10_l + t10_p + t11_l + t11_p + t12_l + t12_p + t13_l + t13_p) as avg_students_per_school
        ')
        ->groupBy('bentuk_pendidikan')
        ->orderByDesc('school_count')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $comparison
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/search",
     *     summary="Search schools",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="q", in="query", required=true, description="Search query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="limit", in="query", description="Result limit", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Search results")
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = $request->q;
        $limit = $request->get('limit', 10);

        $results = DapodikDataPokok::where('nama_satuan_pendidikan', 'like', "%{$query}%")
            ->orWhere('npsn', 'like', "%{$query}%")
            ->limit($limit)
            ->get(['nama_satuan_pendidikan', 'npsn', 'bentuk_pendidikan', 'kabupaten_kota', 'kecamatan']);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/map-data",
     *     summary="Get map data for schools",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="kabupaten", in="query", description="Filter by kabupaten", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Map data")
     * )
     */
    public function mapData(Request $request): JsonResponse
    {
        $query = DapodikDataPokok::select([
            'nama_satuan_pendidikan',
            'npsn',
            'bentuk_pendidikan',
            'status_sekolah',
            'alamat',
            'kabupaten_kota',
            'kecamatan',
            'lintang',
            'bujur',
            'akreditasi',
            'guru',
            'tendik'
        ])
        ->whereNotNull('lintang')
        ->whereNotNull('bujur');

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        $mapData = $query->get();

        return response()->json([
            'success' => true,
            'data' => $mapData
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/trends/enrollment",
     *     summary="Get enrollment trends",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Enrollment trends")
     * )
     */
    public function enrollmentTrends(): JsonResponse
    {
        // This would typically use historical data, but for now we'll use current data
        $trends = DapodikDataPokok::selectRaw('
            bentuk_pendidikan,
            SUM(peserta_didik_baru) as total_new_students,
            AVG(peserta_didik_baru) as avg_new_students_per_school
        ')
        ->groupBy('bentuk_pendidikan')
        ->orderByDesc('total_new_students')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $trends
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/trends/growth",
     *     summary="Get growth trends",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="Growth trends")
     * )
     */
    public function growthTrends(): JsonResponse
    {
        $growth = DapodikDataPokok::selectRaw('
            kabupaten_kota,
            COUNT(*) as school_count,
            SUM(guru) as total_teachers,
            SUM(tka_l + tka_p + tkb_l + tkb_p + t1_l + t1_p + t2_l + t2_p + t3_l + t3_p + t4_l + t4_p + t5_l + t5_p + t6_l + t6_p + t7_l + t7_p + t8_l + t8_p + t9_l + t9_p + t10_l + t10_p + t11_l + t11_p + t12_l + t12_p + t13_l + t13_p) as total_students
        ')
        ->groupBy('kabupaten_kota')
        ->orderByDesc('school_count')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $growth
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/export/excel",
     *     summary="Export data to Excel",
     *     tags={"Dapodik"},
     *     @OA\Parameter(name="kabupaten", in="query", description="Filter by kabupaten", @OA\Schema(type="string")),
     *     @OA\Parameter(name="bentuk_pendidikan", in="query", description="Filter by education type", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Excel file download")
     * )
     */
    public function exportExcel(Request $request): JsonResponse
    {
        $query = DapodikDataPokok::query();

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten_kota', $request->kabupaten);
        }

        if ($request->filled('bentuk_pendidikan')) {
            $query->where('bentuk_pendidikan', $request->bentuk_pendidikan);
        }

        $data = $query->get();

        // For now, return the data count. In a real implementation, you'd generate and return an Excel file
        return response()->json([
            'success' => true,
            'message' => 'Export functionality would generate Excel file',
            'data' => [
                'total_records' => $data->count(),
                'filters_applied' => $request->only(['kabupaten', 'bentuk_pendidikan'])
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dapodik/health",
     *     summary="Check API health",
     *     tags={"Dapodik"},
     *     @OA\Response(response=200, description="API health status")
     * )
     */
    public function health(): JsonResponse
    {
        try {
            $totalSchools = DapodikDataPokok::count();
            $lastUpdate = DapodikDataPokok::latest()->first()?->updated_at;

            return response()->json([
                'success' => true,
                'data' => [
                    'status' => 'healthy',
                    'total_schools' => $totalSchools,
                    'last_update' => $lastUpdate,
                    'database_connection' => 'connected'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [
                    'status' => 'unhealthy',
                    'error' => $e->getMessage(),
                    'database_connection' => 'disconnected'
                ]
            ], 500);
        }
    }
} 