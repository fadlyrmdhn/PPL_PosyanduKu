<?php

namespace Tests\Feature;

use App\Models\Balita;
use App\Models\OrangTua;
use App\Models\UnitPosyandu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint1FadlyTest extends TestCase
{
    use RefreshDatabase;

    private function akun(string $role): User
    {
        return User::factory()->role($role)->create();
    }

    private function unit(): UnitPosyandu
    {
        return UnitPosyandu::create(['nama_pos' => 'Melati', 'rw_dusun' => 'RW 01', 'alamat' => 'Jl. A']);
    }

    private function dataBalita(UnitPosyandu $unit, array $ubah = []): array
    {
        return $ubah + [
            'id_unit' => $unit->id, 'nama' => 'Budi', 'nik' => '1234567890123456',
            'tanggal_lahir' => '2025-01-01', 'jenis_kelamin' => 'L',
        ];
    }

    public function test_login_dengan_email_atau_username_redirect_sesuai_peran(): void
    {
        $user = User::factory()->role('kader')->create(['username' => 'kader1', 'email' => 'k@x.test']);

        $this->post('/login', ['login' => 'k@x.test', 'password' => 'password'])->assertRedirect(route('dashboard.kader'));
        $this->post('/logout');
        $this->post('/login', ['login' => 'kader1', 'password' => 'password'])->assertRedirect(route('dashboard.kader'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_pesan_generik_dan_field_wajib(): void
    {
        $user = $this->akun('admin');

        $this->from('/login')->post('/login', ['login' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors(['login' => 'Email/username atau kata sandi salah.']);
        $this->from('/login')->post('/login', ['login' => 'tidakada', 'password' => 'salah'])
            ->assertSessionHasErrors(['login' => 'Email/username atau kata sandi salah.']);
        $this->post('/login', [])->assertSessionHasErrors(['login', 'password']);
    }

    public function test_orang_tua_ditolak_di_menu_admin_dan_logout_menghapus_sesi(): void
    {
        $ortu = $this->akun('orang_tua');

        $this->actingAs($ortu)->get('/balita')->assertForbidden();
        $this->get('/unit-posyandu')->assertForbidden();
        $respons = $this->get(route('dashboard.orang-tua'))->assertOk();
        $this->assertStringContainsString('no-store', $respons->headers->get('Cache-Control'));

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get('/balita')->assertRedirect(route('login'));
    }

    public function test_unit_crud_validasi_dan_tidak_bisa_dihapus_bila_dipakai(): void
    {
        $this->actingAs($this->akun('kader'));

        $this->post('/unit-posyandu', [])->assertSessionHasErrors(['nama_pos', 'rw_dusun', 'alamat']);
        $this->post('/unit-posyandu', ['nama_pos' => 'Mawar', 'rw_dusun' => 'RW 2', 'alamat' => 'Jl. B'])
            ->assertRedirect('/unit-posyandu');

        $unit = $this->unit();
        $this->delete("/unit-posyandu/{$unit->id}")->assertRedirect()->assertSessionMissing('error');
        $this->assertModelMissing($unit);

        $dipakai = $this->unit();
        Balita::create($this->dataBalita($dipakai));
        $this->delete("/unit-posyandu/{$dipakai->id}")->assertSessionHas('error');
        $this->assertModelExists($dipakai);
    }

    public function test_nik_balita_harus_16_digit_dan_unik_serta_tanggal_tidak_masa_depan(): void
    {
        $this->actingAs($this->akun('admin'));
        $unit = $this->unit();

        $this->post('/balita', $this->dataBalita($unit, ['nik' => '123']))->assertSessionHasErrors('nik');
        $this->post('/balita', $this->dataBalita($unit, ['tanggal_lahir' => now()->addDay()->toDateString()]))
            ->assertSessionHasErrors('tanggal_lahir');

        $this->post('/balita', $this->dataBalita($unit))->assertSessionHasNoErrors();
        $this->post('/balita', $this->dataBalita($unit))->assertSessionHasErrors('nik');
    }

    public function test_satu_orang_tua_terhubung_ke_banyak_balita_dan_tampil_di_detail_serta_pencarian(): void
    {
        $this->actingAs($this->akun('admin'));
        $unit = $this->unit();
        $ibu = OrangTua::create(['nama' => 'Siti', 'nik' => '1111111111111111', 'hubungan' => 'ibu']);

        $this->post('/balita', $this->dataBalita($unit, ['nama' => 'Anak Satu', 'orang_tua' => [$ibu->id]]));
        $this->post('/balita', $this->dataBalita($unit, ['nama' => 'Anak Dua', 'nik' => '2222222222222222', 'orang_tua' => [$ibu->id]]));

        $this->assertSame(2, $ibu->balita()->count());
        $this->get('/balita/'.Balita::where('nama', 'Anak Satu')->value('id'))->assertSee('Siti');
        $this->get('/balita?q=Dua')->assertSee('Anak Dua')->assertDontSee('Anak Satu');
        $this->get('/balita?q=1234')->assertDontSee('Anak Dua');
        $this->get('/balita?q=2222222222')->assertSee('Anak Dua');
    }

    public function test_nik_orang_tua_validasi_dan_kaitkan_akun_login(): void
    {
        $this->actingAs($this->akun('kader'));
        $akun = $this->akun('orang_tua');
        $data = ['nama' => 'Siti', 'nik' => '1111111111111111', 'hubungan' => 'ibu', 'id_user' => $akun->id];

        $this->post('/orang-tua', $data)->assertSessionHasNoErrors();
        $this->post('/orang-tua', ['nik' => '1111111111111111'] + $data)->assertSessionHasErrors('nik');
        $this->post('/orang-tua', ['nik' => '12'] + $data)->assertSessionHasErrors('nik');
        $this->assertSame($akun->id, OrangTua::first()->id_user);
    }
}
