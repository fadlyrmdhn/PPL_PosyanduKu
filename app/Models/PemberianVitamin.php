<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemberianVitamin extends Model
{
    protected $table = 'pemberian_vitamin';

    protected $fillable = [
        'id_balita',
        'nama_vitamin',
        'tanggal_pemberian',
        'dosis',
        'keterangan',
        'id_user',
    ];

    protected function casts(): array
    {
        return ['tanggal_pemberian' => 'date'];
    }

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class, 'id_balita');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
