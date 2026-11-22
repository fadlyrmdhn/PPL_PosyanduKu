<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom role ke tabel users jika belum ada.
     * Kolom ini sudah didefinisikan di create_users_table dengan nilai:
     * admin, kader, orang_tua — migration ini bersifat idempoten.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            // Kolom sudah ada (didefinisikan di create_users_table), skip.
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kader', 'orang_tua'])->default('kader');
        });
    }

    public function down(): void
    {
        // Jangan drop kolom role di sini karena kolom ini milik create_users_table.
        // Hanya drop jika migration ini yang menambahkan (sudah dicek di up).
    }
};
