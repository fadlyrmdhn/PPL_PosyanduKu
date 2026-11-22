<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\StandarAntropometri;
use App\Models\StatusGizi;
use App\Models\UnitPosyandu;
use App\Models\User;
use App\Services\StatusGiziService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests untuk PBI-13 Pengukuran Pertumbuhan & Status Gizi (FR-15).
 *
 * Kriteria penerimaan:
 * - Usia dalam bulan dihitung benar
 * - Hasil z-score dan kategori untuk minimal 5 data uji sama dengan perhitungan manual
 * - Mengubah data pengukuran ikut memperbarui status gizi
 * - Input BB/TB di luar rentang wajar (BB ≤ 0) ditolak
 */
class PengukuranTest extends TestCase
{
    use RefreshDatabase;

    private User $kader;
    private Balita $balita;
    private StatusGiziService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kader = User::factory()->create(['role' => 'kader']);

        // Buat unit posyandu terlebih dahulu karena id_unit wajib ada di tabel balita
        $unit = UnitPosyandu::create([
            'nama_pos' => 'Posyandu Test',
            'rw_dusun' => 'RW 01',
            'alamat'   => 'Jl. Test No. 1',
        ]);

        $this->balita = Balita::create([
            'id_unit'       => $unit->id,
            'nama'          => 'Budi Santoso',
            'nik'           => '3273010101240001',  // 16 karakter, unik
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => Carbon::parse('2024-01-01'),
        ]);

        $this->service = new StatusGiziService();

        $this->seedStandarAntropometri();
    }

    /**
     * Seed data standar minimal untuk pengujian.
     * Nilai diambil dari WHO 2006 Tabel BB/U, TB/U, BB/TB laki-laki.
     */
    private function seedStandarAntropometri(): void
    {
        // BB/U Laki-laki — usia 12 bulan
        StandarAntropometri::create([
            'indikator' => 'bb_u', 'jenis_kelamin' => 'L', 'acuan' => 12,
            'median' => 9.476, 'min_1sd' => 8.381, 'min_2sd' => 7.345, 'min_3sd' => 6.364,
            'plus_1sd' => 10.820, 'plus_2sd' => 12.235, 'plus_3sd' => 13.882,
        ]);

        // BB/U Laki-laki — usia 6 bulan
        StandarAntropometri::create([
            'indikator' => 'bb_u', 'jenis_kelamin' => 'L', 'acuan' => 6,
            'median' => 7.934, 'min_1sd' => 6.980, 'min_2sd' => 6.077, 'min_3sd' => 5.233,
            'plus_1sd' => 8.983, 'plus_2sd' => 10.154, 'plus_3sd' => 11.489,
        ]);

        // TB/U Laki-laki — usia 12 bulan
        StandarAntropometri::create([
            'indikator' => 'tb_u', 'jenis_kelamin' => 'L', 'acuan' => 12,
            'median' => 75.7, 'min_1sd' => 73.0, 'min_2sd' => 70.2, 'min_3sd' => 67.5,
            'plus_1sd' => 78.5, 'plus_2sd' => 81.4, 'plus_3sd' => 84.4,
        ]);

        // TB/U Laki-laki — usia 6 bulan
        StandarAntropometri::create([
            'indikator' => 'tb_u', 'jenis_kelamin' => 'L', 'acuan' => 6,
            'median' => 67.6, 'min_1sd' => 64.9, 'min_2sd' => 62.3, 'min_3sd' => 59.7,
            'plus_1sd' => 70.3, 'plus_2sd' => 73.0, 'plus_3sd' => 75.8,
        ]);

        // BB/TB Laki-laki — tinggi ~75 cm
        StandarAntropometri::create([
            'indikator' => 'bb_tb', 'jenis_kelamin' => 'L', 'acuan' => 75.0,
            'median' => 9.106, 'min_1sd' => 8.182, 'min_2sd' => 7.291, 'min_3sd' => 6.459,
            'plus_1sd' => 10.139, 'plus_2sd' => 11.325, 'plus_3sd' => 12.669,
        ]);

        // BB/TB Laki-laki — tinggi ~67 cm
        StandarAntropometri::create([
            'indikator' => 'bb_tb', 'jenis_kelamin' => 'L', 'acuan' => 65.0,
            'median' => 6.930, 'min_1sd' => 6.146, 'min_2sd' => 5.395, 'min_3sd' => 4.699,
            'plus_1sd' => 7.804, 'plus_2sd' => 8.800, 'plus_3sd' => 9.939,
        ]);
    }

    // =========================================================
    // Usia Bulan Dihitung Benar
    // =========================================================

    /** @test */
    public function usia_bulan_dihitung_benar_saat_simpan(): void
    {
        // Balita lahir 1 Jan 2024, diukur 1 Jan 2025 → 12 bulan
        $response = $this->actingAs($this->kader)
            ->post(route('pengukuran.store'), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => '2025-01-01',
                'berat_badan'  => 9.5,
                'tinggi_badan' => 75.0,
                'cara_ukur'    => 'berdiri',
            ]);

        $p = Pengukuran::latest()->first();
        $this->assertEquals(12, $p->usia_bulan);
    }

    /** @test */
    public function usia_bulan_dihitung_benar_6_bulan(): void
    {
        $response = $this->actingAs($this->kader)
            ->post(route('pengukuran.store'), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => '2024-07-01', // 6 bulan dari 1 Jan 2024
                'berat_badan'  => 7.9,
                'tinggi_badan' => 67.5,
                'cara_ukur'    => 'terlentang',
            ]);

        $p = Pengukuran::latest()->first();
        $this->assertEquals(6, $p->usia_bulan);
    }

    // =========================================================
    // Z-Score & Kategori (minimal 5 data uji)
    // =========================================================

    /**
     * @test
     * Data uji 1: BB=9.5 kg (≥median 9.476), usia 12 bln, L
     * z BB/U = (9.5 - 9.476) / (10.820 - 9.476) = 0.024 / 1.344 ≈ 0.018 → Normal
     */
    public function data_uji_1_zscore_bb_u_normal(): void
    {
        $p = $this->buatPengukuran('2025-01-01', 9.5, 75.0);
        $sg = StatusGizi::where('id_pengukuran', $p->id)->first();

        $this->assertNotNull($sg);
        $this->assertEquals('Normal', $sg->kategori_bb_u);
        // z-score ≈ 0.018
        $this->assertEqualsWithDelta(0.018, (float) $sg->zscore_bb_u, 0.01);
    }

    /**
     * @test
     * Data uji 2: BB=7.0 kg (<median 9.476), usia 12 bln, L
     * z BB/U = (7.0 - 9.476) / (9.476 - 8.381) = -2.476 / 1.095 ≈ -2.261 → Kurang
     */
    public function data_uji_2_zscore_bb_u_kurang(): void
    {
        $p = $this->buatPengukuran('2025-01-01', 7.0, 75.0);
        $sg = StatusGizi::where('id_pengukuran', $p->id)->first();

        $this->assertNotNull($sg);
        $this->assertEquals('Kurang', $sg->kategori_bb_u);
        $this->assertEqualsWithDelta(-2.261, (float) $sg->zscore_bb_u, 0.05);
    }

    /**
     * @test
     * Data uji 3: BB=5.5 kg (<median 9.476), usia 12 bln, L
     * z BB/U = (5.5 - 9.476) / (9.476 - 8.381) = -3.976 / 1.095 ≈ -3.630 → Buruk
     */
    public function data_uji_3_zscore_bb_u_buruk(): void
    {
        $p = $this->buatPengukuran('2025-01-01', 5.5, 75.0);
        $sg = StatusGizi::where('id_pengukuran', $p->id)->first();

        $this->assertNotNull($sg);
        $this->assertEquals('Buruk', $sg->kategori_bb_u);
        $this->assertLessThan(-3.0, (float) $sg->zscore_bb_u);
    }

    /**
     * @test
     * Data uji 4: TB=70.0 cm (<median 75.7), usia 12 bln, L
     * z TB/U = (70.0 - 75.7) / (75.7 - 73.0) = -5.7 / 2.7 ≈ -2.111 → Pendek
     */
    public function data_uji_4_zscore_tb_u_pendek(): void
    {
        $p = $this->buatPengukuran('2025-01-01', 9.5, 70.0);
        $sg = StatusGizi::where('id_pengukuran', $p->id)->first();

        $this->assertNotNull($sg);
        $this->assertEquals('Pendek', $sg->kategori_tb_u);
        $this->assertEqualsWithDelta(-2.111, (float) $sg->zscore_tb_u, 0.05);
    }

    /**
     * @test
     * Data uji 5: BB=10.5 kg (>median 9.106), TB=75 cm, usia 12 bln, L
     * z BB/TB = (10.5 - 9.106) / (10.139 - 9.106) = 1.394 / 1.033 ≈ 1.350 → Normal
     */
    public function data_uji_5_zscore_bb_tb_normal(): void
    {
        $p = $this->buatPengukuran('2025-01-01', 10.5, 75.0);
        $sg = StatusGizi::where('id_pengukuran', $p->id)->first();

        $this->assertNotNull($sg);
        $this->assertEquals('Normal', $sg->kategori_bb_tb);
        $this->assertEqualsWithDelta(1.350, (float) $sg->zscore_bb_tb, 0.05);
    }

    // =========================================================
    // Update Pengukuran → Status Gizi Ikut Diperbarui
    // =========================================================

    /** @test */
    public function update_pengukuran_memperbarui_status_gizi(): void
    {
        // Simpan pengukuran dengan BB normal
        $p = $this->buatPengukuran('2025-01-01', 9.5, 75.0);
        $sgAwal = StatusGizi::where('id_pengukuran', $p->id)->first();
        $this->assertEquals('Normal', $sgAwal->kategori_bb_u);

        // Update BB menjadi sangat kurang → seharusnya Buruk
        $this->actingAs($this->kader)
            ->put(route('pengukuran.update', $p), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => '2025-01-01',
                'berat_badan'  => 5.5, // jauh di bawah normal
                'tinggi_badan' => 75.0,
                'cara_ukur'    => 'berdiri',
            ]);

        $sgUpdate = StatusGizi::where('id_pengukuran', $p->id)->first();
        $this->assertEquals('Buruk', $sgUpdate->kategori_bb_u);
    }

    // =========================================================
    // Validasi Input
    // =========================================================

    /** @test */
    public function bb_nol_atau_negatif_ditolak(): void
    {
        $response = $this->actingAs($this->kader)
            ->post(route('pengukuran.store'), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => '2025-01-01',
                'berat_badan'  => 0,   // tidak valid: ≤ 0
                'tinggi_badan' => 75.0,
                'cara_ukur'    => 'berdiri',
            ]);

        $response->assertSessionHasErrors('berat_badan');
    }

    /** @test */
    public function tb_nol_ditolak(): void
    {
        $response = $this->actingAs($this->kader)
            ->post(route('pengukuran.store'), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => '2025-01-01',
                'berat_badan'  => 9.5,
                'tinggi_badan' => 0,   // tidak valid
                'cara_ukur'    => 'berdiri',
            ]);

        $response->assertSessionHasErrors('tinggi_badan');
    }

    /** @test */
    public function tanggal_masa_depan_ditolak(): void
    {
        $response = $this->actingAs($this->kader)
            ->post(route('pengukuran.store'), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => now()->addDay()->format('Y-m-d'),
                'berat_badan'  => 9.5,
                'tinggi_badan' => 75.0,
                'cara_ukur'    => 'berdiri',
            ]);

        $response->assertSessionHasErrors('tanggal_ukur');
    }

    /** @test */
    public function tanggal_sebelum_lahir_ditolak(): void
    {
        $response = $this->actingAs($this->kader)
            ->post(route('pengukuran.store'), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => '2023-12-31', // sebelum lahir 2024-01-01
                'berat_badan'  => 9.5,
                'tinggi_badan' => 75.0,
                'cara_ukur'    => 'berdiri',
            ]);

        $response->assertSessionHasErrors('tanggal_ukur');
    }

    /** @test */
    public function hapus_pengukuran_juga_hapus_status_gizi(): void
    {
        $p  = $this->buatPengukuran('2025-01-01', 9.5, 75.0);
        $pid = $p->id;

        $this->actingAs($this->kader)->delete(route('pengukuran.destroy', $p));

        $this->assertDatabaseMissing('pengukuran', ['id' => $pid]);
        $this->assertDatabaseMissing('status_gizi', ['id_pengukuran' => $pid]);
    }

    // =========================================================
    // Helper
    // =========================================================

    private function buatPengukuran(string $tanggal, float $bb, float $tb): Pengukuran
    {
        $response = $this->actingAs($this->kader)
            ->post(route('pengukuran.store'), [
                'id_balita'    => $this->balita->id,
                'tanggal_ukur' => $tanggal,
                'berat_badan'  => $bb,
                'tinggi_badan' => $tb,
                'cara_ukur'    => 'berdiri',
            ]);

        return Pengukuran::latest()->first();
    }
}
