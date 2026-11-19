<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel `pengumuman` persis sesuai ERD proposal (PBI-4):
 * id_pengumuman, id_user, judul varchar(150), isi text, status enum, tanggal_terbit datetime.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Kunci utama users: `id` (bawaan Laravel) atau `id_user` (jika PBI-1 mengikuti ERD).
        $kunciUsers = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';

        Schema::create('pengumuman', function (Blueprint $table) use ($kunciUsers) {
            $table->id('id_pengumuman');
            // Admin pembuat. Dikosongkan (bukan dihapus) jika akunnya dihapus agar pengumuman tetap ada.
            $table->foreignId('id_user')->nullable()->constrained('users', $kunciUsers)->nullOnDelete();
            $table->string('judul', 150);
            $table->text('isi');
            $table->enum('status', ['draft', 'dipublikasikan'])->default('draft');
            $table->dateTime('tanggal_terbit')->nullable();

            $table->index(['status', 'tanggal_terbit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
