<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imunisasi extends Model
{
    protected $table = 'imunisasi';

    protected $fillable = [
        'id_balita',
        'id_vaksin',
        'tanggal_pemberian',
        'dosis_ke',
        'keterangan',
        'id_user',
    ];

    protected function casts(): array
    {
        return ['tanggal_pemberian' => 'date'];
    }

    public function balita(): BelongsTo
    {
        return $this->belongsTo(Balita::class, 'id_balita');
    }

    public function vaksin(): BelongsTo
    {
        return $this->belongsTo(Vaksin::class, 'id_vaksin');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
