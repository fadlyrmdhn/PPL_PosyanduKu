<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vaksin extends Model
{
    protected $table = 'vaksin';

    protected $fillable = [
        'nama_vaksin',
        'usia_pemberian_bulan',
        'dosis_ke',
        'jumlah_dosis',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'usia_pemberian_bulan' => 'integer',
            'dosis_ke'             => 'integer',
        ];
    }

    public function imunisasi(): HasMany
    {
        return $this->hasMany(Imunisasi::class, 'id_vaksin');
    }

    public function jadwalImunisasi(): HasMany
    {
        return $this->hasMany(JadwalImunisasi::class, 'id_vaksin');
    }

    /** Apakah vaksin ini sudah dipakai di catatan imunisasi. */
    public function sudahDipakai(): bool
    {
        return $this->imunisasi()->exists();
    }
}
