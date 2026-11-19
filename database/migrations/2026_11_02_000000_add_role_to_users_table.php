<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom `role` (enum admin | kader | orang_tua) pada tabel users, sesuai ERD proposal.
 *
 * SEMENTARA, sampai autentikasi PBI-1 (Fadly) di-merge. Aman terhadap PBI-1: jika kolom `role`
 * sudah ada (misalnya sudah ditambahkan di migration users), migration ini tidak melakukan apa-apa
 * dan file ini boleh dihapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kader', 'orang_tua'])->default('orang_tua');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
