<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('beranda')->name('dashboard.')->group(function () {
    Route::get('admin', [DashboardController::class, 'admin'])->middleware('role:admin')->name('admin');
    Route::get('kader', [DashboardController::class, 'kader'])->middleware('role:kader')->name('kader');
    Route::get('orang-tua', [DashboardController::class, 'orangTua'])->middleware('role:orang_tua')->name('orang-tua');
});
