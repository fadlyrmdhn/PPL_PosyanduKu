<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokVitaminObat extends Model
{
    use HasFactory;

    public const JENIS = ['vitamin', 'obat'];

    /** Batas hari untuk penanda "mendekati kedaluwarsa". */
    public const BATAS_HARI_MENDEKATI_KEDALUWARSA = 30;

    protected $table = 'stok_vitamin_obat';

    protected $fillable = ['nama', 'jenis', 'jumlah', 'satuan', 'tanggal_kedaluwarsa', 'stok_minimum'];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'stok_minimum' => 'integer',
            'tanggal_kedaluwarsa' => 'date',
        ];
    }

    /**
     * Tanggal hari ini menurut WIB. config/app.php (milik bersama) masih UTC, sehingga pukul 00.00-07.00 WIB
     * "hari ini" versi UTC masih kemarin dan penanda kedaluwarsa meleset satu hari.
     */
    public static function hariIni(): Carbon
    {
        return now('Asia/Jakarta')->startOfDay();
    }

    /** Stok menipis: jumlah sudah di bawah atau sama dengan batas minimum. */
    public function scopeMenipis(Builder $query): Builder
    {
        return $query->whereColumn('jumlah', '<=', 'stok_minimum');
    }

    public function scopeKedaluwarsa(Builder $query): Builder
    {
        return $query->whereDate('tanggal_kedaluwarsa', '<', static::hariIni()->toDateString());
    }

    public function scopeMendekatiKedaluwarsa(Builder $query): Builder
    {
        return $query
            ->whereDate('tanggal_kedaluwarsa', '>=', static::hariIni()->toDateString())
            ->whereDate('tanggal_kedaluwarsa', '<=', static::hariIni()->addDays(self::BATAS_HARI_MENDEKATI_KEDALUWARSA)->toDateString());
    }

    public function isHabis(): bool
    {
        return $this->jumlah === 0;
    }

    public function isMenipis(): bool
    {
        return ! $this->isHabis() && $this->jumlah <= $this->stok_minimum;
    }

    public function isKedaluwarsa(): bool
    {
        return $this->tanggal_kedaluwarsa->toDateString() < static::hariIni()->toDateString();
    }

    public function isMendekatiKedaluwarsa(): bool
    {
        return ! $this->isKedaluwarsa()
            && $this->tanggal_kedaluwarsa->toDateString() <= static::hariIni()->addDays(self::BATAS_HARI_MENDEKATI_KEDALUWARSA)->toDateString();
    }

    /**
     * Penanda untuk tabel: label => tingkat ('red' = bahaya, 'amber' = waspada).
     *
     * @return array<string, string>
     */
    public function peringatan(): array
    {
        $daftar = [];

        if ($this->isHabis()) {
            $daftar['Habis'] = 'red';
        } elseif ($this->isMenipis()) {
            $daftar['Menipis'] = 'amber';
        }

        if ($this->isKedaluwarsa()) {
            $daftar['Kedaluwarsa'] = 'red';
        } elseif ($this->isMendekatiKedaluwarsa()) {
            $daftar['Mendekati kedaluwarsa'] = 'amber';
        }

        return $daftar;
    }
}
