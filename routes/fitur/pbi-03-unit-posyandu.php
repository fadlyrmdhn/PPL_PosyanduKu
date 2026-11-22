<?php

use App\Http\Controllers\UnitPosyanduController;
use Illuminate\Support\Facades\Route;

Route::resource('unit-posyandu', UnitPosyanduController::class)
    ->parameters(['unit-posyandu' => 'unitPosyandu'])
    ->except('show')
    ->middleware('role:admin,kader');
