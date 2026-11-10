<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kader extends Model
{
    protected $table = 'kader';

    protected $fillable = ['user_id', 'unit_posyandu_id', 'nama', 'no_telepon', 'aktif'];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unitPosyandu(): BelongsTo
    {
        return $this->belongsTo(UnitPosyandu::class, 'unit_posyandu_id');
    }
}