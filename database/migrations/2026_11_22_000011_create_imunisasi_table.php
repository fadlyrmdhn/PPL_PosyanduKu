<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imunisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_balita')->constrained('balita')->cascadeOnDelete();
            $table->foreignId('id_vaksin')->constrained('vaksin')->restrictOnDelete();
            $table->date('tanggal_pemberian');
            $table->unsignedInteger('dosis_ke')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('id_user')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imunisasi');
    }
};
