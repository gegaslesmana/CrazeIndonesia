<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/presensi', App\Http\Controllers\Api\PresensiController::class);
Route::post('/presensi/log', [App\Http\Controllers\Api\PresensiController::class, 'log']);

// Endpoint fingerprint tanpa rate limiting
// Karena sudah ada mekanisme duplikasi via cache di controller
// dan mesin fingerprint perlu mengirim data real-time tanpa batasan
Route::post('/presensi/receive-data', [App\Http\Controllers\Api\PresensiController::class, 'receiveRevoData'])
    ->withoutMiddleware('throttle:api');

// Endpoint untuk menerima data dari mesin Fingerspot REVO melalui ADMS
// Route::post('/presensi/revo', [App\Http\Controllers\Api\PresensiController::class, 'receiveRevoData'])
//     ->withoutMiddleware('throttle:api');
// Update API Routes
Route::prefix('update')->group(function () {
    // Public endpoints (tidak perlu auth) - Route spesifik dulu
    Route::get('/check', [App\Http\Controllers\Api\UpdateController::class, 'checkUpdate']);
    Route::get('/version', [App\Http\Controllers\Api\UpdateController::class, 'getCurrentVersion']);
    Route::get('/list', [App\Http\Controllers\Api\UpdateController::class, 'listUpdates']);

    // Protected endpoints (disarankan menggunakan auth) - Route spesifik dulu
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/history', [App\Http\Controllers\Api\UpdateController::class, 'history']);
        Route::get('/log/{id}', [App\Http\Controllers\Api\UpdateController::class, 'showLog']);
        Route::get('/status/{logId}', [App\Http\Controllers\Api\UpdateController::class, 'getStatus']);
        Route::post('/{version}/download', [App\Http\Controllers\Api\UpdateController::class, 'downloadUpdate']);
        Route::post('/{version}/install', [App\Http\Controllers\Api\UpdateController::class, 'installUpdate']);
        Route::post('/{version}/update-now', [App\Http\Controllers\Api\UpdateController::class, 'updateNow']);
    });

    // Route dengan parameter di akhir (agar tidak conflict)
    Route::get('/{version}', [App\Http\Controllers\Api\UpdateController::class, 'show']);
});
