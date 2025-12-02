<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KontrakController;

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

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================
// KONTRAK + DETAIL KONTRAK
// =========================

// Kontrak (CRUD utama)
Route::get('/kontraks', [KontrakController::class, 'index']);          // GET    /api/kontraks
Route::post('/kontraks', [KontrakController::class, 'store']);         // POST   /api/kontraks
Route::get('/kontraks/{id}', [KontrakController::class, 'show']);      // GET    /api/kontraks/{id}
Route::put('/kontraks/{id}', [KontrakController::class, 'update']);    // PUT    /api/kontraks/{id}
Route::delete('/kontraks/{id}', [KontrakController::class, 'destroy']); // DELETE /api/kontraks/{id}

// Detail Kontrak (per Kontrak)
Route::get('/kontraks/{kontrakId}/details', [KontrakController::class, 'indexDetail']);   // GET    /api/kontraks/{kontrakId}/details
Route::post('/kontraks/{kontrakId}/details', [KontrakController::class, 'storeDetail']);  // POST   /api/kontraks/{kontrakId}/details
Route::delete(
    '/kontraks/{kontrakId}/details/{detailId}',
    [KontrakController::class, 'destroyDetail']
); // DELETE /api/kontraks/{kontrakId}/details/{detailId}

// KONTRAK LAPORAN per Detail Kontrak
Route::get('/kontraks/{kontrakLaporanId}/laporans', [KontrakController::class, 'indexKontrakLaporan']);   // GET    /api/kontraks/{kontrakLaporanId}/laporans
Route::post('/kontraks/{kontrakLaporanId}/laporans', [KontrakController::class, 'storeKontrakLaporan']);  // POST   /api/kontraks/{kontrakLaporanId}/laporans
Route::delete(
    '/kontraks/{kontrakLaporanId}/laporans/{laporanId}',
    [KontrakController::class, 'destroyKontrakLaporan']
); // DELETE /api/kontraks/{kontrakLaporanId}/laporans
Route::post(
    '/kontraks/{kontrakDetailId}/laporans/generate',
    [KontrakController::class, 'generateKontrakLaporan']
); // POST /api/kontraks/{kontrakDetailId}/laporans/generate


// =========================
// LAPORAN + DETAIL LAPORAN
// =========================

// Laporan (CRUD utama) per kontrak laporan
Route::get('/laporans/{kontrakLaporanId}', [KontrakController::class, 'indexLaporan']);          // GET    /api/laporans/{kontrakLaporanId}
Route::post('/laporans/{kontrakLaporanId}', [KontrakController::class, 'storeLaporan']);         // POST   /api/laporans/{kontrakLaporanId}
Route::delete('/laporans/{id}', [KontrakController::class, 'destroyLaporan']); // DELETE /api/laporans/{id}
Route::post(
    '/laporans/{kontrakLaporanId}/{kontrakDetailId}',
    [KontrakController::class, 'generateLaporan']
); // POST /api/laporans/{kontrakLaporanId}/{kontrakDetailId}

// Detail Laporan (per Laporan)
Route::get('/laporandetails/{laporanId}', [KontrakController::class, 'indexLaporanDetail']); // GET    /api/laporans/{laporanId}/details

Route::post('/laporandetails/{laporanId}', [KontrakController::class, 'storeLaporanDetail']);

Route::delete('/laporandetails/{laporanId}/{detailId}', [KontrakController::class, 'destroyLaporanDetail']); // DELETE /api/laporans/{laporanId}/details/{detailId}