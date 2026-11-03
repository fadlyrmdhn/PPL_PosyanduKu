<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Catatan: test ini mengasumsikan tabel users punya kolom `role`
 * dengan nilai 'admin', 'kader', atau 'orang_tua' (dari PBI-1/autentikasi).
 */
class PengumumanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Test tidak bergantung pada hasil `npm run build`.
        $this->withoutVite();
    }

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function dataValid(array $override = []): array
    {
        return array_merge([
            'judul' => 'Jadwal Posyandu',
            'isi' => 'Posyandu dilaksanakan hari Rabu pukul 08.00.',
            'tanggal_terbit' => '2026-11-04',
            'status' => 'dipublikasikan',
        ], $override);
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('pengumuman.index'))->assertRedirect(route('login'));
    }

    public function test_tanggal_terbit_ditampilkan_dalam_bahasa_indonesia(): void
    {
        Pengumuman::factory()->create(['judul' => 'Tes Tanggal', 'tanggal_terbit' => '2026-11-04']);

        $this->actingAs($this->user('orang_tua'))
            ->get(route('pengumuman.index'))
            ->assertSee('04 November 2026');
    }

    public function test_judul_dan_isi_di_halaman_dilindungi_dari_xss(): void
    {
        $pengumuman = Pengumuman::factory()->create([
            'judul' => '<script>alert(1)</script>',
            'isi' => '<img src=x onerror=alert(1)>',
        ]);

        $this->actingAs($this->user('orang_tua'))
            ->get(route('pengumuman.show', $pengumuman))
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false);
    }

    public function test_admin_melihat_semua_pengumuman_termasuk_draft(): void
    {
        Pengumuman::factory()->create(['judul' => 'Sudah Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Masih Draft']);

        $this->actingAs($this->user('admin'))
            ->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Sudah Terbit')
            ->assertSee('Masih Draft')
            ->assertSee('Tambah Pengumuman');
    }

    public function test_orang_tua_hanya_melihat_pengumuman_yang_dipublikasikan(): void
    {
        Pengumuman::factory()->create(['judul' => 'Sudah Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Masih Draft']);

        $this->actingAs($this->user('orang_tua'))
            ->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Sudah Terbit')
            ->assertDontSee('Masih Draft')
            ->assertDontSee('Tambah Pengumuman');
    }

    public function test_kader_hanya_bisa_membaca_pengumuman_yang_dipublikasikan(): void
    {
        Pengumuman::factory()->create(['judul' => 'Sudah Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Masih Draft']);

        $this->actingAs($this->user('kader'))
            ->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Sudah Terbit')
            ->assertDontSee('Masih Draft');
    }

    public function test_orang_tua_dapat_membaca_detail_pengumuman_terbit_tetapi_tidak_draft(): void
    {
        $terbit = Pengumuman::factory()->create();
        $draft = Pengumuman::factory()->draft()->create();
        $ortu = $this->user('orang_tua');

        $this->actingAs($ortu)->get(route('pengumuman.show', $terbit))->assertOk();
        $this->actingAs($ortu)->get(route('pengumuman.show', $draft))->assertForbidden();
    }

    public function test_orang_tua_dan_kader_tidak_bisa_mengelola_pengumuman(): void
    {
        $pengumuman = Pengumuman::factory()->create();

        foreach (['orang_tua', 'kader'] as $role) {
            $user = $this->user($role);

            $this->actingAs($user)->get(route('pengumuman.create'))->assertForbidden();
            $this->actingAs($user)->post(route('pengumuman.store'), $this->dataValid())->assertForbidden();
            $this->actingAs($user)->get(route('pengumuman.edit', $pengumuman))->assertForbidden();
            $this->actingAs($user)->put(route('pengumuman.update', $pengumuman), $this->dataValid())->assertForbidden();
            $this->actingAs($user)->delete(route('pengumuman.destroy', $pengumuman))->assertForbidden();
        }

        $this->assertDatabaseCount('pengumuman', 1);
    }

    public function test_admin_dapat_menambah_pengumuman(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->post(route('pengumuman.store'), $this->dataValid())
            ->assertRedirect(route('pengumuman.index'));

        $this->assertDatabaseHas('pengumuman', [
            'judul' => 'Jadwal Posyandu',
            'status' => 'dipublikasikan',
            'dibuat_oleh' => $admin->id,
        ]);
    }

    public function test_tanggal_terbit_terisi_otomatis_saat_dipublikasikan_tanpa_tanggal(): void
    {
        $this->actingAs($this->user('admin'))
            ->post(route('pengumuman.store'), $this->dataValid(['tanggal_terbit' => null]));

        $pengumuman = Pengumuman::firstOrFail();
        $this->assertSame(now()->toDateString(), $pengumuman->tanggal_terbit->toDateString());
    }

    public function test_validasi_menolak_data_tidak_lengkap_atau_tidak_valid(): void
    {
        $this->actingAs($this->user('admin'))
            ->post(route('pengumuman.store'), [
                'judul' => '',
                'isi' => '',
                'status' => 'rahasia',
                'tanggal_terbit' => 'bukan-tanggal',
            ])
            ->assertSessionHasErrors(['judul', 'isi', 'status', 'tanggal_terbit']);

        $this->assertDatabaseCount('pengumuman', 0);
    }

    public function test_admin_dapat_mengubah_pengumuman(): void
    {
        $pengumuman = Pengumuman::factory()->draft()->create();

        $this->actingAs($this->user('admin'))
            ->put(route('pengumuman.update', $pengumuman), $this->dataValid(['judul' => 'Judul Baru']))
            ->assertRedirect(route('pengumuman.index'));

        $this->assertDatabaseHas('pengumuman', [
            'id' => $pengumuman->id,
            'judul' => 'Judul Baru',
            'status' => 'dipublikasikan',
        ]);
    }

    public function test_admin_dapat_menghapus_pengumuman(): void
    {
        $pengumuman = Pengumuman::factory()->create();

        $this->actingAs($this->user('admin'))
            ->delete(route('pengumuman.destroy', $pengumuman))
            ->assertRedirect(route('pengumuman.index'));

        $this->assertDatabaseMissing('pengumuman', ['id' => $pengumuman->id]);
    }

    public function test_pencarian_menyaring_berdasarkan_judul(): void
    {
        Pengumuman::factory()->create(['judul' => 'Imunisasi Campak']);
        Pengumuman::factory()->create(['judul' => 'Penyuluhan Gizi']);

        $this->actingAs($this->user('orang_tua'))
            ->get(route('pengumuman.index', ['cari' => 'Campak']))
            ->assertOk()
            ->assertSee('Imunisasi Campak')
            ->assertDontSee('Penyuluhan Gizi');
    }

    public function test_filter_status_hanya_berlaku_untuk_admin(): void
    {
        Pengumuman::factory()->create(['judul' => 'Sudah Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Masih Draft']);

        $this->actingAs($this->user('admin'))
            ->get(route('pengumuman.index', ['status' => 'draft']))
            ->assertSee('Masih Draft')
            ->assertDontSee('Sudah Terbit');

        // Orang Tua tidak bisa "membuka" draft lewat parameter status.
        $this->actingAs($this->user('orang_tua'))
            ->get(route('pengumuman.index', ['status' => 'draft']))
            ->assertDontSee('Masih Draft');
    }
}
