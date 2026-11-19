<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * PBI-4 Pengumuman. Mengasumsikan tabel users punya kolom `role`
 * ('admin' | 'kader' | 'orang_tua') dan route `login` tersedia.
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
            'tanggal_terbit' => '2026-11-04T08:30',
            'status' => 'dipublikasikan',
        ], $override);
    }

    // ------------------------------------------------------- kesesuaian ERD

    public function test_struktur_tabel_sesuai_erd_proposal(): void
    {
        $this->assertTrue(Schema::hasColumns('pengumuman', [
            'id_pengumuman', 'id_user', 'judul', 'isi', 'status', 'tanggal_terbit',
        ]));
        $this->assertFalse(Schema::hasColumn('pengumuman', 'id'));
        $this->assertFalse(Schema::hasColumn('pengumuman', 'created_at'));
    }

    public function test_zona_waktu_aplikasi_adalah_wib(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
    }

    // -------------------------------------------------------------- akses

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('pengumuman.index'))->assertRedirect(route('login'));
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
        $draft = Pengumuman::factory()->draft()->create();

        foreach (['orang_tua', 'kader'] as $role) {
            $user = $this->user($role);

            $this->actingAs($user)->get(route('pengumuman.create'))->assertForbidden();
            $this->actingAs($user)->post(route('pengumuman.store'), $this->dataValid())->assertForbidden();
            $this->actingAs($user)->get(route('pengumuman.edit', $draft))->assertForbidden();
            $this->actingAs($user)->put(route('pengumuman.update', $draft), $this->dataValid())->assertForbidden();
            $this->actingAs($user)->post(route('pengumuman.publikasikan', $draft))->assertForbidden();
            $this->actingAs($user)->delete(route('pengumuman.destroy', $draft))->assertForbidden();
        }

        $this->assertDatabaseCount('pengumuman', 1);
        $this->assertSame('draft', $draft->fresh()->status);
    }

    // --------------------------------------------------------------- CRUD

    public function test_admin_dapat_menambah_pengumuman_dengan_tanggal_dan_jam(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->post(route('pengumuman.store'), $this->dataValid())
            ->assertRedirect(route('pengumuman.index'));

        $pengumuman = Pengumuman::firstOrFail();
        $this->assertSame('Jadwal Posyandu', $pengumuman->judul);
        $this->assertSame('dipublikasikan', $pengumuman->status);
        $this->assertSame($admin->getKey(), $pengumuman->id_user);
        $this->assertSame('2026-11-04 08:30', $pengumuman->tanggal_terbit->format('Y-m-d H:i'));
    }

    public function test_tanggal_terbit_terisi_otomatis_saat_dipublikasikan_tanpa_tanggal(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 11, 16)->setTime(2, 15));

        $this->actingAs($this->user('admin'))
            ->post(route('pengumuman.store'), $this->dataValid(['tanggal_terbit' => null]));

        // 02.15 WIB tanggal 16 masih tanggal 15 di UTC; harus tercatat sebagai tanggal 16 WIB.
        $this->assertSame('2026-11-16 02:15', Pengumuman::firstOrFail()->tanggal_terbit->format('Y-m-d H:i'));
    }

    public function test_draft_tanpa_tanggal_dibiarkan_kosong(): void
    {
        $this->actingAs($this->user('admin'))
            ->post(route('pengumuman.store'), $this->dataValid(['status' => 'draft', 'tanggal_terbit' => null]));

        $pengumuman = Pengumuman::firstOrFail();
        $this->assertSame('draft', $pengumuman->status);
        $this->assertNull($pengumuman->tanggal_terbit);
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

    public function test_judul_maksimal_150_karakter(): void
    {
        $this->actingAs($this->user('admin'))
            ->post(route('pengumuman.store'), $this->dataValid(['judul' => str_repeat('a', 151)]))
            ->assertSessionHasErrors('judul');
    }

    public function test_admin_dapat_mengubah_pengumuman(): void
    {
        $pengumuman = Pengumuman::factory()->draft()->create();

        $this->actingAs($this->user('admin'))
            ->put(route('pengumuman.update', $pengumuman), $this->dataValid(['judul' => 'Judul Baru']))
            ->assertRedirect(route('pengumuman.index'));

        $pengumuman->refresh();
        $this->assertSame('Judul Baru', $pengumuman->judul);
        $this->assertSame('dipublikasikan', $pengumuman->status);
    }

    public function test_admin_dapat_menghapus_pengumuman(): void
    {
        $pengumuman = Pengumuman::factory()->create();

        $this->actingAs($this->user('admin'))
            ->delete(route('pengumuman.destroy', $pengumuman))
            ->assertRedirect(route('pengumuman.index'));

        $this->assertDatabaseMissing('pengumuman', ['id_pengumuman' => $pengumuman->id_pengumuman]);
    }

    public function test_pengumuman_tetap_ada_saat_akun_pembuat_dihapus(): void
    {
        $admin = $this->user('admin');
        $pengumuman = Pengumuman::factory()->create(['id_user' => $admin->getKey()]);

        $admin->delete();

        $this->assertNull($pengumuman->fresh()->id_user);
    }

    // ------------------------------------------------------ publikasikan()

    public function test_admin_dapat_mempublikasikan_draft(): void
    {
        $draft = Pengumuman::factory()->draft()->create();

        $this->actingAs($this->user('admin'))
            ->post(route('pengumuman.publikasikan', $draft))
            ->assertRedirect();

        $draft->refresh();
        $this->assertSame('dipublikasikan', $draft->status);
        $this->assertNotNull($draft->tanggal_terbit);
    }

    public function test_publikasikan_mempertahankan_tanggal_terbit_yang_sudah_diisi(): void
    {
        $draft = Pengumuman::factory()->draft()->create(['tanggal_terbit' => '2026-12-01 09:00:00']);

        $draft->publikasikan();

        $this->assertSame('2026-12-01 09:00', $draft->fresh()->tanggal_terbit->format('Y-m-d H:i'));
    }

    public function test_draft_menjadi_terlihat_oleh_orang_tua_setelah_dipublikasikan(): void
    {
        $draft = Pengumuman::factory()->draft()->create(['judul' => 'Akan Terbit']);
        $ortu = $this->user('orang_tua');

        $this->actingAs($ortu)->get(route('pengumuman.index'))->assertDontSee('Akan Terbit');

        $draft->publikasikan();

        $this->actingAs($ortu)->get(route('pengumuman.index'))->assertSee('Akan Terbit');
    }

    // ----------------------------------------------- tampilan, cari, urutan

    public function test_tanggal_dan_jam_terbit_ditampilkan_dalam_bahasa_indonesia_wib(): void
    {
        Pengumuman::factory()->create(['judul' => 'Tes Tanggal', 'tanggal_terbit' => '2026-11-04 08:30:00']);

        $this->actingAs($this->user('orang_tua'))
            ->get(route('pengumuman.index'))
            ->assertSee('04 November 2026, 08.30 WIB');
    }

    public function test_urutan_terbaru_di_atas_dan_draft_paling_atas_bagi_admin(): void
    {
        Pengumuman::factory()->create(['judul' => 'Lama', 'tanggal_terbit' => '2026-10-01 08:00:00']);
        Pengumuman::factory()->create(['judul' => 'Baru', 'tanggal_terbit' => '2026-11-10 08:00:00']);
        Pengumuman::factory()->draft()->create(['judul' => 'Konsep']);

        $this->actingAs($this->user('admin'))
            ->get(route('pengumuman.index'))
            ->assertSeeInOrder(['Konsep', 'Baru', 'Lama']);
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

    public function test_nama_pembuat_ditampilkan_untuk_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Rafa Pembuat']);
        Pengumuman::factory()->create(['id_user' => $admin->getKey()]);

        $this->actingAs($admin)
            ->get(route('pengumuman.index'))
            ->assertSee('oleh Rafa Pembuat');
    }
}
