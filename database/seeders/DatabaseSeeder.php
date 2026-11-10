<?php

namespace Database\Seeders;

use App\Models\Kader;
use App\Models\UnitPosyandu;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Posyandu',
            'email' => 'admin@posyanduku.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $wilayah = Wilayah::create([
            'nama_wilayah' => 'Kelurahan Contoh',
            'keterangan' => 'Data contoh untuk pengembangan',
        ]);

        $unit = UnitPosyandu::create([
            'wilayah_id' => $wilayah->id,
            'nama_pos' => 'Posyandu Melati',
            'rw_dusun' => 'RW 01',
            'alamat' => 'Jl. Contoh No. 1',
        ]);

        $userKader = User::create([
            'name' => 'Kader Contoh',
            'email' => 'kader@posyanduku.test',
            'password' => Hash::make('password'),
            'role' => 'kader',
            'status' => 'aktif',
        ]);

        Kader::create([
            'user_id' => $userKader->id,
            'unit_posyandu_id' => $unit->id,
            'nama' => 'Kader Contoh',
            'no_telepon' => '081234567890',
            'aktif' => true,
        ]);

        User::create([
            'name' => 'Orang Tua Contoh',
            'email' => 'ortu@posyanduku.test',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
            'status' => 'aktif',
        ]);
    }
}