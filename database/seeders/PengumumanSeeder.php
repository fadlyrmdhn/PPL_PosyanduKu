<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $contoh = [
            [
                'judul' => 'Jadwal Posyandu Bulan November',
                'isi' => "Posyandu akan dilaksanakan pada hari Rabu pukul 08.00 WIB.\nMohon membawa Buku KIA/KMS balita.",
                'status' => Pengumuman::STATUS_DIPUBLIKASIKAN,
                'tanggal_terbit' => now(),
            ],
            [
                'judul' => 'Imunisasi Campak dan Rubella',
                'isi' => 'Imunisasi campak dan rubella untuk balita usia 9 bulan ke atas akan dilaksanakan pada kegiatan posyandu berikutnya.',
                'status' => Pengumuman::STATUS_DIPUBLIKASIKAN,
                'tanggal_terbit' => now()->subDays(3),
            ],
            [
                'judul' => 'Penyuluhan Gizi Seimbang (draft)',
                'isi' => 'Draf pengumuman penyuluhan gizi seimbang. Belum dipublikasikan.',
                'status' => Pengumuman::STATUS_DRAFT,
                'tanggal_terbit' => null,
            ],
        ];

        foreach ($contoh as $data) {
            Pengumuman::firstOrCreate(['judul' => $data['judul']], $data + ['id_user' => $admin?->getKey()]);
        }
    }
}
