<?php

namespace Database\Seeders;

use App\Models\StokVitaminObat;
use Illuminate\Database\Seeder;

class StokVitaminObatSeeder extends Seeder
{
    public function run(): void
    {
        $hari = StokVitaminObat::hariIni();

        $contoh = [
            ['Vitamin A Biru (100.000 IU)', 'vitamin', 120, 'kapsul', $hari->copy()->addMonths(8), 20],
            ['Vitamin A Merah (200.000 IU)', 'vitamin', 8, 'kapsul', $hari->copy()->addMonths(10), 20],
            ['Tablet Tambah Darah', 'obat', 200, 'tablet', $hari->copy()->addDays(20), 30],
            ['Paracetamol Sirup', 'obat', 15, 'botol', $hari->copy()->subDays(5), 5],
        ];

        foreach ($contoh as [$nama, $jenis, $jumlah, $satuan, $kedaluwarsa, $minimum]) {
            StokVitaminObat::create([
                'nama' => $nama,
                'jenis' => $jenis,
                'jumlah' => $jumlah,
                'satuan' => $satuan,
                'tanggal_kedaluwarsa' => $kedaluwarsa->toDateString(),
                'stok_minimum' => $minimum,
            ]);
        }
    }
}
