<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_vitamin_obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['vitamin', 'obat']);
            $table->unsignedInteger('jumlah')->default(0);
            $table->string('satuan', 50);
            $table->date('tanggal_kedaluwarsa');
            $table->unsignedInteger('stok_minimum')->default(10);
            $table->timestamps();

            $table->index('tanggal_kedaluwarsa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_vitamin_obat');
    }
};
