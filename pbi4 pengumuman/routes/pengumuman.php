<?php

use App\Http\Controllers\PengumumanController;
use Illuminate\Support\Facades\Route;

// Tambahkan di routes/web.php:  require __DIR__.'/pengumuman.php';
Route::middleware('auth')->group(function () {
    Route::resource('pengumuman', PengumumanController::class)
        ->parameters(['pengumuman' => 'pengumuman']);
});
