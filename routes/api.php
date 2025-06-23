<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public authentication routes
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// Protected authentication routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/refresh', [App\Http\Controllers\Api\AuthController::class, 'refresh']);
});

// Protected API Routes - All routes require Bearer token authentication
Route::middleware('auth:sanctum')->group(function () {
    
    // Dashboard API Routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [App\Http\Controllers\Api\DashboardController::class, 'summary']);
        Route::get('/unit-kerja', [App\Http\Controllers\Api\DashboardController::class, 'unitKerja']);
        Route::get('/unit-kerja/top', [App\Http\Controllers\Api\DashboardController::class, 'topUnitKerja']);
        Route::get('/activity/last-seen', [App\Http\Controllers\Api\DashboardController::class, 'lastSeen']);
        Route::get('/status-detail', [App\Http\Controllers\Api\DashboardController::class, 'statusDetail']);
        Route::get('/durasi/rtl', [App\Http\Controllers\Api\DashboardController::class, 'rtlDurasi']);
        Route::get('/durasi/rhp', [App\Http\Controllers\Api\DashboardController::class, 'rhpDurasi']);
    });

    // Dapodik API Routes
    Route::prefix('dapodik')->group(function () {
        // Dashboard & Analytics
        Route::get('/dashboard/summary', [App\Http\Controllers\Api\DapodikController::class, 'dashboardSummary']);
        Route::get('/dashboard/statistics', [App\Http\Controllers\Api\DapodikController::class, 'dashboardStatistics']);
        
        // Schools
        Route::get('/schools', [App\Http\Controllers\Api\DapodikController::class, 'schools']);
        Route::get('/schools/{npsn}', [App\Http\Controllers\Api\DapodikController::class, 'schoolDetail']);
        Route::get('/schools/nearby', [App\Http\Controllers\Api\DapodikController::class, 'nearbySchools']);
        
        // Geographic & Location
        Route::get('/locations/kabupaten', [App\Http\Controllers\Api\DapodikController::class, 'kabupatenList']);
        Route::get('/locations/kecamatan', [App\Http\Controllers\Api\DapodikController::class, 'kecamatanList']);
        Route::get('/locations/desa', [App\Http\Controllers\Api\DapodikController::class, 'desaList']);
        
        // Analytics
        Route::get('/analytics/students', [App\Http\Controllers\Api\DapodikController::class, 'studentAnalytics']);
        Route::get('/analytics/teachers', [App\Http\Controllers\Api\DapodikController::class, 'teacherAnalytics']);
        Route::get('/analytics/classrooms', [App\Http\Controllers\Api\DapodikController::class, 'classroomAnalytics']);
        
        // Accreditation
        Route::get('/accreditation/summary', [App\Http\Controllers\Api\DapodikController::class, 'accreditationSummary']);
        Route::get('/accreditation/expiring', [App\Http\Controllers\Api\DapodikController::class, 'expiringAccreditation']);
        
        // Comparison & Benchmark
        Route::get('/compare/kabupaten', [App\Http\Controllers\Api\DapodikController::class, 'compareKabupaten']);
        Route::get('/compare/bentuk-pendidikan', [App\Http\Controllers\Api\DapodikController::class, 'compareEducationTypes']);
        
        // Search & Discovery
        Route::get('/search', [App\Http\Controllers\Api\DapodikController::class, 'search']);
        Route::get('/map-data', [App\Http\Controllers\Api\DapodikController::class, 'mapData']);
        
        // Trends & Forecasting
        Route::get('/trends/enrollment', [App\Http\Controllers\Api\DapodikController::class, 'enrollmentTrends']);
        Route::get('/trends/growth', [App\Http\Controllers\Api\DapodikController::class, 'growthTrends']);
        
        // Export
        Route::get('/export/excel', [App\Http\Controllers\Api\DapodikController::class, 'exportExcel']);
        
        // Health Check
        Route::get('/health', [App\Http\Controllers\Api\DapodikController::class, 'health']);
    });
});
