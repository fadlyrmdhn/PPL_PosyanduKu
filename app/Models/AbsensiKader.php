<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiKader extends Model
{
    protected $table = 'absensi_kader';

    protected $fillable = [
        'id_jadwal',
        'id_kader',
        'status',
        'keterangan',
        'dicatat_pada',
    ];

    protected function casts(): array
    {
        return [
            'dicatat_pada' => 'datetime',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalKegiatan::class, 'id_jadwal');
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(Kader::class, 'id_kader');
    }

    // ── Helper ───────────────────────────────────────────────

    /** Kelas CSS lencana berdasarkan status kehadiran. */
    public function badgeStatus(): string
    {
        return match ($this->status) {
            'hadir' => 'teal',
            'izin'  => 'warm',
            'sakit' => 'girl',
            default => 'boy',   // alpa
        };
    }

    /** Label status yang rapi. */
    public function labelStatus(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            default => 'Alpa',
        };
    }
}
