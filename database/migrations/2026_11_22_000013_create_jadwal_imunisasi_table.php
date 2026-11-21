<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_imunisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_balita')->constrained('balita')->cascadeOnDelete();
            $table->foreignId('id_vaksin')->constrained('vaksin')->cascadeOnDelete();
            $table->date('tanggal_jadwal');
            $table->enum('status', ['terjadwal', 'selesai', 'terlewat'])->default('terjadwal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_imunisasi');
    }
};
