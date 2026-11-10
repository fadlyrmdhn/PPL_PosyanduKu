<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balita extends Model
{
    protected $table = 'balita';

    protected $fillable = [
        'orang_tua_id', 'unit_posyandu_id', 'nama', 'nik', 'tanggal_lahir',
        'jenis_kelamin', 'berat_lahir', 'panjang_lahir', 'usia_kehamilan',
        'penolong_persalinan',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id');
    }

    public function unitPosyandu(): BelongsTo
    {
        return $this->belongsTo(UnitPosyandu::class, 'unit_posyandu_id');
    }
}