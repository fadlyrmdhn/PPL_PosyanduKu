<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\JadwalImunisasi;
use App\Models\OrangTua;
use Illuminate\Http\Request;

/**
 * Controller untuk Orang Tua: hanya melihat data balita milik akun sendiri.
 * Pembatasan akses URL dilakukan via authorizeBalita() — abort 403 bila balita
 * tidak terhubung ke akun orang tua yang sedang login.
 */
class ImunisasiOrangTuaController extends Controller
{
    /** Pastikan balita milik orang tua yang login, atau abort 403. */
    private function authorizeBalita(Balita $balita): void
    {
        $orangTua = OrangTua::where('id_user', auth()->id())->first();

        abort_unless(
            $orangTua && $orangTua->balita()->where('balita.id', $balita->id)->exists(),
            403,
            'Anda tidak berhak mengakses data balita ini.'
        );
    }

    /** Daftar anak milik akun orang tua yang login. */
    public function daftarAnak()
    {
        $orangTua = OrangTua::where('id_user', auth()->id())->with('balita')->first();
        $balitaList = $orangTua ? $orangTua->balita : collect();

        return view('orang_tua.imunisasi.daftar_anak', compact('balitaList'));
    }

    /**
     * Halaman "Imunisasi Anak": jadwal berikutnya, riwayat imunisasi, riwayat vitamin
     * untuk satu balita milik orang tua yang sedang login.
     */
    public function show(Balita $balita)
    {
        $this->authorizeBalita($balita);

        // Perbarui status terlewat
        JadwalImunisasi::tandaiTerlewat($balita->id);

        $imunisasi = $balita->imunisasi()
            ->with('vaksin', 'pencatat')
            ->orderByDesc('tanggal_pemberian')
            ->get();

        $vitamin = $balita->pemberianVitamin()
            ->with('pencatat')
            ->orderByDesc('tanggal_pemberian')
            ->get();

        $jadwal = $balita->jadwalImunisasi()
            ->with('vaksin')
            ->orderBy('tanggal_jadwal')
            ->get();

        $jadwalBerikutnya = $jadwal->where('status', 'terjadwal')->first();

        return view('orang_tua.imunisasi.show', compact(
            'balita', 'imunisasi', 'vitamin', 'jadwal', 'jadwalBerikutnya'
        ));
    }
}
