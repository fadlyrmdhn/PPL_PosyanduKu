<?php

namespace App\Models;

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
    protected $primaryKey = 'id_pengumuman';

    // ERD tidak memiliki kolom created_at/updated_at.
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'judul',
        'isi',
        'status',
        'tanggal_terbit',
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    /** Class diagram: User 1 - 0..* Pengumuman ("membuat"). */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /** Nama pembuat. Mendukung kolom `nama` (ERD) maupun `name` (bawaan Laravel) pada tabel users. */
    public function getNamaPembuatAttribute(): ?string
    {
        $pembuat = $this->pembuat;

        return $pembuat?->getAttribute('nama') ?? $pembuat?->getAttribute('name');
    }

    /** Class diagram: publikasikan(). Mengubah status menjadi dipublikasikan dan mengisi tanggal terbit jika kosong. */
    public function publikasikan(): static
    {
        $this->status = self::STATUS_DIPUBLIKASIKAN;
        $this->tanggal_terbit ??= now();
        $this->save();

        return $this;
    }

    public function isDipublikasikan(): bool
    {
        return $this->status === self::STATUS_DIPUBLIKASIKAN;
    }

    /** Hanya pengumuman yang sudah dipublikasikan (yang boleh dibaca Kader dan Orang Tua). */
    public function scopeDipublikasikan(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DIPUBLIKASIKAN);
    }

    /**
     * Terbaru di atas; draft (belum bertanggal) di paling atas agar Admin melihatnya.
     * Dipakai juga untuk "pengumuman terbaru" di dashboard (FR-02): Pengumuman::dipublikasikan()->terbaru()->limit(3).
     */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByRaw('tanggal_terbit IS NULL DESC')
            ->orderByDesc('tanggal_terbit')
            ->orderByDesc('id_pengumuman');
    }
}
