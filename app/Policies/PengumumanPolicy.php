<?php

namespace App\Policies;

use App\Models\Pengumuman;
use App\Models\User;

/**
 * Hak akses pengumuman sesuai proposal (FR-06, Tabel 1 Karakteristik Pengguna, use case):
 * - Admin     : membuat, mengubah, menghapus, dan membaca semua pengumuman (termasuk draft)
 * - Kader     : hanya membaca pengumuman yang dipublikasikan
 * - Orang Tua : hanya membaca pengumuman yang dipublikasikan
 *
 * Peran dibaca dari `User::hasRole()` (class diagram) jika sudah dibuat oleh PBI-1;
 * jika belum, dari kolom `role` pada tabel users.
 */
class PengumumanPolicy
{
    private const ROLE_ADMIN = 'admin';
    private const ROLE_KADER = 'kader';
    private const ROLE_ORANG_TUA = 'orang_tua';

    private function punyaPeran(User $user, string $peran): bool
    {
        return method_exists($user, 'hasRole')
            ? (bool) $user->hasRole($peran)
            : $user->role === $peran;
    }

    private function adalahAdmin(User $user): bool
    {
        return $this->punyaPeran($user, self::ROLE_ADMIN);
    }

    public function viewAny(User $user): bool
    {
        return $this->adalahAdmin($user)
            || $this->punyaPeran($user, self::ROLE_KADER)
            || $this->punyaPeran($user, self::ROLE_ORANG_TUA);
    }

    public function view(User $user, Pengumuman $pengumuman): bool
    {
        if ($this->adalahAdmin($user)) {
            return true;
        }

        return $this->viewAny($user) && $pengumuman->isDipublikasikan();
    }

    public function create(User $user): bool
    {
        return $this->adalahAdmin($user);
    }

    public function update(User $user, Pengumuman $pengumuman): bool
    {
        return $this->adalahAdmin($user);
    }

    public function delete(User $user, Pengumuman $pengumuman): bool
    {
        return $this->adalahAdmin($user);
    }
}
