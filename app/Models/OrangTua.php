<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrangTua extends Model
{
    protected $table = 'orang_tua';

    protected $fillable = ['id_user', 'nama', 'nik', 'hubungan', 'no_hp', 'alamat'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function balita(): BelongsToMany
    {
        return $this->belongsToMany(Balita::class, 'balita_orang_tua', 'id_orang_tua', 'id_balita');
    }
}
