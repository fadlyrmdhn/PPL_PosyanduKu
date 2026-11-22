<?php

namespace Database\Seeders;

use App\Models\Vaksin;
use Illuminate\Database\Seeder;

class VaksinSeeder extends Seeder
{
    /**
     * Vaksin imunisasi dasar beserta usia pemberian (dalam bulan) sesuai program nasional.
     */
    public function run(): void
    {
        $vaksin = [
            // Hepatitis B
            ['nama_vaksin' => 'Hepatitis B 1',    'usia_pemberian_bulan' => 0,  'dosis_ke' => 1, 'jumlah_dosis' => '0,5 ml', 'keterangan' => 'Diberikan dalam 24 jam setelah lahir'],
            ['nama_vaksin' => 'Hepatitis B 2',    'usia_pemberian_bulan' => 2,  'dosis_ke' => 2, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'Hepatitis B 3',    'usia_pemberian_bulan' => 3,  'dosis_ke' => 3, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'Hepatitis B 4',    'usia_pemberian_bulan' => 4,  'dosis_ke' => 4, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],

            // Polio
            ['nama_vaksin' => 'Polio OPV 1',      'usia_pemberian_bulan' => 1,  'dosis_ke' => 1, 'jumlah_dosis' => '2 tetes', 'keterangan' => null],
            ['nama_vaksin' => 'Polio OPV 2',      'usia_pemberian_bulan' => 2,  'dosis_ke' => 2, 'jumlah_dosis' => '2 tetes', 'keterangan' => null],
            ['nama_vaksin' => 'Polio OPV 3',      'usia_pemberian_bulan' => 3,  'dosis_ke' => 3, 'jumlah_dosis' => '2 tetes', 'keterangan' => null],
            ['nama_vaksin' => 'Polio OPV 4',      'usia_pemberian_bulan' => 4,  'dosis_ke' => 4, 'jumlah_dosis' => '2 tetes', 'keterangan' => null],
            ['nama_vaksin' => 'Polio IPV 1',      'usia_pemberian_bulan' => 4,  'dosis_ke' => 1, 'jumlah_dosis' => '0,5 ml',  'keterangan' => 'Inactivated Polio Vaccine (injeksi)'],
            ['nama_vaksin' => 'Polio IPV 2',      'usia_pemberian_bulan' => 9,  'dosis_ke' => 2, 'jumlah_dosis' => '0,5 ml',  'keterangan' => null],

            // BCG
            ['nama_vaksin' => 'BCG',              'usia_pemberian_bulan' => 1,  'dosis_ke' => 1, 'jumlah_dosis' => '0,05 ml', 'keterangan' => 'Diberikan secara intradermal'],

            // DPT-HB-Hib
            ['nama_vaksin' => 'DPT-HB-Hib 1',    'usia_pemberian_bulan' => 2,  'dosis_ke' => 1, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'DPT-HB-Hib 2',    'usia_pemberian_bulan' => 3,  'dosis_ke' => 2, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'DPT-HB-Hib 3',    'usia_pemberian_bulan' => 4,  'dosis_ke' => 3, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'DPT-HB-Hib 4',    'usia_pemberian_bulan' => 18, 'dosis_ke' => 4, 'jumlah_dosis' => '0,5 ml', 'keterangan' => 'Booster'],

            // PCV (Pneumokokus)
            ['nama_vaksin' => 'PCV 1',            'usia_pemberian_bulan' => 2,  'dosis_ke' => 1, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'PCV 2',            'usia_pemberian_bulan' => 3,  'dosis_ke' => 2, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'PCV 3',            'usia_pemberian_bulan' => 12, 'dosis_ke' => 3, 'jumlah_dosis' => '0,5 ml', 'keterangan' => 'Booster'],

            // Rotavirus
            ['nama_vaksin' => 'Rotavirus 1',      'usia_pemberian_bulan' => 2,  'dosis_ke' => 1, 'jumlah_dosis' => '1,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'Rotavirus 2',      'usia_pemberian_bulan' => 3,  'dosis_ke' => 2, 'jumlah_dosis' => '1,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'Rotavirus 3',      'usia_pemberian_bulan' => 4,  'dosis_ke' => 3, 'jumlah_dosis' => '1,5 ml', 'keterangan' => null],

            // MR / Campak-Rubela
            ['nama_vaksin' => 'MR 1',             'usia_pemberian_bulan' => 9,  'dosis_ke' => 1, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
            ['nama_vaksin' => 'MR 2',             'usia_pemberian_bulan' => 18, 'dosis_ke' => 2, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],

            // JE (Japanese Encephalitis)
            ['nama_vaksin' => 'JE 1',             'usia_pemberian_bulan' => 10, 'dosis_ke' => 1, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],

            // Varisela
            ['nama_vaksin' => 'Varisela 1',       'usia_pemberian_bulan' => 12, 'dosis_ke' => 1, 'jumlah_dosis' => '0,5 ml', 'keterangan' => null],
        ];

        foreach ($vaksin as $v) {
            Vaksin::firstOrCreate(['nama_vaksin' => $v['nama_vaksin']], $v);
        }
    }
}
