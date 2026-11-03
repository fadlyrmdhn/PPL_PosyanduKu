<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom `role` pada tabel users (admin | kader | orang_tua), sesuai FR-01.
 *
 * Dibuat aman terhadap migration PBI-1 (Fadly): jika kolom `role` sudah ada,
 * migration ini tidak melakukan apa-apa. Jika PBI-1 sudah menambahkan `role`
 * lewat migration sendiri, file ini boleh dihapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('orang_tua')->after('password');
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
