<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** PBI-04 Pengumuman. */
class PengumumanTest extends TestCase
{
    use RefreshDatabase;

    private function akun(string $role, array $ubah = []): User
    {
        return User::factory()->role($role)->create($ubah);
    }

    private function dataValid(array $ubah = []): array
    {
        return $ubah + [
            'judul' => 'Jadwal Posyandu',
            'isi' => 'Posyandu dilaksanakan hari Rabu pukul 08.00.',
            'tanggal_terbit' => '2026-11-04T08:30',
            'status' => 'dipublikasikan',
        ];
    }

    // ---------------------------------------------------------------- akses

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('pengumuman.index'))->assertRedirect(route('login'));
    }

    public function test_admin_melihat_semua_pengumuman_termasuk_draft(): void
    {
        Pengumuman::factory()->create(['judul' => 'Sudah Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Masih Draft']);

        $this->actingAs($this->akun('admin'))->get(route('pengumuman.index'))
            ->assertOk()->assertSee('Sudah Terbit')->assertSee('Masih Draft')->assertSee('Tambah Pengumuman');
    }

    public function test_kader_dan_orang_tua_hanya_melihat_yang_dipublikasikan(): void
    {
        Pengumuman::factory()->create(['judul' => 'Sudah Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Masih Draft']);

        foreach (['kader', 'orang_tua'] as $role) {
            $this->actingAs($this->akun($role))->get(route('pengumuman.index'))
                ->assertOk()->assertSee('Sudah Terbit')->assertDontSee('Masih Draft')->assertDontSee('Tambah Pengumuman');
        }
    }

    public function test_detail_draft_ditolak_untuk_kader_dan_orang_tua_tetapi_boleh_untuk_admin(): void
    {
        $terbit = Pengumuman::factory()->create();
        $draft = Pengumuman::factory()->draft()->create();

        foreach (['kader', 'orang_tua'] as $role) {
            $this->actingAs($this->akun($role));
            $this->get(route('pengumuman.show', $terbit))->assertOk();
            $this->get(route('pengumuman.show', $draft))->assertForbidden();
        }

        $this->actingAs($this->akun('admin'))->get(route('pengumuman.show', $draft))->assertOk();
    }

    public function test_kader_dan_orang_tua_tidak_bisa_mengelola_pengumuman(): void
    {
        $draft = Pengumuman::factory()->draft()->create();

        foreach (['kader', 'orang_tua'] as $role) {
            $this->actingAs($this->akun($role));

            $this->get(route('pengumuman.create'))->assertForbidden();
            $this->post(route('pengumuman.store'), $this->dataValid())->assertForbidden();
            $this->get(route('pengumuman.edit', $draft))->assertForbidden();
            $this->put(route('pengumuman.update', $draft), $this->dataValid())->assertForbidden();
            $this->post(route('pengumuman.publikasikan', $draft))->assertForbidden();
            $this->delete(route('pengumuman.destroy', $draft))->assertForbidden();
        }

        $this->assertDatabaseCount('pengumuman', 1);
        $this->assertSame('draft', $draft->fresh()->status);
    }

    // ----------------------------------------------------------------- CRUD

    public function test_admin_dapat_menambah_pengumuman_dengan_tanggal_dan_jam(): void
    {
        $admin = $this->akun('admin');

        $this->actingAs($admin)->post(route('pengumuman.store'), $this->dataValid())
            ->assertRedirect(route('pengumuman.index'))
            ->assertSessionHas('success', 'Data pengumuman berhasil ditambahkan.');

        $p = Pengumuman::firstOrFail();
        $this->assertSame('Jadwal Posyandu', $p->judul);
        $this->assertSame('dipublikasikan', $p->status);
        $this->assertSame($admin->id, $p->id_user);
        $this->assertSame('2026-11-04 08:30', $p->tanggal_terbit->format('Y-m-d H:i'));
    }

    public function test_tanggal_terbit_otomatis_memakai_waktu_wib(): void
    {
        // 19.15 UTC tanggal 15 = 02.15 WIB tanggal 16.
        $this->travelTo(Carbon::create(2026, 11, 15, 19, 15, 0, 'UTC'));

        $this->actingAs($this->akun('admin'))
            ->post(route('pengumuman.store'), $this->dataValid(['tanggal_terbit' => null]));

        $this->assertSame('2026-11-16 02:15', Pengumuman::firstOrFail()->tanggal_terbit->format('Y-m-d H:i'));
    }

    public function test_draft_tanpa_tanggal_dibiarkan_kosong(): void
    {
        $this->actingAs($this->akun('admin'))
            ->post(route('pengumuman.store'), $this->dataValid(['status' => 'draft', 'tanggal_terbit' => null]));

        $p = Pengumuman::firstOrFail();
        $this->assertSame('draft', $p->status);
        $this->assertNull($p->tanggal_terbit);
    }

    public function test_validasi_menolak_data_tidak_lengkap_atau_tidak_valid(): void
    {
        $this->actingAs($this->akun('admin'))
            ->post(route('pengumuman.store'), ['judul' => '', 'isi' => '', 'status' => 'rahasia', 'tanggal_terbit' => 'bukan-tanggal'])
            ->assertSessionHasErrors(['judul', 'isi', 'status', 'tanggal_terbit']);

        $this->assertDatabaseCount('pengumuman', 0);
    }

    public function test_judul_maksimal_150_karakter(): void
    {
        $this->actingAs($this->akun('admin'))
            ->post(route('pengumuman.store'), $this->dataValid(['judul' => str_repeat('a', 151)]))
            ->assertSessionHasErrors('judul');
    }

    public function test_admin_dapat_mengubah_pengumuman(): void
    {
        $p = Pengumuman::factory()->draft()->create();

        $this->actingAs($this->akun('admin'))
            ->put(route('pengumuman.update', $p), $this->dataValid(['judul' => 'Judul Baru']))
            ->assertRedirect(route('pengumuman.index'))
            ->assertSessionHas('success', 'Data pengumuman berhasil diperbarui.');

        $p->refresh();
        $this->assertSame('Judul Baru', $p->judul);
        $this->assertSame('dipublikasikan', $p->status);
    }

    public function test_admin_dapat_membuka_form_tambah_dan_ubah(): void
    {
        $p = Pengumuman::factory()->create(['judul' => 'Untuk Diubah']);

        $this->actingAs($this->akun('admin'));
        $this->get(route('pengumuman.create'))->assertOk()->assertSee('Tambah Pengumuman');
        $this->get(route('pengumuman.edit', $p))->assertOk()->assertSee('Untuk Diubah');
    }

    public function test_admin_dapat_menghapus_pengumuman(): void
    {
        $p = Pengumuman::factory()->create();

        $this->actingAs($this->akun('admin'))->delete(route('pengumuman.destroy', $p))
            ->assertRedirect(route('pengumuman.index'))
            ->assertSessionHas('success', 'Data pengumuman berhasil dihapus.');

        $this->assertModelMissing($p);
    }

    public function test_pengumuman_tetap_ada_saat_akun_pembuat_dihapus(): void
    {
        $admin = $this->akun('admin');
        $p = Pengumuman::factory()->create(['id_user' => $admin->id]);

        $admin->delete();

        $this->assertNull($p->fresh()->id_user);
    }

    // --------------------------------------------------------- publikasikan

    public function test_admin_dapat_mempublikasikan_draft(): void
    {
        $draft = Pengumuman::factory()->draft()->create();

        $this->actingAs($this->akun('admin'))->post(route('pengumuman.publikasikan', $draft))->assertRedirect();

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

    public function test_draft_terlihat_oleh_orang_tua_setelah_dipublikasikan(): void
    {
        $draft = Pengumuman::factory()->draft()->create(['judul' => 'Akan Terbit']);
        $this->actingAs($this->akun('orang_tua'));

        $this->get(route('pengumuman.index'))->assertDontSee('Akan Terbit');

        $draft->publikasikan();

        $this->get(route('pengumuman.index'))->assertSee('Akan Terbit');
    }

    // ------------------------------------------- tampilan, pencarian, urutan

    public function test_tanggal_dan_jam_terbit_ditampilkan_dengan_wib(): void
    {
        Pengumuman::factory()->create(['judul' => 'Tes Tanggal', 'tanggal_terbit' => '2026-11-04 08:30:00']);

        $this->actingAs($this->akun('orang_tua'))->get(route('pengumuman.index'))->assertSee('04-11-2026 08:30 WIB');
    }

    public function test_urutan_terbaru_di_atas_dan_draft_paling_atas_bagi_admin(): void
    {
        Pengumuman::factory()->create(['judul' => 'Judul Lama', 'tanggal_terbit' => '2026-10-01 08:00:00']);
        Pengumuman::factory()->create(['judul' => 'Judul Baru', 'tanggal_terbit' => '2026-11-10 08:00:00']);
        Pengumuman::factory()->draft()->create(['judul' => 'Judul Konsep']);

        $this->actingAs($this->akun('admin'))->get(route('pengumuman.index'))
            ->assertSeeInOrder(['Judul Konsep', 'Judul Baru', 'Judul Lama']);
    }

    public function test_judul_dan_isi_dilindungi_dari_xss(): void
    {
        $p = Pengumuman::factory()->create(['judul' => '<script>alert(1)</script>', 'isi' => '<img src=x onerror=alert(1)>']);

        $this->actingAs($this->akun('orang_tua'))->get(route('pengumuman.show', $p))->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false);
    }

    public function test_pencarian_dengan_parameter_q(): void
    {
        Pengumuman::factory()->create(['judul' => 'Imunisasi Campak']);
        Pengumuman::factory()->create(['judul' => 'Penyuluhan Gizi']);

        $this->actingAs($this->akun('orang_tua'))->get(route('pengumuman.index', ['q' => 'Campak']))
            ->assertOk()->assertSee('Imunisasi Campak')->assertDontSee('Penyuluhan Gizi');
    }

    public function test_filter_status_hanya_berlaku_untuk_admin(): void
    {
        Pengumuman::factory()->create(['judul' => 'Sudah Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Masih Draft']);

        $this->actingAs($this->akun('admin'))->get(route('pengumuman.index', ['status' => 'draft']))
            ->assertSee('Masih Draft')->assertDontSee('Sudah Terbit');

        $this->actingAs($this->akun('orang_tua'))->get(route('pengumuman.index', ['status' => 'draft']))
            ->assertDontSee('Masih Draft');
    }

    public function test_nama_pembuat_ditampilkan_untuk_admin(): void
    {
        $admin = $this->akun('admin', ['nama' => 'Rafa Pembuat']);
        Pengumuman::factory()->create(['id_user' => $admin->id]);

        $this->actingAs($admin)->get(route('pengumuman.index'))->assertSee('Rafa Pembuat');
    }

    // ------------------------------------------------------ integrasi develop

    public function test_menu_pengumuman_muncul_di_sidebar_semua_peran(): void
    {
        foreach (['admin', 'kader', 'orang_tua'] as $role) {
            $this->actingAs($this->akun($role))->get(route(User::factory()->role($role)->make()->homeRoute()))
                ->assertOk()->assertSee('href="'.route('pengumuman.index').'"', false);
        }
    }

    public function test_dashboard_menampilkan_pengumuman_terbit_terbaru_saja(): void
    {
        Pengumuman::factory()->create(['judul' => 'Info Terbit']);
        Pengumuman::factory()->draft()->create(['judul' => 'Info Draft']);

        foreach (['admin' => 'dashboard.admin', 'kader' => 'dashboard.kader', 'orang_tua' => 'dashboard.orang-tua'] as $role => $route) {
            $this->actingAs($this->akun($role))->get(route($route))
                ->assertOk()->assertSee('Info Terbit')->assertDontSee('Info Draft');
        }
    }

    public function test_dashboard_menampilkan_maksimal_tiga_pengumuman(): void
    {
        foreach (range(1, 4) as $i) {
            Pengumuman::factory()->create(['judul' => "Berita ke-{$i}", 'tanggal_terbit' => "2026-11-0{$i} 08:00:00"]);
        }

        $this->actingAs($this->akun('orang_tua'))->get(route('dashboard.orang-tua'))
            ->assertSee('Berita ke-4')->assertSee('Berita ke-2')->assertDontSee('Berita ke-1');
    }

    public function test_seeder_membuat_pengumuman_contoh_milik_admin(): void
    {
        $this->seed();

        $this->assertDatabaseCount('pengumuman', 3);
        $this->assertSame(2, Pengumuman::dipublikasikan()->count());
        $this->assertSame('admin', Pengumuman::first()->pembuat->role);
    }
}
