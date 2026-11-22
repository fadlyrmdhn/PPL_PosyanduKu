<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarAntropometri extends Model
{
    protected $table = 'standar_antropometri';

    public $timestamps = false;

    protected $fillable = [
        'indikator',
        'jenis_kelamin',
        'acuan',
        'median',
        'min_1sd',
        'min_2sd',
        'min_3sd',
        'plus_1sd',
        'plus_2sd',
        'plus_3sd',
    ];

    protected function casts(): array
    {
        return [
            'acuan'    => 'decimal:1',
            'median'   => 'decimal:3',
            'min_1sd'  => 'decimal:3',
            'min_2sd'  => 'decimal:3',
            'min_3sd'  => 'decimal:3',
            'plus_1sd' => 'decimal:3',
            'plus_2sd' => 'decimal:3',
            'plus_3sd' => 'decimal:3',
        ];
    }

    /**
     * Cari baris standar yang paling dekat dengan nilai acuan.
     */
    public static function cari(string $indikator, string $jenisKelamin, float $acuan): ?self
    {
        return self::where('indikator', $indikator)
            ->where('jenis_kelamin', $jenisKelamin)
            ->orderByRaw('ABS(acuan - ?)', [$acuan])
            ->first();
    }
}
