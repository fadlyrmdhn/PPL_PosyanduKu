<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balita extends Model
{
    protected $table = 'balita';

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'nama_orang_tua',
        'alamat',
        'id_user',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    // Relasi ke pengukuran
    public function pengukuran(): HasMany
    {
        return $this->hasMany(Pengukuran::class, 'id_balita');
    }

    // Relasi ke user (kader pendamping)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
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
