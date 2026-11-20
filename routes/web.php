<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route(auth()->user()->homeRoute())
    : redirect()->route('login'));

// Memuat semua route fitur dari routes/fitur/*.php (jangan diubah, tambah file baru saja)
foreach (glob(__DIR__.'/fitur/*.php') as $file) {
    require $file;
}
