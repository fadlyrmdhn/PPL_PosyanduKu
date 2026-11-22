<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel balita sudah dibuat oleh 2026_11_20_000003_create_balita_table.php
     * dengan skema lengkap (id_unit, nik, dll). Migration ini hanya menambahkan
     * kolom tambahan yang belum ada agar tidak terjadi duplikat tabel.
     */
    public function up(): void
    {
        if (!Schema::hasTable('balita')) {
            // Fallback jika somehow tabel belum ada (seharusnya tidak terjadi)
            Schema::create('balita', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_unit')->constrained('unit_posyandu')->restrictOnDelete();
                $table->string('nama');
                $table->char('nik', 16)->unique();
                $table->date('tanggal_lahir');
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->decimal('berat_lahir', 4, 2)->nullable();
                $table->decimal('panjang_lahir', 4, 1)->nullable();
                $table->unsignedTinyInteger('usia_kehamilan')->nullable();
                $table->string('penolong_persalinan')->nullable();
                $table->timestamps();
            });
            return;
        }

        // Tabel sudah ada — tambah kolom opsional jika belum ada
        Schema::table('balita', function (Blueprint $table) {
            if (!Schema::hasColumn('balita', 'nama_orang_tua')) {
                $table->string('nama_orang_tua')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('balita', 'alamat')) {
                $table->string('alamat')->nullable()->after('nama_orang_tua');
            }
        });
    }

    public function down(): void
    {
        Schema::table('balita', function (Blueprint $table) {
            if (Schema::hasColumn('balita', 'nama_orang_tua')) {
                $table->dropColumn('nama_orang_tua');
            }
            if (Schema::hasColumn('balita', 'alamat')) {
                $table->dropColumn('alamat');
            }
        });
    }
};
