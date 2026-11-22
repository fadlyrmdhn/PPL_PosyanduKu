<?php

namespace Tests\Feature;

use App\Models\MenuMpasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests untuk PBI-15 Menu MPASI (FR-17).
 * Kriteria penerimaan:
 * - Filter rentang usia menampilkan menu yang sesuai
 * - Rentang usia tidak valid ditolak
 */
class MenuMpasiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $kader;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin
        $this->admin = User::factory()->create(['role' => 'admin']);

        // Kader
        $this->kader = User::factory()->create(['role' => 'kader']);

        // Data awal
        MenuMpasi::create([
            'nama_menu'      => 'Bubur Ayam 6-8 Bulan',
            'usia_min_bulan' => 6,
            'usia_max_bulan' => 8,
            'bahan'          => 'Beras, ayam, sayuran',
            'cara_pembuatan' => 'Masak beras menjadi bubur, tambahkan ayam suwir',
        ]);

        MenuMpasi::create([
            'nama_menu'      => 'Nasi Tim 9-11 Bulan',
            'usia_min_bulan' => 9,
            'usia_max_bulan' => 11,
            'bahan'          => 'Beras, daging, wortel',
            'cara_pembuatan' => 'Kukus beras, daging, dan wortel bersama',
        ]);

        MenuMpasi::create([
            'nama_menu'      => 'Nasi Biasa 12-23 Bulan',
            'usia_min_bulan' => 12,
            'usia_max_bulan' => 23,
            'bahan'          => 'Beras, lauk pauk',
            'cara_pembuatan' => 'Masak nasi dan lauk biasa',
        ]);
    }

    // =========================================================
    // F-17.2 Filter Rentang Usia
    // =========================================================

    /** @test */
    public function filter_rentang_6_8_menampilkan_menu_yang_sesuai(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('menu-mpasi.index', ['rentang' => '6-8']));

        $response->assertOk();
        $response->assertSee('Bubur Ayam 6-8 Bulan');
        $response->assertDontSee('Nasi Tim 9-11 Bulan');
        $response->assertDontSee('Nasi Biasa 12-23 Bulan');
    }

    /** @test */
    public function filter_rentang_9_11_menampilkan_menu_yang_sesuai(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('menu-mpasi.index', ['rentang' => '9-11']));

        $response->assertOk();
        $response->assertSee('Nasi Tim 9-11 Bulan');
        $response->assertDontSee('Bubur Ayam 6-8 Bulan');
        $response->assertDontSee('Nasi Biasa 12-23 Bulan');
    }

    /** @test */
    public function filter_rentang_12_23_menampilkan_menu_yang_sesuai(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('menu-mpasi.index', ['rentang' => '12-23']));

        $response->assertOk();
        $response->assertSee('Nasi Biasa 12-23 Bulan');
        $response->assertDontSee('Bubur Ayam 6-8 Bulan');
    }

    /** @test */
    public function tanpa_filter_menampilkan_semua_menu(): void
    {
        $response = $this->actingAs($this->admin)->get(route('menu-mpasi.index'));

        $response->assertOk();
        $response->assertSee('Bubur Ayam 6-8 Bulan');
        $response->assertSee('Nasi Tim 9-11 Bulan');
        $response->assertSee('Nasi Biasa 12-23 Bulan');
    }

    // =========================================================
    // F-17.1 Validasi
    // =========================================================

    /** @test */
    public function rentang_usia_tidak_valid_ditolak_jika_max_lebih_kecil_dari_min(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('menu-mpasi.store'), [
                'nama_menu'      => 'Test Menu',
                'usia_min_bulan' => 10,
                'usia_max_bulan' => 5, // tidak valid: max < min
                'bahan'          => 'Bahan test',
                'cara_pembuatan' => 'Cara test',
            ]);

        $response->assertSessionHasErrors('usia_max_bulan');
    }

    /** @test */
    public function admin_dapat_tambah_menu_mpasi(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('menu-mpasi.store'), [
                'nama_menu'      => 'Puree Pisang',
                'usia_min_bulan' => 6,
                'usia_max_bulan' => 8,
                'bahan'          => 'Pisang matang',
                'cara_pembuatan' => 'Haluskan pisang',
                'kandungan_gizi' => 'Energi 90 kkal',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('menu_mpasi', ['nama_menu' => 'Puree Pisang']);
    }

    /** @test */
    public function admin_dapat_menghapus_menu_mpasi(): void
    {
        $menu = MenuMpasi::first();
        $response = $this->actingAs($this->admin)
            ->delete(route('menu-mpasi.destroy', $menu));

        $response->assertRedirect(route('menu-mpasi.index'));
        $this->assertDatabaseMissing('menu_mpasi', ['id' => $menu->id]);
    }

    /** @test */
    public function nama_menu_wajib_diisi(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('menu-mpasi.store'), [
                'nama_menu'      => '',
                'usia_min_bulan' => 6,
                'usia_max_bulan' => 8,
                'bahan'          => 'Bahan',
                'cara_pembuatan' => 'Cara',
            ]);

        $response->assertSessionHasErrors('nama_menu');
    }

    /** @test */
    public function kader_tidak_bisa_akses_menu_mpasi(): void
    {
        $response = $this->actingAs($this->kader)
            ->get(route('menu-mpasi.index'));

        $response->assertForbidden();
    }
}
