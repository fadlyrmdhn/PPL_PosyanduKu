<?php

use App\Http\Controllers\AuthSementaraController;
use Illuminate\Support\Facades\Route;

/*
 | LOGIN SEMENTARA - hanya agar PBI-4 bisa dicoba sebelum autentikasi PBI-1 (Fadly) di-merge.
 | Setelah PBI-1 masuk ke main, HAPUS: file ini, AuthSementaraController, view auth-sementara/,
 | AkunContohSeeder, dan baris require-nya di routes/web.php.
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthSementaraController::class, 'form'])->name('login');
    Route::post('/login', [AuthSementaraController::class, 'proses']);
});

Route::post('/logout', [AuthSementaraController::class, 'keluar'])
    ->middleware('auth')
    ->name('logout');
