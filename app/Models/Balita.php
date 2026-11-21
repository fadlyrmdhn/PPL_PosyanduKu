<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Balita extends Model
{
    protected $table = 'balita';

    protected $fillable = [
        'id_unit', 'nama', 'nik', 'tanggal_lahir', 'jenis_kelamin',
        'berat_lahir', 'panjang_lahir', 'usia_kehamilan', 'penolong_persalinan',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function unitPosyandu(): BelongsTo
    {
        return $this->belongsTo(UnitPosyandu::class, 'id_unit');
    }

    public function orangTua(): BelongsToMany
    {
        return $this->belongsToMany(OrangTua::class, 'balita_orang_tua', 'id_balita', 'id_orang_tua');
    }
}
