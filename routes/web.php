<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PesananController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('pesanan')->name('pesanan.')->group(function () {
    Route::get('/', [PesananController::class, 'index'])->name('index');
    Route::get('/get-layanan', [PesananController::class, 'getLayanan'])->name('get.layanan');
    Route::post('/buat-pesanan', [PesananController::class, 'buatPesanan'])->name('buat.pesanan');
});
