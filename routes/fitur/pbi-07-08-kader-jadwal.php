<?php

use App\Http\Controllers\KaderController;
use App\Http\Controllers\JadwalKegiatanController;
use App\Http\Controllers\AbsensiKaderController;
use Illuminate\Support\Facades\Route;

// FR-09: Data Kader (hanya Admin)
Route::middleware('role:admin')->group(function () {
    Route::resource('kader', KaderController::class);
    Route::post('kader/{kader}/toggle-status', [KaderController::class, 'toggleStatus'])
        ->name('kader.toggle-status');
});

// FR-10: Jadwal Kegiatan
// Admin + Kader: lihat daftar dan detail (hanya didaftarkan SEKALI agar tidak saling menimpa)
Route::middleware('role:admin,kader')->group(function () {
    Route::get('jadwal', [JadwalKegiatanController::class, 'index'])->name('jadwal.index');
    Route::get('jadwal/{jadwal}', [JadwalKegiatanController::class, 'show'])
        ->whereNumber('jadwal')->name('jadwal.show');
});

// Admin saja: tambah, ubah, hapus jadwal + rekap absensi
Route::middleware('role:admin')->group(function () {
    Route::resource('jadwal', JadwalKegiatanController::class)->except(['index', 'show']);
    Route::get('absensi/rekap', [AbsensiKaderController::class, 'rekap'])->name('absensi.rekap');
});

// Kader saja: catat kehadiran
Route::middleware('role:kader')->group(function () {
    Route::get('absensi', [AbsensiKaderController::class, 'index'])->name('absensi.index');
    Route::get('jadwal/{jadwal}/absensi', [AbsensiKaderController::class, 'create'])->name('absensi.create');
    Route::post('jadwal/{jadwal}/absensi', [AbsensiKaderController::class, 'store'])->name('absensi.store');
});