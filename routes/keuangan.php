<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembayaranController;

// Routes khusus untuk role keuangan
Route::middleware(['auth'])->prefix('keuangan')->group(function () {
    
    // Dashboard keuangan
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'keuangan'])->name('keuangan.dashboard');
    
    // Pembayaran management
    Route::prefix('pembayaran')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('keuangan.pembayaran.index');
        Route::get('/menunggu', [PembayaranController::class, 'menungguKonfirmasi'])->name('keuangan.pembayaran.menunggu');
        Route::post('/{id}/konfirmasi', [PembayaranController::class, 'konfirmasi'])->name('keuangan.pembayaran.konfirmasi');
        Route::get('/{id}', [PembayaranController::class, 'show'])->name('keuangan.pembayaran.show');
        Route::get('/statistik/dashboard', [PembayaranController::class, 'statistik'])->name('keuangan.statistik');
    });
    
});