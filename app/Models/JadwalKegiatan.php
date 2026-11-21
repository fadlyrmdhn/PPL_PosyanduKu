<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalKegiatan extends Model
{
    protected $table = 'jadwal_kegiatan';

    protected $fillable = [
        'id_unit',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────

    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitPosyandu::class, 'id_unit');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsensiKader::class, 'id_jadwal');
    }

    // ── Helper ───────────────────────────────────────────────

    /** Label jenis kegiatan yang rapi. */
    public function labelJenis(): string
    {
        return match ($this->jenis_kegiatan) {
            'penimbangan' => 'Penimbangan',
            'imunisasi'   => 'Imunisasi',
            'penyuluhan'  => 'Penyuluhan',
            default       => 'Lainnya',
        };
    }

    /** Ikon Bootstrap Icons sesuai jenis. */
    public function iconJenis(): string
    {
        return match ($this->jenis_kegiatan) {
            'penimbangan' => 'bi-speedometer2',
            'imunisasi'   => 'bi-shield-plus',
            'penyuluhan'  => 'bi-megaphone',
            default       => 'bi-calendar-event',
        };
    }
}
