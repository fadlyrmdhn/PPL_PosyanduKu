<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Test login sementara. Hapus bersama AuthSementaraController setelah PBI-1 di-merge. */
class AuthSementaraTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Test tidak bergantung pada hasil `npm run build`.
        $this->withoutVite();
    }

    public function test_halaman_login_dapat_dibuka(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Masuk');
    }

    public function test_login_berhasil_dan_diarahkan_ke_pengumuman(): void
    {
        $user = User::factory()->create(['role' => 'orang_tua']);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('pengumuman.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_dengan_kata_sandi_salah(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->from(route('login'))
            ->post(route('login'), ['email' => $user->email, 'password' => 'salah'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_mengakhiri_sesi(): void
    {
        $user = User::factory()->create(['role' => 'kader']);

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
