<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kader extends Model
{
    protected $table = 'kader';

    protected $fillable = [
        'id_user',
        'id_unit',
        'nama',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
        'alamat',
        'status',
        'tanggal_bergabung',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir'     => 'date',
            'tanggal_bergabung' => 'date',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitPosyandu::class, 'id_unit');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsensiKader::class, 'id_kader');
    }

    // ── Helper ───────────────────────────────────────────────

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    /** Label lencana status. */
    public function labelStatus(): string
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    /** Kelas CSS lencana status. */
    public function badgeStatus(): string
    {
        return $this->status === 'aktif' ? 'teal' : 'warm';
    }
}
