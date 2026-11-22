<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            // Admin pembuat. Dikosongkan (bukan dihapus) bila akunnya dihapus agar pengumuman tetap ada.
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judul', 150);
            $table->text('isi');
            $table->enum('status', ['draft', 'dipublikasikan'])->default('draft');
            $table->dateTime('tanggal_terbit')->nullable();
            $table->timestamps();

            $table->index(['status', 'tanggal_terbit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
