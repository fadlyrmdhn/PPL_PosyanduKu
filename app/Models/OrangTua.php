<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    protected $table = 'orang_tua';

    protected $fillable = ['user_id', 'nama', 'nik', 'no_telepon', 'alamat'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function balita(): HasMany
    {
        return $this->hasMany(Balita::class, 'orang_tua_id');
    }
}