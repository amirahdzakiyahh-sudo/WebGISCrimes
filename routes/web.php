<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\KriminalitasController;
use App\Http\Controllers\Admin\RekapController;

// =====================
// HALAMAN DAN PETA//
// =====================
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/peta', [MapController::class, 'index'])->name('map.index');
Route::get('/api/map-data', [MapController::class, 'mapData'])->name('map.data');

// =====================
// AUTH LOGIN / LOGOUT
// =====================
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// =====================
// ADMIN PANEL
// =====================
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', fn() => redirect()->route('admin.kriminalitas.index'))->name('dashboard');
    Route::resource('kecamatan', KecamatanController::class);
    // Tambahkan baris ini di sini (harus di atas resource kriminalitas)
    Route::delete('kriminalitas/bulk-destroy', [KriminalitasController::class, 'bulkDestroy'])->name('kriminalitas.bulk-destroy');
    Route::resource('kriminalitas', KriminalitasController::class);
    Route::get('/rekap-wilayah', [RekapController::class, 'index'])->name('rekap.index');
});