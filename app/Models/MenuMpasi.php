<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuMpasi extends Model
{
    protected $table = 'menu_mpasi';

    protected $fillable = [
        'nama_menu',
        'usia_min_bulan',
        'usia_max_bulan',
        'bahan',
        'cara_pembuatan',
        'kandungan_gizi',
        'gambar',
    ];
}
