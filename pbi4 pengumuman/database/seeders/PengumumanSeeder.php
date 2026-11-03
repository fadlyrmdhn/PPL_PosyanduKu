<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        Pengumuman::create([
            'judul' => 'Jadwal Posyandu Bulan November',
            'isi' => "Posyandu akan dilaksanakan pada hari Rabu pukul 08.00 WIB.\nMohon membawa Buku KIA/KMS balita.",
            'tanggal_terbit' => now()->toDateString(),
            'status' => Pengumuman::STATUS_DIPUBLIKASIKAN,
        ]);

        Pengumuman::create([
            'judul' => 'Imunisasi Campak dan Rubella',
            'isi' => 'Imunisasi campak dan rubella untuk balita usia 9 bulan ke atas akan dilaksanakan pada kegiatan posyandu berikutnya.',
            'tanggal_terbit' => now()->subDays(3)->toDateString(),
            'status' => Pengumuman::STATUS_DIPUBLIKASIKAN,
        ]);

        Pengumuman::create([
            'judul' => 'Penyuluhan Gizi Seimbang (draft)',
            'isi' => 'Draf pengumuman penyuluhan gizi seimbang. Belum dipublikasikan.',
            'tanggal_terbit' => null,
            'status' => Pengumuman::STATUS_DRAFT,
        ]);
    }
}
