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
        Schema::create('menu_mpasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->unsignedTinyInteger('usia_min_bulan'); // mis. 6
            $table->unsignedTinyInteger('usia_max_bulan'); // mis. 8, harus >= usia_min
            $table->text('bahan');
            $table->text('cara_pembuatan');
            $table->text('kandungan_gizi')->nullable(); // energi, protein, lemak, dll.
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_mpasi');
    }
};
