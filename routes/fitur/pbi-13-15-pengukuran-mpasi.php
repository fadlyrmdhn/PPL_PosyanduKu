<?php

use App\Http\Controllers\MenuMpasiController;
use App\Http\Controllers\PengukuranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PBI-15 Menu MPASI (FR-17)
| Akses: Admin
|--------------------------------------------------------------------------
*/
Route::resource('menu-mpasi', MenuMpasiController::class)
    ->middleware(['auth', 'role:admin']);

/*
|--------------------------------------------------------------------------
| PBI-13 Pengukuran Pertumbuhan & Status Gizi (FR-15)
| Akses: Kader (dan Admin)
|--------------------------------------------------------------------------
*/
Route::resource('pengukuran', PengukuranController::class)
    ->middleware(['auth', 'role:kader,admin']);
