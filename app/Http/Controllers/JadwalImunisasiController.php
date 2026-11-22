<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\JadwalImunisasi;
use App\Models\Vaksin;
use Illuminate\Http\Request;

class JadwalImunisasiController extends Controller
{
    public function index(Request $request)
    {
        $balita = Balita::orderBy('nama')->get();
        $balitaTerpilih = null;
        $jadwal = collect();

        if ($request->filled('balita')) {
            $balitaTerpilih = Balita::findOrFail($request->balita);
            // Perbarui status terlewat
            JadwalImunisasi::tandaiTerlewat($balitaTerpilih->id);

            $jadwal = $balitaTerpilih->jadwalImunisasi()
                ->with('vaksin')
                ->orderBy('tanggal_jadwal')
                ->get();
        }

        return view('jadwal_imunisasi.index', compact('balita', 'balitaTerpilih', 'jadwal'));
    }

    public function create(Request $request)
    {
        $balita = Balita::orderBy('nama')->get();
        $vaksin = Vaksin::orderBy('usia_pemberian_bulan')->orderBy('dosis_ke')->get();
        $balitaTerpilih = $request->filled('balita') ? Balita::findOrFail($request->balita) : null;

        return view('jadwal_imunisasi.create', compact('balita', 'vaksin', 'balitaTerpilih'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_balita'      => ['required', 'exists:balita,id'],
            'id_vaksin'      => ['required', 'exists:vaksin,id'],
            'tanggal_jadwal' => ['required', 'date'],
            'status'         => ['required', 'in:terjadwal,selesai,terlewat'],
        ], [
            'id_balita.required'      => 'Balita wajib dipilih.',
            'id_vaksin.required'      => 'Vaksin wajib dipilih.',
            'tanggal_jadwal.required' => 'Tanggal jadwal wajib diisi.',
            'status.required'         => 'Status wajib dipilih.',
        ]);

        JadwalImunisasi::create($data);

        return redirect()
            ->route('jadwal-imunisasi.index', ['balita' => $data['id_balita']])
            ->with('success', 'Jadwal imunisasi berhasil ditambahkan.');
    }

    /**
     * Buat jadwal otomatis dari tanggal_lahir + usia_pemberian_bulan di master vaksin.
     */
    public function buatOtomatis(Request $request)
    {
        $request->validate([
            'id_balita' => ['required', 'exists:balita,id'],
        ]);

        $balita = Balita::findOrFail($request->id_balita);
        $vaksinList = Vaksin::all();
        $dibuat = 0;

        foreach ($vaksinList as $vaksin) {
            $tanggal = $balita->tanggal_lahir->copy()->addMonths($vaksin->usia_pemberian_bulan)->toDateString();

            // Hindari duplikat
            $sudahAda = JadwalImunisasi::where('id_balita', $balita->id)
                ->where('id_vaksin', $vaksin->id)
                ->exists();

            if (! $sudahAda) {
                JadwalImunisasi::create([
                    'id_balita'      => $balita->id,
                    'id_vaksin'      => $vaksin->id,
                    'tanggal_jadwal' => $tanggal,
                    'status'         => 'terjadwal',
                ]);
                $dibuat++;
            }
        }

        // Perbarui yang terlewat setelah membuat jadwal baru
        JadwalImunisasi::tandaiTerlewat($balita->id);

        return redirect()
            ->route('jadwal-imunisasi.index', ['balita' => $balita->id])
            ->with('success', "Berhasil membuat {$dibuat} jadwal imunisasi otomatis.");
    }

    public function edit(JadwalImunisasi $jadwalImunisasi)
    {
        $jadwalImunisasi->load('balita', 'vaksin');
        $vaksin = Vaksin::orderBy('usia_pemberian_bulan')->orderBy('dosis_ke')->get();

        return view('jadwal_imunisasi.edit', compact('jadwalImunisasi', 'vaksin'));
    }

    public function update(Request $request, JadwalImunisasi $jadwalImunisasi)
    {
        $data = $request->validate([
            'id_vaksin'      => ['required', 'exists:vaksin,id'],
            'tanggal_jadwal' => ['required', 'date'],
            'status'         => ['required', 'in:terjadwal,selesai,terlewat'],
        ], [
            'id_vaksin.required'      => 'Vaksin wajib dipilih.',
            'tanggal_jadwal.required' => 'Tanggal jadwal wajib diisi.',
            'status.required'         => 'Status wajib dipilih.',
        ]);

        $jadwalImunisasi->update($data);

        return redirect()
            ->route('jadwal-imunisasi.index', ['balita' => $jadwalImunisasi->id_balita])
            ->with('success', 'Jadwal imunisasi berhasil diperbarui.');
    }

    public function destroy(JadwalImunisasi $jadwalImunisasi)
    {
        $idBalita = $jadwalImunisasi->id_balita;
        $jadwalImunisasi->delete();

        return redirect()
            ->route('jadwal-imunisasi.index', ['balita' => $idBalita])
            ->with('success', 'Jadwal imunisasi berhasil dihapus.');
    }
}
