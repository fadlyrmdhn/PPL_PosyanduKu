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
        Schema::create('status_gizi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengukuran')
                  ->unique()
                  ->constrained('pengukuran')
                  ->cascadeOnDelete();

            // BB/U
            $table->decimal('zscore_bb_u', 6, 3)->nullable();
            $table->string('kategori_bb_u')->nullable();

            // TB/U
            $table->decimal('zscore_tb_u', 6, 3)->nullable();
            $table->string('kategori_tb_u')->nullable();

            // BB/TB
            $table->decimal('zscore_bb_tb', 6, 3)->nullable();
            $table->string('kategori_bb_tb')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_gizi');
    }
};
