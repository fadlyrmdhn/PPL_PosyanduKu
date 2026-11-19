<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PBI-4 Pengumuman
require __DIR__.'/pengumuman.php';

// Login sementara (hapus setelah autentikasi PBI-1 di-merge)
require __DIR__.'/auth-sementara.php';
