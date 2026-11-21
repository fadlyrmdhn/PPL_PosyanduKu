<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemberian_vitamin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_balita')->constrained('balita')->cascadeOnDelete();
            $table->string('nama_vitamin');
            $table->date('tanggal_pemberian');
            $table->string('dosis')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('id_user')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemberian_vitamin');
    }
};
