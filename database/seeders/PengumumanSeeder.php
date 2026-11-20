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
            ['Jadwal Posyandu Bulan Ini', "Posyandu dilaksanakan pada hari Rabu pukul 08.00 WIB.\nMohon membawa Buku KIA/KMS balita.", Pengumuman::STATUS_DIPUBLIKASIKAN, Pengumuman::sekarang()],
            ['Imunisasi Campak dan Rubella', 'Imunisasi campak dan rubella untuk balita usia 9 bulan ke atas dilaksanakan pada kegiatan posyandu berikutnya.', Pengumuman::STATUS_DIPUBLIKASIKAN, Pengumuman::sekarang()->subDays(3)],
            ['Penyuluhan Gizi Seimbang', 'Draf pengumuman penyuluhan gizi seimbang. Belum dipublikasikan.', Pengumuman::STATUS_DRAFT, null],
        ];

        foreach ($contoh as [$judul, $isi, $status, $tanggal]) {
            Pengumuman::create([
                'id_user' => $admin?->id,
                'judul' => $judul,
                'isi' => $isi,
                'status' => $status,
                'tanggal_terbit' => $tanggal,
            ]);
        }
    }
}
