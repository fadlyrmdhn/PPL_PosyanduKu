<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('balita.index'));

// Memuat semua route fitur dari routes/fitur/*.php (jangan diubah, tambah file baru saja)
foreach (glob(__DIR__.'/fitur/*.php') as $file) {
    require $file;
}
