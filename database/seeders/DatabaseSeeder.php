<?php

namespace Database\Seeders;

use App\Models\Balita;
use App\Models\OrangTua;
use App\Models\UnitPosyandu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kata sandi di-hash otomatis oleh cast pada model User.
        User::create(['nama' => 'Admin Posyandu', 'username' => 'admin', 'email' => 'admin@posyanduku.test', 'password' => 'password', 'role' => 'admin']);
        User::create(['nama' => 'Kader Contoh', 'username' => 'kader', 'email' => 'kader@posyanduku.test', 'password' => 'password', 'role' => 'kader']);
        $akunOrtu = User::create(['nama' => 'Orang Tua Contoh', 'username' => 'ortu', 'email' => 'ortu@posyanduku.test', 'password' => 'password', 'role' => 'orang_tua']);

        $melati = UnitPosyandu::create(['nama_pos' => 'Posyandu Melati', 'rw_dusun' => 'RW 01', 'alamat' => 'Jl. Melati No. 1']);
        $mawar = UnitPosyandu::create(['nama_pos' => 'Posyandu Mawar', 'rw_dusun' => 'RW 02', 'alamat' => 'Jl. Mawar No. 5']);

        $ibu = OrangTua::create([
            'id_user' => $akunOrtu->id, 'nama' => 'Siti Aminah', 'nik' => '3273014501900001',
            'hubungan' => 'ibu', 'no_hp' => '081234567890', 'alamat' => 'Jl. Melati No. 10',
        ]);
        $ayah = OrangTua::create([
            'nama' => 'Budi Santoso', 'nik' => '3273011203880002',
            'hubungan' => 'ayah', 'no_hp' => '081298765432', 'alamat' => 'Jl. Mawar No. 7',
        ]);

        $balita = [
            [$melati, 'Aisyah Putri', '3273016001260001', 4, 'P', 3.20, 49.0, 39, 'Bidan', [$ibu]],
            [$melati, 'Rafi Pratama', '3273011507250002', 14, 'L', 3.50, 50.0, 40, 'Dokter', [$ibu, $ayah]],
            [$melati, 'Naura Salsabila', '3273012002240003', 33, 'P', 2.80, 47.0, 37, 'Bidan', [$ibu]],
            [$mawar, 'Dimas Aditya', '3273010809230004', 38, 'L', 3.10, 48.5, 38, 'Bidan', [$ayah]],
            [$mawar, 'Keisha Anindya', '3273012511210005', 52, 'P', 3.40, 49.5, 40, 'Dokter', [$ayah]],
        ];

        foreach ($balita as [$unit, $nama, $nik, $usiaBulan, $jk, $berat, $panjang, $minggu, $penolong, $ortu]) {
            Balita::create([
                'id_unit' => $unit->id,
                'nama' => $nama,
                'nik' => $nik,
                'tanggal_lahir' => now()->subMonths($usiaBulan)->toDateString(),
                'jenis_kelamin' => $jk,
                'berat_lahir' => $berat,
                'panjang_lahir' => $panjang,
                'usia_kehamilan' => $minggu,
                'penolong_persalinan' => $penolong,
            ])->orangTua()->attach(collect($ortu)->pluck('id'));
        }

        $this->call(PengumumanSeeder::class);
        $this->call(StokVitaminObatSeeder::class);
    }
}
