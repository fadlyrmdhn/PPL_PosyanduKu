<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\StokVitaminObat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** PBI-05 Stok Vitamin dan Obat. */
class StokVitaminObatTest extends TestCase
{
    use RefreshDatabase;

    private function akun(string $role): User
    {
        return User::factory()->role($role)->create();
    }

    private function dataValid(array $ubah = []): array
    {
        return $ubah + [
            'nama' => 'Vitamin A Biru',
            'jenis' => 'vitamin',
            'jumlah' => 100,
            'satuan' => 'kapsul',
            'tanggal_kedaluwarsa' => StokVitaminObat::hariIni()->addYear()->toDateString(),
            'stok_minimum' => 20,
        ];
    }

    // ---------------------------------------------------------------- akses

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('stok-vitamin-obat.index'))->assertRedirect(route('login'));
    }

    public function test_admin_dapat_melihat_daftar_stok(): void
    {
        StokVitaminObat::factory()->create(['nama' => 'Zinc Sirup']);

        $this->actingAs($this->akun('admin'))->get(route('stok-vitamin-obat.index'))
            ->assertOk()->assertSee('Zinc Sirup')->assertSee('Tambah Stok');
    }

    public function test_kader_dan_orang_tua_tidak_dapat_mengakses(): void
    {
        $stok = StokVitaminObat::factory()->create();

        foreach (['kader', 'orang_tua'] as $role) {
            $this->actingAs($this->akun($role));

            $this->get(route('stok-vitamin-obat.index'))->assertForbidden();
            $this->get(route('stok-vitamin-obat.create'))->assertForbidden();
            $this->post(route('stok-vitamin-obat.store'), $this->dataValid())->assertForbidden();
            $this->get(route('stok-vitamin-obat.edit', $stok))->assertForbidden();
            $this->put(route('stok-vitamin-obat.update', $stok), $this->dataValid())->assertForbidden();
            $this->post(route('stok-vitamin-obat.penyesuaian', $stok), ['tipe' => 'tambah', 'jumlah' => 5])->assertForbidden();
            $this->delete(route('stok-vitamin-obat.destroy', $stok))->assertForbidden();
        }

        $this->assertDatabaseCount('stok_vitamin_obat', 1);
    }

    // ----------------------------------------------------------------- CRUD

    public function test_admin_dapat_menambah_stok(): void
    {
        $this->actingAs($this->akun('admin'))->post(route('stok-vitamin-obat.store'), $this->dataValid())
            ->assertRedirect(route('stok-vitamin-obat.index'))
            ->assertSessionHas('success', 'Data stok berhasil ditambahkan.');

        $this->assertDatabaseHas('stok_vitamin_obat', ['nama' => 'Vitamin A Biru', 'jumlah' => 100]);
    }

    public function test_penambahan_menolak_data_tidak_valid(): void
    {
        $this->actingAs($this->akun('admin'))
            ->post(route('stok-vitamin-obat.store'), $this->dataValid(['jenis' => 'jamu', 'jumlah' => -1, 'nama' => '', 'satuan' => '', 'tanggal_kedaluwarsa' => 'x', 'stok_minimum' => 'a']))
            ->assertSessionHasErrors(['jenis', 'jumlah', 'nama', 'satuan', 'tanggal_kedaluwarsa', 'stok_minimum']);

        $this->assertDatabaseCount('stok_vitamin_obat', 0);
    }

    public function test_admin_dapat_membuka_form_tambah_dan_ubah(): void
    {
        $stok = StokVitaminObat::factory()->create(['nama' => 'Untuk Diubah']);

        $this->actingAs($this->akun('admin'));
        $this->get(route('stok-vitamin-obat.create'))->assertOk()->assertSee('Tambah Stok Vitamin/Obat');
        $this->get(route('stok-vitamin-obat.edit', $stok))->assertOk()->assertSee('Untuk Diubah');
    }

    public function test_admin_dapat_mengubah_stok(): void
    {
        $stok = StokVitaminObat::factory()->create(['nama' => 'Lama']);

        $this->actingAs($this->akun('admin'))->put(route('stok-vitamin-obat.update', $stok), $this->dataValid(['nama' => 'Baru']))
            ->assertRedirect(route('stok-vitamin-obat.index'))
            ->assertSessionHas('success', 'Data stok berhasil diperbarui.');

        $this->assertDatabaseHas('stok_vitamin_obat', ['id' => $stok->id, 'nama' => 'Baru']);
    }

    public function test_admin_dapat_menghapus_stok(): void
    {
        $stok = StokVitaminObat::factory()->create();

        $this->actingAs($this->akun('admin'))->delete(route('stok-vitamin-obat.destroy', $stok))
            ->assertRedirect(route('stok-vitamin-obat.index'))
            ->assertSessionHas('success', 'Data stok berhasil dihapus.');

        $this->assertModelMissing($stok);
    }

    // --------------------------------------------------------- penyesuaian

    public function test_admin_dapat_menambah_dan_mengurangi_jumlah_stok(): void
    {
        $stok = StokVitaminObat::factory()->create(['jumlah' => 50]);
        $this->actingAs($this->akun('admin'));

        $this->post(route('stok-vitamin-obat.penyesuaian', $stok), ['tipe' => 'tambah', 'jumlah' => 25])->assertRedirect();
        $this->assertSame(75, $stok->fresh()->jumlah);

        $this->post(route('stok-vitamin-obat.penyesuaian', $stok), ['tipe' => 'kurang', 'jumlah' => 20])->assertRedirect();
        $this->assertSame(55, $stok->fresh()->jumlah);
    }

    public function test_pengurangan_melebihi_stok_ditolak_dan_pesannya_tampil(): void
    {
        $stok = StokVitaminObat::factory()->create(['nama' => 'Zinc', 'jumlah' => 10]);

        $this->actingAs($this->akun('admin'))->from(route('stok-vitamin-obat.index'))
            ->post(route('stok-vitamin-obat.penyesuaian', $stok), ['tipe' => 'kurang', 'jumlah' => 11])
            ->assertSessionHasErrors('jumlah');

        $this->assertSame(10, $stok->fresh()->jumlah);

        $this->followingRedirects()->from(route('stok-vitamin-obat.index'))
            ->post(route('stok-vitamin-obat.penyesuaian', $stok), ['tipe' => 'kurang', 'jumlah' => 11])
            ->assertSee('tidak cukup');
    }

    public function test_pengurangan_sampai_nol_diperbolehkan(): void
    {
        $stok = StokVitaminObat::factory()->create(['jumlah' => 10]);

        $this->actingAs($this->akun('admin'))
            ->post(route('stok-vitamin-obat.penyesuaian', $stok), ['tipe' => 'kurang', 'jumlah' => 10])
            ->assertSessionHasNoErrors();

        $this->assertSame(0, $stok->fresh()->jumlah);
    }

    public function test_penyesuaian_menolak_jumlah_nol_dan_tipe_salah(): void
    {
        $stok = StokVitaminObat::factory()->create(['jumlah' => 10]);

        $this->actingAs($this->akun('admin'))
            ->post(route('stok-vitamin-obat.penyesuaian', $stok), ['tipe' => 'ganti', 'jumlah' => 0])
            ->assertSessionHasErrors(['tipe', 'jumlah']);

        $this->assertSame(10, $stok->fresh()->jumlah);
    }

    // ------------------------------------------- pencarian, filter, penanda

    public function test_pencarian_dan_filter_jenis(): void
    {
        StokVitaminObat::factory()->create(['nama' => 'Vitamin A Biru', 'jenis' => 'vitamin']);
        StokVitaminObat::factory()->create(['nama' => 'Paracetamol Sirup', 'jenis' => 'obat']);

        $this->actingAs($this->akun('admin'));

        $this->get(route('stok-vitamin-obat.index', ['q' => 'Paracetamol']))
            ->assertSee('Paracetamol Sirup')->assertDontSee('Vitamin A Biru');

        $this->get(route('stok-vitamin-obat.index', ['jenis' => 'vitamin']))
            ->assertSee('Vitamin A Biru')->assertDontSee('Paracetamol Sirup');
    }

    public function test_filter_peringatan_menipis_mendekati_dan_kedaluwarsa(): void
    {
        $hari = StokVitaminObat::hariIni();
        StokVitaminObat::factory()->create(['nama' => 'Item Aman', 'jumlah' => 100, 'stok_minimum' => 10, 'tanggal_kedaluwarsa' => $hari->copy()->addYear()->toDateString()]);
        StokVitaminObat::factory()->create(['nama' => 'Item Menipis', 'jumlah' => 3, 'stok_minimum' => 10, 'tanggal_kedaluwarsa' => $hari->copy()->addYear()->toDateString()]);
        StokVitaminObat::factory()->create(['nama' => 'Item Mendekati', 'jumlah' => 100, 'stok_minimum' => 10, 'tanggal_kedaluwarsa' => $hari->copy()->addDays(10)->toDateString()]);
        StokVitaminObat::factory()->create(['nama' => 'Item Lewat', 'jumlah' => 100, 'stok_minimum' => 10, 'tanggal_kedaluwarsa' => $hari->copy()->subDay()->toDateString()]);

        $this->actingAs($this->akun('admin'));

        $this->get(route('stok-vitamin-obat.index', ['peringatan' => 'menipis']))
            ->assertSee('Item Menipis')->assertDontSee('Item Aman')->assertDontSee('Item Mendekati')->assertDontSee('Item Lewat');
        $this->get(route('stok-vitamin-obat.index', ['peringatan' => 'mendekati']))
            ->assertSee('Item Mendekati')->assertDontSee('Item Aman')->assertDontSee('Item Lewat');
        $this->get(route('stok-vitamin-obat.index', ['peringatan' => 'kedaluwarsa']))
            ->assertSee('Item Lewat')->assertDontSee('Item Aman')->assertDontSee('Item Mendekati');
        // Nilai filter yang tidak dikenal diabaikan (tidak error).
        $this->get(route('stok-vitamin-obat.index', ['peringatan' => 'ngawur']))->assertOk()->assertSee('Item Aman');
    }

    public function test_penanda_menipis_habis_dan_kedaluwarsa(): void
    {
        $hari = StokVitaminObat::hariIni();
        $menipis = StokVitaminObat::factory()->create(['jumlah' => 5, 'stok_minimum' => 10]);
        $habis = StokVitaminObat::factory()->create(['jumlah' => 0, 'stok_minimum' => 10]);
        $aman = StokVitaminObat::factory()->create(['jumlah' => 100, 'stok_minimum' => 10, 'tanggal_kedaluwarsa' => $hari->copy()->addYear()->toDateString()]);
        $mendekati = StokVitaminObat::factory()->create(['tanggal_kedaluwarsa' => $hari->copy()->addDays(10)->toDateString()]);
        $lewat = StokVitaminObat::factory()->create(['tanggal_kedaluwarsa' => $hari->copy()->subDay()->toDateString()]);
        $hariIni = StokVitaminObat::factory()->create(['tanggal_kedaluwarsa' => $hari->toDateString()]);

        $this->assertTrue($menipis->isMenipis());
        $this->assertTrue($habis->isHabis());
        $this->assertFalse($habis->isMenipis());
        $this->assertSame([], $aman->peringatan());
        $this->assertTrue($mendekati->isMendekatiKedaluwarsa());
        $this->assertTrue($lewat->isKedaluwarsa());
        $this->assertFalse($hariIni->isKedaluwarsa());
        $this->assertTrue($hariIni->isMendekatiKedaluwarsa());

        $this->assertContains($lewat->id, StokVitaminObat::kedaluwarsa()->pluck('id')->all());
        $this->assertNotContains($hariIni->id, StokVitaminObat::kedaluwarsa()->pluck('id')->all());
        $this->assertContains($hariIni->id, StokVitaminObat::mendekatiKedaluwarsa()->pluck('id')->all());
        $this->assertNotContains($lewat->id, StokVitaminObat::mendekatiKedaluwarsa()->pluck('id')->all());
    }

    public function test_batas_kedaluwarsa_mengikuti_tanggal_wib_bukan_utc(): void
    {
        // 18.00 UTC tanggal 19 = 01.00 WIB tanggal 20.
        $this->travelTo(Carbon::create(2026, 11, 19, 18, 0, 0, 'UTC'));

        $kemarinWib = StokVitaminObat::factory()->create(['tanggal_kedaluwarsa' => '2026-11-19']);
        $hariIniWib = StokVitaminObat::factory()->create(['tanggal_kedaluwarsa' => '2026-11-20']);

        $this->assertTrue($kemarinWib->isKedaluwarsa());
        $this->assertFalse($hariIniWib->isKedaluwarsa());
        $this->assertSame([$kemarinWib->id], StokVitaminObat::kedaluwarsa()->pluck('id')->all());
    }

    // ------------------------------------------------- integrasi develop/PBI-4

    public function test_menu_stok_hanya_muncul_untuk_admin_dan_menu_pengumuman_tetap_ada(): void
    {
        $linkStok = 'href="'.route('stok-vitamin-obat.index').'"';
        $linkPengumuman = 'href="'.route('pengumuman.index').'"';

        $this->actingAs($this->akun('admin'))->get(route('dashboard.admin'))
            ->assertOk()->assertSee($linkStok, false)->assertSee($linkPengumuman, false);

        $this->actingAs($this->akun('kader'))->get(route('dashboard.kader'))
            ->assertOk()->assertDontSee($linkStok, false)->assertSee($linkPengumuman, false);

        $this->actingAs($this->akun('orang_tua'))->get(route('dashboard.orang-tua'))
            ->assertOk()->assertDontSee($linkStok, false)->assertSee($linkPengumuman, false);
    }

    public function test_dashboard_admin_menampilkan_peringatan_stok_dan_pengumuman(): void
    {
        $hari = StokVitaminObat::hariIni();
        StokVitaminObat::factory()->create(['jumlah' => 1, 'stok_minimum' => 10, 'tanggal_kedaluwarsa' => $hari->copy()->subDay()->toDateString()]);
        Pengumuman::factory()->create(['judul' => 'Info Terbit']);

        $this->actingAs($this->akun('admin'))->get(route('dashboard.admin'))
            ->assertOk()->assertSee('Peringatan Stok')->assertSee('Stok menipis')->assertSee('Sudah kedaluwarsa')
            ->assertSee('Pengumuman Terbaru')->assertSee('Info Terbit');
    }

    public function test_dashboard_admin_menyatakan_stok_aman_bila_tidak_ada_peringatan(): void
    {
        StokVitaminObat::factory()->create(['jumlah' => 100, 'stok_minimum' => 10, 'tanggal_kedaluwarsa' => StokVitaminObat::hariIni()->addYear()->toDateString()]);

        $this->actingAs($this->akun('admin'))->get(route('dashboard.admin'))
            ->assertOk()->assertSee('Semua stok vitamin dan obat aman.');
    }

    public function test_widget_stok_tidak_tampil_untuk_kader_dan_orang_tua(): void
    {
        StokVitaminObat::factory()->create(['jumlah' => 1, 'stok_minimum' => 10]);

        $this->actingAs($this->akun('kader'))->get(route('dashboard.kader'))->assertOk()->assertDontSee('Peringatan Stok');
        $this->actingAs($this->akun('orang_tua'))->get(route('dashboard.orang-tua'))->assertOk()->assertDontSee('Peringatan Stok');
    }

    public function test_seeder_membuat_stok_contoh_beserta_pengumuman_pbi4(): void
    {
        $this->seed();

        $this->assertDatabaseCount('stok_vitamin_obat', 4);
        $this->assertDatabaseCount('pengumuman', 3);
        $this->assertSame(1, StokVitaminObat::kedaluwarsa()->count());
        $this->assertSame(1, StokVitaminObat::mendekatiKedaluwarsa()->count());
        $this->assertSame(1, StokVitaminObat::menipis()->count());
    }
}
