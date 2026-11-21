<?php

use App\Http\Controllers\VaksinController;
use App\Http\Controllers\ImunisasiController;
use App\Http\Controllers\PemberianVitaminController;
use App\Http\Controllers\JadwalImunisasiController;
use App\Http\Controllers\ImunisasiOrangTuaController;
use Illuminate\Support\Facades\Route;

// ── PBI-10: Master Vaksin (Kader saja) ───────────────────────────────────────
Route::middleware('role:kader')->group(function () {
    Route::resource('vaksin', VaksinController::class);
});

// ── PBI-11: Imunisasi, Vitamin & Jadwal (Kader saja) ─────────────────────────
Route::middleware('role:kader')->group(function () {
    // Riwayat imunisasi (index = pilih balita, lalu tampilkan riwayat)
    Route::resource('imunisasi', ImunisasiController::class)->except('show');
    Route::get('imunisasi/{imunisasi}', [ImunisasiController::class, 'show'])->name('imunisasi.show');

    // Pemberian vitamin
    Route::resource('pemberian-vitamin', PemberianVitaminController::class)
        ->parameters(['pemberian-vitamin' => 'pemberianVitamin']);

    // Jadwal imunisasi (CRUD + buat otomatis)
    Route::resource('jadwal-imunisasi', JadwalImunisasiController::class)
        ->parameters(['jadwal-imunisasi' => 'jadwalImunisasi'])
        ->except('show');
    Route::post('jadwal-imunisasi/otomatis', [JadwalImunisasiController::class, 'buatOtomatis'])
        ->name('jadwal-imunisasi.otomatis');
});

// ── PBI-11: Orang Tua — hanya lihat data balita miliknya (termasuk via URL) ──
Route::middleware('role:orang_tua')->group(function () {
    Route::get('anak', [ImunisasiOrangTuaController::class, 'daftarAnak'])->name('anak.index');
    Route::get('anak/{balita}/imunisasi', [ImunisasiOrangTuaController::class, 'show'])->name('anak.imunisasi');
});
