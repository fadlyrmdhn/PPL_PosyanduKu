<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_kader', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal')->constrained('jadwal_kegiatan')->cascadeOnDelete();
            $table->foreignId('id_kader')->constrained('kader')->cascadeOnDelete();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->text('keterangan')->nullable();
            $table->timestamp('dicatat_pada')->nullable();
            $table->timestamps();

            // Satu kader hanya boleh satu catatan per kegiatan
            $table->unique(['id_jadwal', 'id_kader']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_kader');
    }
};
