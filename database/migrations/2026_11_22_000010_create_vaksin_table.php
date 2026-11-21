<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaksin', function (Blueprint $table) {
            $table->id();
            $table->string('nama_vaksin')->unique();
            $table->unsignedInteger('usia_pemberian_bulan');   // usia anjuran, ≥ 0
            $table->unsignedInteger('dosis_ke');               // ≥ 1
            $table->string('jumlah_dosis')->nullable();        // mis. 0,5 ml
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaksin');
    }
};
