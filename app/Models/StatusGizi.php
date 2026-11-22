<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusGizi extends Model
{
    protected $table = 'status_gizi';

    protected $fillable = [
        'id_pengukuran',
        'zscore_bb_u',
        'kategori_bb_u',
        'zscore_tb_u',
        'kategori_tb_u',
        'zscore_bb_tb',
        'kategori_bb_tb',
    ];

    protected function casts(): array
    {
        return [
            'zscore_bb_u'  => 'decimal:3',
            'zscore_tb_u'  => 'decimal:3',
            'zscore_bb_tb' => 'decimal:3',
        ];
    }

    // Relasi ke pengukuran
    public function pengukuran(): BelongsTo
    {
        return $this->belongsTo(Pengukuran::class, 'id_pengukuran');
    }

    /**
     * Warna badge untuk kategori status gizi.
     */
    public static function warnaBadge(string $kategori): string
    {
        return match (true) {
            str_contains($kategori, 'Normal')     => 'green',
            str_contains($kategori, 'Kurang')     => 'yellow',
            str_contains($kategori, 'Lebih')      => 'orange',
            str_contains($kategori, 'Buruk')      => 'red',
            str_contains($kategori, 'Sangat')     => 'red',
            str_contains($kategori, 'Pendek')     => 'yellow',
            str_contains($kategori, 'Tinggi')     => 'blue',
            str_contains($kategori, 'Gemuk')      => 'orange',
            str_contains($kategori, 'Obesitas')   => 'red',
            default                               => 'gray',
        };
    }
}
