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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('dashboard')->group(function () {
    Route::get('/summary', [App\Http\Controllers\Api\DashboardController::class, 'summary']);
    Route::get('/unit-kerja', [App\Http\Controllers\Api\DashboardController::class, 'unitKerja']);
    Route::get('/unit-kerja/top', [App\Http\Controllers\Api\DashboardController::class, 'topUnitKerja']);
    Route::get('/activity/last-seen', [App\Http\Controllers\Api\DashboardController::class, 'lastSeen']);
    Route::get('/status-detail', [App\Http\Controllers\Api\DashboardController::class, 'statusDetail']);
    Route::get('/durasi/rtl', [App\Http\Controllers\Api\DashboardController::class, 'rtlDurasi']);
    Route::get('/durasi/rhp', [App\Http\Controllers\Api\DashboardController::class, 'rhpDurasi']);
});
