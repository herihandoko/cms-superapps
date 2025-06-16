<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiskominfoPengaduanEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @OA\Tag(
 *     name="Dashboard",
 *     description="API Endpoints for Dashboard Statistics"
 * )
 */
class DashboardController extends Controller
{
    /**
     * @OA\Info(
     *     title="Dashboard API",
     *     version="1.0.0",
     *     description="API for dashboard statistics and data"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/dashboard/summary",
     *     summary="Get overall summary statistics",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Time period (today, week, month, year)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function summary(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'period' => 'nullable|in:today,week,month,year'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $period = $request->get('period', 'month');
            $cacheKey = "dashboard_summary_{$period}";

            return response()->json([
                'status' => 'success',
                'data' => Cache::remember($cacheKey, 3600, function () use ($period) {
                    $query = DiskominfoPengaduanEntry::query();
                    
                    // Apply period filter
                    if ($period === 'today') {
                        $query->whereDate('created_at', Carbon::today());
                    } elseif ($period === 'week') {
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    } elseif ($period === 'month') {
                        $query->whereMonth('created_at', Carbon::now()->month)
                              ->whereYear('created_at', Carbon::now()->year);
                    } elseif ($period === 'year') {
                        $query->whereYear('created_at', Carbon::now()->year);
                    }

                    $summary = $query->select([
                        DB::raw('SUM(belum_terverifikasi) as total_belum_terverifikasi'),
                        DB::raw('SUM(belum_ditindaklanjuti) as total_belum_ditindaklanjuti'),
                        DB::raw('SUM(proses) as total_proses'),
                        DB::raw('SUM(selesai) as total_selesai'),
                        DB::raw('SUM(total) as total_keseluruhan'),
                        DB::raw('AVG(persentase_tl) as rata_rata_persentase_tl'),
                        DB::raw('SUM(rtl) as total_rtl'),
                        DB::raw('SUM(rhp) as total_rhp')
                    ])->first();

                    // Calculate additional statistics
                    $totalCases = $summary->total_keseluruhan;
                    $completedCases = $summary->total_selesai;
                    $inProgressCases = $summary->total_proses;
                    $pendingCases = $summary->total_belum_terverifikasi + $summary->total_belum_ditindaklanjuti;

                    // Calculate performance metrics
                    $completionRate = $totalCases > 0 ? ($completedCases / $totalCases) * 100 : 0;
                    $efficiencyRate = $totalCases > 0 ? ($completedCases / ($completedCases + $inProgressCases)) * 100 : 0;
                    $responseRate = $totalCases > 0 ? (($completedCases + $inProgressCases) / $totalCases) * 100 : 0;

                    return [
                        'summary' => $summary,
                        'performance_metrics' => [
                            'completion_rate' => round($completionRate, 2),
                            'efficiency_rate' => round($efficiencyRate, 2),
                            'response_rate' => round($responseRate, 2)
                        ],
                        'status_breakdown' => [
                            'completed' => $completedCases,
                            'in_progress' => $inProgressCases,
                            'pending' => $pendingCases
                        ],
                        'meta' => [
                            'period' => $period,
                            'generated_at' => now()->toIso8601String(),
                            'cache_status' => 'cached'
                        ]
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch summary data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/unit-kerja",
     *     summary="Get data grouped by unit kerja",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search unit kerja",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Sort direction (asc/desc)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Time period",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="export",
     *         in="query",
     *         description="Export to CSV",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function unitKerja(Request $request): JsonResponse|StreamedResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable|string',
                'sort_by' => 'nullable|string|in:unit_kerja,belum_terverifikasi,belum_ditindaklanjuti,proses,selesai,total,persentase_tl,rtl,rhp',
                'sort_direction' => 'nullable|in:asc,desc',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
                'period' => 'nullable|in:today,week,month,year',
                'export' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = DiskominfoPengaduanEntry::query();

            // Apply search filter
            if ($search = $request->get('search')) {
                $query->where('unit_kerja', 'like', "%{$search}%");
            }

            // Apply period filter
            if ($period = $request->get('period')) {
                if ($period === 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($period === 'week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($period === 'month') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                } elseif ($period === 'year') {
                    $query->whereYear('created_at', Carbon::now()->year);
                }
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'unit_kerja');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortBy, $sortDirection);

            // Handle export
            if ($request->boolean('export')) {
                return $this->exportData($query->get(), 'unit_kerja');
            }

            // Apply pagination
            $perPage = $request->get('per_page', 15);
            $data = $query->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $data->items(),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'period' => $period,
                    'generated_at' => now()->toIso8601String()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch unit kerja data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/unit-kerja/top",
     *     summary="Get top performing unit kerja",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of top units to return",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Time period",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function topUnitKerja(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'nullable|integer|min:1|max:100',
                'period' => 'nullable|in:today,week,month,year'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = DiskominfoPengaduanEntry::query();

            // Apply period filter
            if ($period = $request->get('period')) {
                if ($period === 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($period === 'week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($period === 'month') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                } elseif ($period === 'year') {
                    $query->whereYear('created_at', Carbon::now()->year);
                }
            }

            $limit = $request->get('limit', 10);
            $data = $query->select('unit_kerja', DB::raw('AVG(persentase_tl) as avg_persentase_tl'))
                         ->groupBy('unit_kerja')
                         ->orderBy('avg_persentase_tl', 'desc')
                         ->limit($limit)
                         ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'meta' => [
                    'limit' => $limit,
                    'period' => $period,
                    'generated_at' => now()->toIso8601String()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch top unit kerja data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/activity/last-seen",
     *     summary="Get last seen activity",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of activities to return",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function lastSeen(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $limit = $request->get('limit', 10);
            $data = DiskominfoPengaduanEntry::select('unit_kerja', 'updated_at')
                                           ->orderBy('updated_at', 'desc')
                                           ->limit($limit)
                                           ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'meta' => [
                    'limit' => $limit,
                    'generated_at' => now()->toIso8601String()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch last seen activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/status-detail",
     *     summary="Get status detail breakdown",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="unit_kerja",
     *         in="query",
     *         description="Filter by unit kerja",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Time period",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function statusDetail(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'unit_kerja' => 'nullable|string',
                'period' => 'nullable|in:today,week,month,year'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = DiskominfoPengaduanEntry::query();

            // Apply unit kerja filter
            if ($unitKerja = $request->get('unit_kerja')) {
                $query->where('unit_kerja', $unitKerja);
            }

            // Apply period filter
            if ($period = $request->get('period')) {
                if ($period === 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($period === 'week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($period === 'month') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                } elseif ($period === 'year') {
                    $query->whereYear('created_at', Carbon::now()->year);
                }
            }

            $data = $query->select([
                DB::raw('SUM(belum_terverifikasi) as total_belum_terverifikasi'),
                DB::raw('SUM(belum_ditindaklanjuti) as total_belum_ditindaklanjuti'),
                DB::raw('SUM(proses) as total_proses'),
                DB::raw('SUM(selesai) as total_selesai'),
                DB::raw('SUM(total) as total_keseluruhan')
            ])->first();

            // Calculate percentages
            $total = $data->total_keseluruhan;
            $percentages = [
                'belum_terverifikasi' => $total > 0 ? round(($data->total_belum_terverifikasi / $total) * 100, 2) : 0,
                'belum_ditindaklanjuti' => $total > 0 ? round(($data->total_belum_ditindaklanjuti / $total) * 100, 2) : 0,
                'proses' => $total > 0 ? round(($data->total_proses / $total) * 100, 2) : 0,
                'selesai' => $total > 0 ? round(($data->total_selesai / $total) * 100, 2) : 0
            ];

            return response()->json([
                'status' => 'success',
                'data' => [
                    'counts' => $data,
                    'percentages' => $percentages
                ],
                'meta' => [
                    'unit_kerja' => $unitKerja,
                    'period' => $period,
                    'generated_at' => now()->toIso8601String()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch status detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/durasi/rtl",
     *     summary="Get RTL duration statistics",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="unit_kerja",
     *         in="query",
     *         description="Filter by unit kerja",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Time period",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="export",
     *         in="query",
     *         description="Export to CSV",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function rtlDurasi(Request $request): JsonResponse|StreamedResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'unit_kerja' => 'nullable|string',
                'period' => 'nullable|in:today,week,month,year',
                'export' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = DiskominfoPengaduanEntry::query();

            // Apply unit kerja filter
            if ($unitKerja = $request->get('unit_kerja')) {
                $query->where('unit_kerja', $unitKerja);
            }

            // Apply period filter
            if ($period = $request->get('period')) {
                if ($period === 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($period === 'week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($period === 'month') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                } elseif ($period === 'year') {
                    $query->whereYear('created_at', Carbon::now()->year);
                }
            }

            $data = $query->select('unit_kerja', DB::raw('SUM(rtl) as total_rtl'))
                         ->groupBy('unit_kerja')
                         ->get();

            // Handle export
            if ($request->boolean('export')) {
                return $this->exportData($data, 'rtl_durasi');
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'meta' => [
                    'unit_kerja' => $unitKerja,
                    'period' => $period,
                    'generated_at' => now()->toIso8601String()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch RTL duration data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/durasi/rhp",
     *     summary="Get RHP duration statistics",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="unit_kerja",
     *         in="query",
     *         description="Filter by unit kerja",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Time period",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="export",
     *         in="query",
     *         description="Export to CSV",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function rhpDurasi(Request $request): JsonResponse|StreamedResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'unit_kerja' => 'nullable|string',
                'period' => 'nullable|in:today,week,month,year',
                'export' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = DiskominfoPengaduanEntry::query();

            // Apply unit kerja filter
            if ($unitKerja = $request->get('unit_kerja')) {
                $query->where('unit_kerja', $unitKerja);
            }

            // Apply period filter
            if ($period = $request->get('period')) {
                if ($period === 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($period === 'week') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($period === 'month') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                } elseif ($period === 'year') {
                    $query->whereYear('created_at', Carbon::now()->year);
                }
            }

            $data = $query->select('unit_kerja', DB::raw('SUM(rhp) as total_rhp'))
                         ->groupBy('unit_kerja')
                         ->get();

            // Handle export
            if ($request->boolean('export')) {
                return $this->exportData($data, 'rhp_durasi');
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'meta' => [
                    'unit_kerja' => $unitKerja,
                    'period' => $period,
                    'generated_at' => now()->toIso8601String()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch RHP duration data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to CSV
     */
    private function exportData($data, string $type): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$type}_" . date('Y-m-d') . '.csv',
        ];

        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            // Write headers based on type
            switch ($type) {
                case 'unit_kerja':
                    fputcsv($file, ['Unit Kerja', 'Belum Terverifikasi', 'Belum Ditindaklanjuti', 'Proses', 'Selesai', 'Total', 'Persentase TL', 'RTL', 'RHP']);
                    foreach ($data as $row) {
                        fputcsv($file, [
                            $row->unit_kerja,
                            $row->belum_terverifikasi,
                            $row->belum_ditindaklanjuti,
                            $row->proses,
                            $row->selesai,
                            $row->total,
                            $row->persentase_tl,
                            $row->rtl,
                            $row->rhp
                        ]);
                    }
                    break;
                case 'rtl_durasi':
                    fputcsv($file, ['Unit Kerja', 'Total RTL']);
                    foreach ($data as $row) {
                        fputcsv($file, [$row->unit_kerja, $row->total_rtl]);
                    }
                    break;
                case 'rhp_durasi':
                    fputcsv($file, ['Unit Kerja', 'Total RHP']);
                    foreach ($data as $row) {
                        fputcsv($file, [$row->unit_kerja, $row->total_rhp]);
                    }
                    break;
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 