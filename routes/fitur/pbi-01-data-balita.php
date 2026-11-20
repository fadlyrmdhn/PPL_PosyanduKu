<?php

use App\Http\Controllers\BalitaController;
use App\Http\Controllers\OrangTuaController;
use Illuminate\Support\Facades\Route;

Route::resource('balita', BalitaController::class)->parameters(['balita' => 'balita']);
Route::resource('orang-tua', OrangTuaController::class)->parameters(['orang-tua' => 'orangTua'])->except('show');
