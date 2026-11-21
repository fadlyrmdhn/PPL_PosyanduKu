<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balita_orang_tua', function (Blueprint $table) {
            $table->foreignId('id_balita')->constrained('balita')->cascadeOnDelete();
            $table->foreignId('id_orang_tua')->constrained('orang_tua')->cascadeOnDelete();
            $table->primary(['id_balita', 'id_orang_tua']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balita_orang_tua');
    }
};
