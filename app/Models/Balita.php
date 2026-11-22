<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Balita extends Model
{
    protected $table = 'balita';

    protected $fillable = [
        'id_unit', 'nama', 'nik', 'tanggal_lahir', 'jenis_kelamin',
        'berat_lahir', 'panjang_lahir', 'usia_kehamilan', 'penolong_persalinan',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function unitPosyandu(): BelongsTo
    {
        return $this->belongsTo(UnitPosyandu::class, 'id_unit');
    }

    public function orangTua(): BelongsToMany
    {
        return $this->belongsToMany(OrangTua::class, 'balita_orang_tua', 'id_balita', 'id_orang_tua');
    }

    public function imunisasi(): HasMany
    {
        return $this->hasMany(Imunisasi::class, 'id_balita');
    }

    public function pemberianVitamin(): HasMany
    {
        return $this->hasMany(PemberianVitamin::class, 'id_balita');
    }

    public function jadwalImunisasi(): HasMany
    {
        return $this->hasMany(JadwalImunisasi::class, 'id_balita');
    }

    public function pengukuran(): HasMany
    {
        return $this->hasMany(Pengukuran::class, 'id_balita');
    }

    /**
     * Hitung usia dalam bulan pada tanggal tertentu.
     */
    public function usiabulanPada(\Carbon\Carbon|string $tanggal): int
    {
        $tgl = \Carbon\Carbon::parse($tanggal);
        return (int) $this->tanggal_lahir->diffInMonths($tgl);
    }
}
