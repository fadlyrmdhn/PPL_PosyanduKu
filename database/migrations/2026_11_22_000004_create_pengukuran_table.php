<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengukuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_balita')->constrained('balita')->cascadeOnDelete();
            $table->date('tanggal_ukur'); // wajib, tidak di masa depan, tidak sebelum tanggal lahir
            $table->unsignedSmallInteger('usia_bulan'); // dihitung otomatis
            $table->decimal('berat_badan', 5, 2);   // kg, wajib, > 0
            $table->decimal('tinggi_badan', 5, 2);  // cm, wajib, > 0
            $table->enum('cara_ukur', ['terlentang', 'berdiri']);
            $table->decimal('lingkar_kepala', 5, 2)->nullable(); // cm
            $table->decimal('lila', 5, 2)->nullable(); // cm
            $table->foreignId('id_user')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengukuran');
    }
};
