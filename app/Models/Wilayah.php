<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = ['nama_wilayah', 'keterangan'];

    public function unitPosyandu(): HasMany
    {
        return $this->hasMany(UnitPosyandu::class, 'wilayah_id');
    }
}