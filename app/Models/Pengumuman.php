<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_DIPUBLIKASIKAN = 'dipublikasikan';
    public const STATUS = [self::STATUS_DRAFT, self::STATUS_DIPUBLIKASIKAN];

    protected $table = 'pengumuman';

    protected $fillable = ['id_user', 'judul', 'isi', 'status', 'tanggal_terbit'];

    protected $casts = ['tanggal_terbit' => 'datetime'];

    /**
     * Waktu sekarang dalam WIB. config/app.php (milik bersama) masih UTC, jadi tanggal terbit
     * disimpan dan ditampilkan sebagai waktu dinding WIB agar "hari ini" tidak meleset pukul 00.00-07.00.
     */
    public static function sekarang(): Carbon
    {
        return now('Asia/Jakarta');
    }

    /** User 1 - 0..* Pengumuman ("membuat"). */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /** Mengubah status menjadi dipublikasikan dan mengisi tanggal terbit bila masih kosong. */
    public function publikasikan(): static
    {
        $this->status = self::STATUS_DIPUBLIKASIKAN;
        $this->tanggal_terbit ??= static::sekarang();
        $this->save();

        return $this;
    }

    public function isDipublikasikan(): bool
    {
        return $this->status === self::STATUS_DIPUBLIKASIKAN;
    }

    /** Tanggal terbit untuk tampilan: d-m-Y H:i WIB, atau "-" bila belum terbit. */
    public function tanggalTerbitLabel(): string
    {
        return $this->tanggal_terbit ? $this->tanggal_terbit->format('d-m-Y H:i').' WIB' : '-';
    }

    /** Hanya yang sudah dipublikasikan (boleh dibaca Kader dan Orang Tua). */
    public function scopeDipublikasikan(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DIPUBLIKASIKAN);
    }

    /** Terbaru di atas; draft yang belum bertanggal di paling atas agar Admin melihatnya. */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByRaw('tanggal_terbit IS NULL DESC')
            ->orderByDesc('tanggal_terbit')
            ->orderByDesc('id');
    }
}
