<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengukuran extends Model
{
    protected $table = 'pengukuran';

    protected $fillable = [
        'id_balita',
        'tanggal_ukur',
        'usia_bulan',
        'berat_badan',
        'tinggi_badan',
        'cara_ukur',
        'lingkar_kepala',
        'lila',
        'id_user',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_ukur'   => 'date',
            'berat_badan'    => 'decimal:2',
            'tinggi_badan'   => 'decimal:2',
            'lingkar_kepala' => 'decimal:2',
            'lila'           => 'decimal:2',
        ];
    }

    // Relasi ke balita
    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class, 'id_balita');
    }

    // Relasi ke kader
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke status gizi (one-to-one)
    public function statusGizi(): HasOne
    {
        return $this->hasOne(StatusGizi::class, 'id_pengukuran');
    }
}
