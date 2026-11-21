<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('unit_posyandu')->restrictOnDelete();
            $table->string('nama_kegiatan');
            $table->enum('jenis_kegiatan', ['penimbangan', 'imunisasi', 'penyuluhan', 'lainnya']);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kegiatan');
    }
};
