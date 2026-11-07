<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_id')->constrained('orang_tua')->restrictOnDelete();
            $table->foreignId('unit_posyandu_id')->constrained('unit_posyandu')->restrictOnDelete();
            $table->string('nama');
            $table->string('nik', 16)->nullable()->unique();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->decimal('berat_lahir', 4, 2)->nullable();
            $table->decimal('panjang_lahir', 4, 1)->nullable();
            $table->unsignedTinyInteger('usia_kehamilan')->nullable();
            $table->string('penolong_persalinan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balita');
    }
};