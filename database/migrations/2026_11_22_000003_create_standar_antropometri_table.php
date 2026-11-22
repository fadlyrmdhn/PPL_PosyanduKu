<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel referensi standar antropometri WHO 2006.
     * Kolom `acuan`:
     *   - Untuk bb_u dan tb_u: nilai usia dalam bulan (0–60)
     *   - Untuk bb_tb: nilai tinggi/panjang badan (cm)
     */
    public function up(): void
    {
        Schema::create('standar_antropometri', function (Blueprint $table) {
            $table->id();
            $table->enum('indikator', ['bb_u', 'tb_u', 'bb_tb']);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->decimal('acuan', 6, 1); // usia bulan atau tinggi (cm)
            $table->decimal('median', 6, 3);
            $table->decimal('min_1sd', 6, 3);
            $table->decimal('min_2sd', 6, 3);
            $table->decimal('min_3sd', 6, 3);
            $table->decimal('plus_1sd', 6, 3);
            $table->decimal('plus_2sd', 6, 3);
            $table->decimal('plus_3sd', 6, 3);

            $table->unique(['indikator', 'jenis_kelamin', 'acuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_antropometri');
    }
};
