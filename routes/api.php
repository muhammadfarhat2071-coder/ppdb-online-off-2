<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\GelombangController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\LaporanController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes without middleware for testing
Route::apiResource('pendaftar', PendaftarController::class);
Route::apiResource('jurusan', JurusanController::class);
Route::apiResource('gelombang', GelombangController::class);
Route::apiResource('wilayah', WilayahController::class);
Route::apiResource('pengguna', PenggunaController::class);

// Laporan routes
Route::prefix('laporan')->group(function () {
    Route::get('/', [LaporanController::class, 'index']);
    Route::get('/harian', [LaporanController::class, 'generateHarian']);
    Route::get('/bulanan', [LaporanController::class, 'generateBulanan']);
    Route::get('/komprehensif', [LaporanController::class, 'generateKomprehensif']);
    Route::get('/download', [LaporanController::class, 'download'])->name('laporan.download');
});