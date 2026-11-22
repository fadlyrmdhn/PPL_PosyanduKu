<?php

use App\Http\Controllers\StokVitaminObatController;
use Illuminate\Support\Facades\Route;

// Kelola stok vitamin dan obat: hanya Admin (FR-07).
Route::middleware('role:admin')->group(function () {
    Route::resource('stok-vitamin-obat', StokVitaminObatController::class)
        ->parameters(['stok-vitamin-obat' => 'stok'])
        ->except('show');

    Route::post('stok-vitamin-obat/{stok}/penyesuaian', [StokVitaminObatController::class, 'penyesuaian'])
        ->name('stok-vitamin-obat.penyesuaian');
});
