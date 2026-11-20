<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['nama', 'username', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    /** Nama route halaman awal sesuai peran. */
    public function homeRoute(): string
    {
        return match ($this->role) {
            'admin' => 'dashboard.admin',
            'kader' => 'dashboard.kader',
            default => 'dashboard.orang-tua',
        };
    }
}
