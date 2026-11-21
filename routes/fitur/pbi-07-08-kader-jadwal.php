<?php

use App\Http\Controllers\KaderController;
use App\Http\Controllers\JadwalKegiatanController;
use App\Http\Controllers\AbsensiKaderController;
use Illuminate\Support\Facades\Route;

// ── FR-09: Data Kader (hanya Admin) ─────────────────────────────────────────
Route::middleware('role:admin')->group(function () {
    Route::resource('kader', KaderController::class);
    Route::post('kader/{kader}/toggle-status', [KaderController::class, 'toggleStatus'])
        ->name('kader.toggle-status');
});

// ── FR-10: Jadwal Kegiatan ───────────────────────────────────────────────────

// Admin: kelola jadwal (CRUD lengkap) + rekap absensi
Route::middleware('role:admin')->group(function () {
    Route::resource('jadwal', JadwalKegiatanController::class);
    Route::get('absensi/rekap', [AbsensiKaderController::class, 'rekap'])
        ->name('absensi.rekap');
});

// Kader: lihat jadwal unitnya (index + show bersifat read-only)
Route::middleware('role:kader')->group(function () {
    Route::get('jadwal', [JadwalKegiatanController::class, 'index'])->name('jadwal.index');
    Route::get('jadwal/{jadwal}', [JadwalKegiatanController::class, 'show'])->name('jadwal.show');

    // Catat / perbarui kehadiran
    Route::get('absensi', [AbsensiKaderController::class, 'index'])->name('absensi.index');
    Route::get('jadwal/{jadwal}/absensi', [AbsensiKaderController::class, 'create'])->name('absensi.create');
    Route::post('jadwal/{jadwal}/absensi', [AbsensiKaderController::class, 'store'])->name('absensi.store');
});
