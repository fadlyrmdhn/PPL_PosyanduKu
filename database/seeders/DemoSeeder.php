<?php

namespace Database\Seeders;

use App\Models\AbsensiKader;
use App\Models\Balita;
use App\Models\Imunisasi;
use App\Models\JadwalImunisasi;
use App\Models\JadwalKegiatan;
use App\Models\Kader;
use App\Models\MenuMpasi;
use App\Models\PemberianVitamin;
use App\Models\Pengukuran;
use App\Models\UnitPosyandu;
use App\Models\User;
use App\Models\Vaksin;
use App\Services\StatusGiziService;
use Illuminate\Database\Seeder;

/**
 * Data contoh untuk demo: kader, jadwal kegiatan, absensi, pengukuran + status gizi,
 * imunisasi, vitamin, jadwal imunisasi, dan menu MPASI.
 *
 * Jalankan SETELAH DatabaseSeeder (butuh akun, unit, balita, vaksin, dan standar antropometri):
 *   php artisan db:seed --class=DemoSeeder
 *
 * Aman dijalankan ulang: kalau profil kader sudah ada, seeder berhenti tanpa menambah data.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $userKader = User::where('username', 'kader')->first();
        $melati = UnitPosyandu::where('nama_pos', 'Posyandu Melati')->first();
        $mawar = UnitPosyandu::where('nama_pos', 'Posyandu Mawar')->first();

        if (! $userKader || ! $melati || ! $mawar) {
            $this->command?->warn('Jalankan DatabaseSeeder dulu (akun kader dan unit posyandu belum ada).');

            return;
        }

        if (Kader::where('id_user', $userKader->id)->exists()) {
            $this->command?->info('Data demo sudah ada, dilewati.');

            return;
        }

        $this->kaderDanKegiatan($userKader, $melati, $mawar);
        $this->pengukuran($userKader);
        $this->imunisasiDanVitamin($userKader);
        $this->menuMpasi();
    }

    private function kaderDanKegiatan(User $userKader, UnitPosyandu $melati, UnitPosyandu $mawar): void
    {
        // Profil kader yang tersambung ke akun login "kader" (dipakai menu Kehadiran Saya).
        $kader1 = Kader::create([
            'id_user' => $userKader->id, 'id_unit' => $melati->id, 'nama' => 'Kader Contoh',
            'nik' => '3273015501920001', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1992-01-15',
            'no_hp' => '081311110001', 'alamat' => 'Jl. Melati No. 3', 'status' => 'aktif',
            'tanggal_bergabung' => now()->subYears(2)->toDateString(),
        ]);
        $kader2 = Kader::create([
            'id_unit' => $melati->id, 'nama' => 'Dewi Lestari',
            'nik' => '3273016204900002', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1990-04-22',
            'no_hp' => '081311110002', 'alamat' => 'Jl. Melati No. 8', 'status' => 'aktif',
            'tanggal_bergabung' => now()->subYears(3)->toDateString(),
        ]);
        $kader3 = Kader::create([
            'id_unit' => $mawar->id, 'nama' => 'Sri Wahyuni',
            'nik' => '3273014807850003', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1985-07-08',
            'no_hp' => '081311110003', 'alamat' => 'Jl. Mawar No. 2', 'status' => 'aktif',
            'tanggal_bergabung' => now()->subYears(4)->toDateString(),
        ]);
        Kader::create([
            'id_unit' => $mawar->id, 'nama' => 'Rina Marlina',
            'nik' => '3273015309880004', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1988-09-13',
            'no_hp' => '081311110004', 'alamat' => 'Jl. Mawar No. 11', 'status' => 'nonaktif',
            'tanggal_bergabung' => now()->subYears(5)->toDateString(),
        ]);

        // Jadwal kegiatan. Jadwal "hari ini" sengaja belum diabsen supaya bisa diisi langsung saat demo.
        $jadwal = fn ($unit, $nama, $jenis, $tgl, $mulai, $selesai, $ket = null) => JadwalKegiatan::create([
            'id_unit' => $unit->id, 'nama_kegiatan' => $nama, 'jenis_kegiatan' => $jenis,
            'tanggal' => $tgl->toDateString(), 'waktu_mulai' => $mulai, 'waktu_selesai' => $selesai, 'keterangan' => $ket,
        ]);

        $m1 = $jadwal($melati, 'Penimbangan Bulanan', 'penimbangan', now()->subWeeks(4), '08:00', '11:00', 'Penimbangan dan pengukuran seluruh balita.');
        $m2 = $jadwal($melati, 'Imunisasi Campak dan Rubella', 'imunisasi', now()->subWeeks(2), '08:00', '11:00');
        $m3 = $jadwal($melati, 'Penyuluhan Gizi Seimbang', 'penyuluhan', now()->subWeek(), '09:00', '11:00', 'Materi: MPASI dan gizi seimbang untuk balita.');
        $jadwal($melati, 'Penimbangan dan Imunisasi Hari Ini', 'penimbangan', now(), '08:00', '11:00', 'Kegiatan posyandu bulan ini.');
        $jadwal($melati, 'Penimbangan Bulan Depan', 'penimbangan', now()->addWeeks(4), '08:00', '11:00');

        $w1 = $jadwal($mawar, 'Penimbangan Bulanan', 'penimbangan', now()->subWeeks(3), '08:30', '11:30');
        $w2 = $jadwal($mawar, 'Penyuluhan Kesehatan Balita', 'penyuluhan', now()->subDays(5), '09:00', '11:00');
        $jadwal($mawar, 'Imunisasi Rutin', 'imunisasi', now()->addWeeks(2), '08:30', '11:30');

        $absen = fn ($j, $k, $status, $ket = null) => AbsensiKader::create([
            'id_jadwal' => $j->id, 'id_kader' => $k->id, 'status' => $status,
            'keterangan' => $ket, 'dicatat_pada' => $j->tanggal->copy()->setTime(7, 45),
        ]);

        $absen($m1, $kader1, 'hadir');
        $absen($m2, $kader1, 'hadir');
        $absen($m3, $kader1, 'izin', 'Ada keperluan keluarga.');
        $absen($m1, $kader2, 'hadir');
        $absen($m2, $kader2, 'sakit', 'Demam.');
        $absen($m3, $kader2, 'hadir');
        $absen($w1, $kader3, 'hadir');
        $absen($w2, $kader3, 'alpa');
    }

    /** Nilai kira-kira mengikuti kurva WHO; Naura dibuat sedikit kurang supaya status gizinya bervariasi. */
    private function pengukuran(User $userKader): void
    {
        $service = app(StatusGiziService::class);

        // nama => [[bulan_lalu, BB, TB, lingkar_kepala, lila], ...]
        $data = [
            'Aisyah Putri' => [[2, 4.9, 56.5, 38.0, 12.0], [0, 6.2, 61.5, 40.5, 13.0]],
            'Rafi Pratama' => [[2, 9.3, 75.0, 46.0, 14.5], [0, 9.8, 77.5, 46.8, 15.0]],
            'Naura Salsabila' => [[3, 9.4, 82.0, 47.0, 13.5], [0, 9.2, 83.5, 47.2, 13.5]],
            'Dimas Aditya' => [[0, 14.0, 96.0, 49.5, 15.5]],
            'Keisha Anindya' => [[0, 15.5, 101.0, 50.0, 16.0]],
        ];

        foreach ($data as $nama => $baris) {
            $balita = Balita::where('nama', $nama)->first();
            if (! $balita) {
                continue;
            }

            foreach ($baris as [$bulanLalu, $bb, $tb, $lk, $lila]) {
                $tanggal = now()->subMonths($bulanLalu)->subDays($bulanLalu === 0 ? 2 : 0);
                if ($tanggal->lt($balita->tanggal_lahir)) {
                    $tanggal = $balita->tanggal_lahir->copy();
                }
                $usia = (int) $balita->tanggal_lahir->diffInMonths($tanggal);

                $pengukuran = Pengukuran::create([
                    'id_balita' => $balita->id,
                    'tanggal_ukur' => $tanggal->toDateString(),
                    'usia_bulan' => $usia,
                    'berat_badan' => $bb,
                    'tinggi_badan' => $tb,
                    'cara_ukur' => $usia < 24 ? 'terlentang' : 'berdiri',
                    'lingkar_kepala' => $lk,
                    'lila' => $lila,
                    'id_user' => $userKader->id,
                ]);

                $service->hitungDanSimpan($pengukuran);
            }
        }
    }

    private function imunisasiDanVitamin(User $userKader): void
    {
        $vaksinList = Vaksin::orderBy('usia_pemberian_bulan')->orderBy('id')->get();
        $hariIni = now()->startOfDay();

        foreach (Balita::all() as $balita) {
            $usia = (int) $balita->tanggal_lahir->diffInMonths(now());

            // Vaksin yang sudah jatuh tempo (usia anjuran < usia anak saat ini).
            $jatuhTempo = $vaksinList->filter(fn ($v) => $v->usia_pemberian_bulan < $usia)->values();
            // Satu vaksin terakhir yang jatuh tempo sengaja belum diberikan, jadi muncul status "terlewat".
            $terlewat = $jatuhTempo->count() >= 2 ? $jatuhTempo->last() : null;

            foreach ($vaksinList as $vaksin) {
                $tanggal = $balita->tanggal_lahir->copy()->addMonths($vaksin->usia_pemberian_bulan);
                $diberikan = $vaksin->usia_pemberian_bulan < $usia && (! $terlewat || $vaksin->id !== $terlewat->id);

                if ($diberikan) {
                    Imunisasi::create([
                        'id_balita' => $balita->id,
                        'id_vaksin' => $vaksin->id,
                        'tanggal_pemberian' => $tanggal->toDateString(),
                        'dosis_ke' => $vaksin->dosis_ke,
                        'keterangan' => null,
                        'id_user' => $userKader->id,
                    ]);
                    $status = 'selesai';
                } else {
                    $status = $tanggal->lt($hariIni) ? 'terlewat' : 'terjadwal';
                }

                JadwalImunisasi::create([
                    'id_balita' => $balita->id,
                    'id_vaksin' => $vaksin->id,
                    'tanggal_jadwal' => $tanggal->toDateString(),
                    'status' => $status,
                ]);
            }

            // Vitamin A: kapsul biru 6-11 bulan, kapsul merah 12-59 bulan.
            if ($usia >= 6) {
                PemberianVitamin::create([
                    'id_balita' => $balita->id,
                    'nama_vitamin' => $usia >= 12 ? 'Vitamin A Kapsul Merah' : 'Vitamin A Kapsul Biru',
                    'tanggal_pemberian' => now()->subWeeks(4)->toDateString(),
                    'dosis' => $usia >= 12 ? '200.000 IU' : '100.000 IU',
                    'keterangan' => 'Diberikan saat penimbangan bulanan.',
                    'id_user' => $userKader->id,
                ]);
            }
        }
    }

    private function menuMpasi(): void
    {
        $menu = [
            ['Bubur Beras Pisang', 6, 8, "Beras putih 2 sdm\nPisang ambon 1/2 buah\nAir matang secukupnya",
                "1. Masak beras dengan air hingga menjadi bubur halus.\n2. Haluskan pisang dengan garpu.\n3. Campur pisang ke bubur dan aduk rata.",
                'Energi ±90 kkal, karbohidrat 20 g, protein 1 g'],
            ['Puree Wortel dan Kentang', 6, 8, "Wortel 1 buah kecil\nKentang 1/2 buah\nSedikit minyak zaitun",
                "1. Kukus wortel dan kentang sampai empuk.\n2. Blender dengan sedikit air rebusan hingga halus.\n3. Tambahkan minyak zaitun, aduk.",
                'Energi ±70 kkal, serat 2 g, vitamin A tinggi'],
            ['Bubur Saring Ayam Brokoli', 9, 11, "Beras 3 sdm\nDada ayam 30 g\nBrokoli 2 kuntum\nBawang putih 1 siung",
                "1. Rebus beras dengan air hingga lunak.\n2. Masukkan ayam, brokoli, dan bawang putih, masak sampai matang.\n3. Saring kasar atau cincang halus.",
                'Energi ±120 kkal, protein 7 g, zat besi 1,2 mg'],
            ['Nasi Tim Hati Ayam Tahu', 9, 11, "Beras 3 sdm\nHati ayam 20 g\nTahu 1 potong kecil\nWortel parut 1 sdm",
                "1. Cuci beras, campur dengan air dan semua bahan.\n2. Kukus atau tim hingga empuk.\n3. Cincang halus sebelum disajikan.",
                'Energi ±130 kkal, protein 8 g, zat besi 2 mg'],
            ['Sup Ikan Tomat Nasi Lembut', 12, 23, "Ikan kembung fillet 40 g\nTomat 1/2 buah\nNasi lembut 4 sdm\nSeledri secukupnya",
                "1. Rebus ikan hingga matang, suwir.\n2. Masak tomat dengan air kaldu, masukkan nasi dan ikan.\n3. Beri seledri, masak 5 menit.",
                'Energi ±180 kkal, protein 12 g, omega-3 tinggi'],
            ['Telur Dadar Sayur', 12, 23, "Telur ayam 1 butir\nBayam cincang 1 sdm\nWortel parut 1 sdm\nMinyak 1 sdt",
                "1. Kocok telur, campur dengan sayuran.\n2. Dadar dengan api kecil hingga matang.\n3. Potong kecil-kecil.",
                'Energi ±110 kkal, protein 7 g, lemak 8 g'],
        ];

        foreach ($menu as [$nama, $min, $max, $bahan, $cara, $gizi]) {
            MenuMpasi::create([
                'nama_menu' => $nama, 'usia_min_bulan' => $min, 'usia_max_bulan' => $max,
                'bahan' => $bahan, 'cara_pembuatan' => $cara, 'kandungan_gizi' => $gizi,
            ]);
        }
    }
}