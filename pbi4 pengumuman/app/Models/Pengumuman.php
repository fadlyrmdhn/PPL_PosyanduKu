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

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'tanggal_terbit',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /** Hanya pengumuman yang sudah dipublikasikan (yang boleh dibaca Orang Tua). */
    public function scopeDipublikasikan(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DIPUBLIKASIKAN);
    }

    /** Urutan terbaru di atas. Bisa dipakai juga untuk "pengumuman terbaru" di dashboard (FR-02). */
    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByDesc('tanggal_terbit')->orderByDesc('id');
    }

    public function isDipublikasikan(): bool
    {
        return $this->status === self::STATUS_DIPUBLIKASIKAN;
    }
}
