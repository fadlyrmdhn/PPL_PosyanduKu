<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UnitPosyandu extends Model
{
    protected $table = 'unit_posyandu';

    protected $fillable = ['nama_pos', 'rw_dusun', 'alamat'];

    public function balita(): HasMany
    {
        return $this->hasMany(Balita::class, 'id_unit');
    }

    public function kader(): HasMany
    {
        return $this->hasMany(Kader::class, 'id_unit');
    }

    public function jadwalKegiatan(): HasMany
    {
        return $this->hasMany(JadwalKegiatan::class, 'id_unit');
    }

    /** Unit dianggap terpakai bila masih dirujuk balita atau kader (tabel kader dibuat anggota lain). */
    public function masihDipakai(): bool
    {
        if ($this->balita()->exists()) {
            return true;
        }

        return Schema::hasTable('kader')
            && DB::table('kader')->where('id_unit', $this->id)->exists();
    }
}
