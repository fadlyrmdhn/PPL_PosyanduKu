<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Akun contoh untuk mencoba PBI-4 (kata sandi semua akun: "password").
 * SEMENTARA: hapus setelah autentikasi PBI-1 masuk. Jangan dipakai di produksi.
 */
class AkunContohSeeder extends Seeder
{
    public function run(): void
    {
        $akun = [
            ['name' => 'Admin Posyandu', 'email' => 'admin@posyandu.test', 'role' => 'admin'],
            ['name' => 'Kader Posyandu', 'email' => 'kader@posyandu.test', 'role' => 'kader'],
            ['name' => 'Orang Tua Contoh', 'email' => 'ortu@posyandu.test', 'role' => 'orang_tua'],
        ];

        foreach ($akun as $data) {
            if (! User::where('email', $data['email'])->exists()) {
                // Factory memakai mass-assignment tanpa guard, sehingga kolom `role` bisa diisi.
                User::factory()->create($data);
            }
        }
    }
}
