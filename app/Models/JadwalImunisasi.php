<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalImunisasi extends Model
{
    protected $table = 'jadwal_imunisasi';

    protected $fillable = [
        'id_balita',
        'id_vaksin',
        'tanggal_jadwal',
        'status',
    ];

    protected function casts(): array
    {
        return ['tanggal_jadwal' => 'date'];
    }

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class, 'id_balita');
    }

    public function vaksin(): BelongsTo
    {
        return $this->belongsTo(Vaksin::class, 'id_vaksin');
    }

    /** Perbarui status jadwal lewat tanggal yang belum selesai menjadi 'terlewat'. */
    public static function tandaiTerlewat(int $idBalita): void
    {
        static::where('id_balita', $idBalita)
            ->where('status', 'terjadwal')
            ->where('tanggal_jadwal', '<', now()->toDateString())
            ->update(['status' => 'terlewat']);
    }
}
