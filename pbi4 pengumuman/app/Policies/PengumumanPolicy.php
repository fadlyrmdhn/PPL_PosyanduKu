<?php

namespace App\Policies;

use App\Models\Pengumuman;
use App\Models\User;

/**
 * Hak akses pengumuman sesuai proposal (FR-06, Tabel 1 Karakteristik Pengguna, use case):
 * - Admin     : CRUD semua pengumuman (termasuk draft)
 * - Kader     : hanya baca pengumuman yang dipublikasikan
 * - Orang Tua : hanya baca pengumuman yang dipublikasikan
 *
 * Sesuaikan konstanta peran di bawah dengan nilai kolom `role` pada tabel users (PBI-1/autentikasi).
 */
class PengumumanPolicy
{
    private const ROLE_ADMIN = 'admin';
    private const ROLE_KADER = 'kader';
    private const ROLE_ORANG_TUA = 'orang_tua';

    public function viewAny(User $user): bool
    {
        return in_array($user->role, [self::ROLE_ADMIN, self::ROLE_KADER, self::ROLE_ORANG_TUA], true);
    }

    public function view(User $user, Pengumuman $pengumuman): bool
    {
        if ($user->role === self::ROLE_ADMIN) {
            return true;
        }

        return $this->viewAny($user) && $pengumuman->isDipublikasikan();
    }

    public function create(User $user): bool
    {
        return $user->role === self::ROLE_ADMIN;
    }

    public function update(User $user, Pengumuman $pengumuman): bool
    {
        return $user->role === self::ROLE_ADMIN;
    }

    public function delete(User $user, Pengumuman $pengumuman): bool
    {
        return $user->role === self::ROLE_ADMIN;
    }
}
