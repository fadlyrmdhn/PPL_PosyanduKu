<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Imunisasi;
use App\Models\JadwalImunisasi;
use App\Models\Vaksin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ImunisasiController extends Controller
{
    public function index(Request $request)
    {
        // Kader: pilih balita dari daftar
        $balita = Balita::orderBy('nama')->get();
        $balitaTerpilih = null;
        $imunisasi = collect();

        if ($request->filled('balita')) {
            $balitaTerpilih = Balita::findOrFail($request->balita);
            // Perbarui status jadwal terlewat sebelum tampil
            JadwalImunisasi::tandaiTerlewat($balitaTerpilih->id);

            $imunisasi = $balitaTerpilih->imunisasi()
                ->with('vaksin', 'pencatat')
                ->orderByDesc('tanggal_pemberian')
                ->get();
        }

        return view('imunisasi.index', compact('balita', 'balitaTerpilih', 'imunisasi'));
    }

    public function create(Request $request)
    {
        $balita = Balita::orderBy('nama')->get();
        $vaksin = Vaksin::orderBy('usia_pemberian_bulan')->orderBy('dosis_ke')->get();
        $balitaTerpilih = $request->filled('balita') ? Balita::findOrFail($request->balita) : null;

        return view('imunisasi.create', compact('balita', 'vaksin', 'balitaTerpilih'));
    }

    public function store(Request $request)
    {
        $balita = Balita::findOrFail($request->id_balita);

        $data = $request->validate([
            'id_balita'         => ['required', 'exists:balita,id'],
            'id_vaksin'         => ['required', 'exists:vaksin,id'],
            'tanggal_pemberian' => [
                'required', 'date',
                'before_or_equal:today',
                'after_or_equal:' . $balita->tanggal_lahir->toDateString(),
            ],
            'dosis_ke'          => ['nullable', 'integer', 'min:1'],
            'keterangan'        => ['nullable', 'string'],
        ], [
            'id_balita.required'                => 'Balita wajib dipilih.',
            'id_vaksin.required'                => 'Vaksin wajib dipilih.',
            'tanggal_pemberian.required'        => 'Tanggal pemberian wajib diisi.',
            'tanggal_pemberian.before_or_equal' => 'Tanggal pemberian tidak boleh di masa depan.',
            'tanggal_pemberian.after_or_equal'  => 'Tanggal pemberian tidak boleh sebelum tanggal lahir balita.',
        ]);

        $data['id_user'] = auth()->id();

        $imunisasi = Imunisasi::create($data);

        // Tandai jadwal terkait sebagai selesai
        JadwalImunisasi::where('id_balita', $data['id_balita'])
            ->where('id_vaksin', $data['id_vaksin'])
            ->where('status', 'terjadwal')
            ->orderBy('tanggal_jadwal')
            ->first()?->update(['status' => 'selesai']);

        return redirect()
            ->route('imunisasi.index', ['balita' => $data['id_balita']])
            ->with('success', 'Catatan imunisasi berhasil disimpan.');
    }

    public function show(Imunisasi $imunisasi)
    {
        $imunisasi->load('balita', 'vaksin', 'pencatat');

        return view('imunisasi.show', compact('imunisasi'));
    }

    public function edit(Imunisasi $imunisasi)
    {
        $imunisasi->load('balita', 'vaksin');
        $vaksin = Vaksin::orderBy('usia_pemberian_bulan')->orderBy('dosis_ke')->get();

        return view('imunisasi.edit', compact('imunisasi', 'vaksin'));
    }

    public function update(Request $request, Imunisasi $imunisasi)
    {
        $balita = $imunisasi->balita;

        $data = $request->validate([
            'id_vaksin'         => ['required', 'exists:vaksin,id'],
            'tanggal_pemberian' => [
                'required', 'date',
                'before_or_equal:today',
                'after_or_equal:' . $balita->tanggal_lahir->toDateString(),
            ],
            'dosis_ke'          => ['nullable', 'integer', 'min:1'],
            'keterangan'        => ['nullable', 'string'],
        ], [
            'id_vaksin.required'                => 'Vaksin wajib dipilih.',
            'tanggal_pemberian.required'        => 'Tanggal pemberian wajib diisi.',
            'tanggal_pemberian.before_or_equal' => 'Tanggal pemberian tidak boleh di masa depan.',
            'tanggal_pemberian.after_or_equal'  => 'Tanggal pemberian tidak boleh sebelum tanggal lahir balita.',
        ]);

        $imunisasi->update($data);

        return redirect()
            ->route('imunisasi.index', ['balita' => $imunisasi->id_balita])
            ->with('success', 'Catatan imunisasi berhasil diperbarui.');
    }

    public function destroy(Imunisasi $imunisasi)
    {
        $idBalita = $imunisasi->id_balita;
        $idVaksin = $imunisasi->id_vaksin;

        $imunisasi->delete();

        // Kembalikan jadwal yang sudah ditandai selesai karena catatan ini ke terjadwal
        JadwalImunisasi::where('id_balita', $idBalita)
            ->where('id_vaksin', $idVaksin)
            ->where('status', 'selesai')
            ->orderBy('tanggal_jadwal')
            ->first()?->update(['status' => 'terjadwal']);

        return redirect()
            ->route('imunisasi.index', ['balita' => $idBalita])
            ->with('success', 'Catatan imunisasi berhasil dihapus.');
    }
}
