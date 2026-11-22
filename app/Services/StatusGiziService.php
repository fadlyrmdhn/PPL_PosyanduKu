<?php

namespace App\Services;

use App\Models\Pengukuran;
use App\Models\StatusGizi;
use App\Models\StandarAntropometri;

/**
 * Service untuk menghitung status gizi balita
 * berdasarkan standar antropometri WHO 2006.
 *
 * Rumus z-score (LMS diimplementasikan tanpa koefisien L):
 *   jika nilai >= median : (nilai - median) / (plus_1sd - median)
 *   jika nilai <  median : (nilai - median) / (median - min_1sd)
 *
 * Kategori (BB/U, TB/U, BB/TB) berdasarkan WHO 2006:
 *   z-score >  3   : Sangat Lebih / Sangat Tinggi / Obesitas
 *   z-score >  2   : Lebih / Tinggi / Gemuk
 *   z-score >= -2  : Normal
 *   z-score >= -3  : Kurang / Pendek / Kurus
 *   z-score <  -3  : Buruk / Sangat Pendek / Sangat Kurus
 */
class StatusGiziService
{
    /**
     * Hitung dan simpan status gizi berdasarkan pengukuran.
     */
    public function hitungDanSimpan(Pengukuran $pengukuran): StatusGizi
    {
        $balita       = $pengukuran->balita;
        $jenisKelamin = $balita->jenis_kelamin; // 'L' atau 'P'
        $usiaBulan    = $pengukuran->usia_bulan;
        $bb           = (float) $pengukuran->berat_badan;
        $tb           = (float) $pengukuran->tinggi_badan;

        // --- BB/U ---
        $stBbu = StandarAntropometri::cari('bb_u', $jenisKelamin, $usiaBulan);
        [$zBbu, $katBbu] = $stBbu
            ? $this->hitungZscore($bb, $stBbu, 'bb_u')
            : [null, null];

        // --- TB/U ---
        $stTbu = StandarAntropometri::cari('tb_u', $jenisKelamin, $usiaBulan);
        [$zTbu, $katTbu] = $stTbu
            ? $this->hitungZscore($tb, $stTbu, 'tb_u')
            : [null, null];

        // --- BB/TB ---
        $stBbtb = StandarAntropometri::cari('bb_tb', $jenisKelamin, $tb);
        [$zBbtb, $katBbtb] = $stBbtb
            ? $this->hitungZscore($bb, $stBbtb, 'bb_tb')
            : [null, null];

        return StatusGizi::updateOrCreate(
            ['id_pengukuran' => $pengukuran->id],
            [
                'zscore_bb_u'   => $zBbu,
                'kategori_bb_u' => $katBbu,
                'zscore_tb_u'   => $zTbu,
                'kategori_tb_u' => $katTbu,
                'zscore_bb_tb'  => $zBbtb,
                'kategori_bb_tb' => $katBbtb,
            ]
        );
    }

    /**
     * Hitung z-score dan tentukan kategori.
     *
     * @return array{0: float, 1: string}
     */
    private function hitungZscore(float $nilai, StandarAntropometri $std, string $indikator): array
    {
        $median  = (float) $std->median;
        $plus1sd = (float) $std->plus_1sd;
        $min1sd  = (float) $std->min_1sd;

        if ($nilai >= $median) {
            $zscore = ($plus1sd - $median) != 0
                ? ($nilai - $median) / ($plus1sd - $median)
                : 0.0;
        } else {
            $zscore = ($median - $min1sd) != 0
                ? ($nilai - $median) / ($median - $min1sd)
                : 0.0;
        }

        $zscore    = round($zscore, 3);
        $kategori  = $this->tentukanKategori($zscore, $indikator);

        return [$zscore, $kategori];
    }

    /**
     * Tentukan kategori status gizi berdasarkan z-score dan indikator.
     * Ambang batas sesuai WHO 2006.
     */
    private function tentukanKategori(float $zscore, string $indikator): string
    {
        return match ($indikator) {
            'bb_u'  => $this->kategoriPerBbu($zscore),
            'tb_u'  => $this->kategoriPerTbu($zscore),
            'bb_tb' => $this->kategoriPerBbtb($zscore),
            default => 'Tidak Diketahui',
        };
    }

    /** Kategori BB/U (Berat Badan per Umur) */
    private function kategoriPerBbu(float $z): string
    {
        if ($z > 3)  return 'Lebih';
        if ($z > 2)  return 'Lebih';
        if ($z >= -2) return 'Normal';
        if ($z >= -3) return 'Kurang';
        return 'Buruk';
    }

    /** Kategori TB/U (Tinggi Badan per Umur) */
    private function kategoriPerTbu(float $z): string
    {
        if ($z > 3)  return 'Sangat Tinggi';
        if ($z > 2)  return 'Tinggi';
        if ($z >= -2) return 'Normal';
        if ($z >= -3) return 'Pendek';
        return 'Sangat Pendek';
    }

    /** Kategori BB/TB (Berat Badan per Tinggi Badan) */
    private function kategoriPerBbtb(float $z): string
    {
        if ($z > 3)  return 'Obesitas';
        if ($z > 2)  return 'Gemuk';
        if ($z >= -2) return 'Normal';
        if ($z >= -3) return 'Kurus';
        return 'Sangat Kurus';
    }
}
