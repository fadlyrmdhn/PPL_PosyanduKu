<?php

use App\Http\Controllers\PengumumanController;
use Illuminate\Support\Facades\Route;

// Kelola pengumuman: hanya Admin (FR-06). Didaftarkan lebih dulu agar /pengumuman/create tidak tertangkap route show.
Route::middleware('role:admin')->group(function () {
    Route::resource('pengumuman', PengumumanController::class)
        ->parameters(['pengumuman' => 'pengumuman'])
        ->except(['index', 'show']);
    Route::post('pengumuman/{pengumuman}/publikasikan', [PengumumanController::class, 'publikasikan'])
        ->name('pengumuman.publikasikan');
});

// Baca pengumuman: semua peran. Kader dan Orang Tua hanya melihat yang sudah dipublikasikan.
Route::middleware('role:admin,kader,orang_tua')->group(function () {
    Route::resource('pengumuman', PengumumanController::class)
        ->parameters(['pengumuman' => 'pengumuman'])
        ->only(['index', 'show']);
});
