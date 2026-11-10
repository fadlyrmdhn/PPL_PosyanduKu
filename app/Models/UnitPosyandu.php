<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitPosyandu extends Model
{
    protected $table = 'unit_posyandu';

    protected $fillable = ['wilayah_id', 'nama_pos', 'rw_dusun', 'alamat'];

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function balita(): HasMany
    {
        return $this->hasMany(Balita::class, 'unit_posyandu_id');
    }

    public function kader(): HasMany
    {
        return $this->hasMany(Kader::class, 'unit_posyandu_id');
    }
}