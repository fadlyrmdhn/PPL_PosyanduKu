<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengukuranRequest;
use App\Models\Balita;
use App\Models\Pengukuran;
use App\Services\StatusGiziService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk mencatat Pengukuran Pertumbuhan & Status Gizi (FR-15).
 * Akses: Kader
 */
class PengukuranController extends Controller
{
    public function __construct(private StatusGiziService $statusGiziService) {}

    /**
     * Pilih balita (atau tampil riwayat jika sudah dipilih).
     * F-15.4 Riwayat pengukuran & status gizi per balita.
     */
    public function index(Request $request): View
    {
        $balitaList = Balita::orderBy('nama')->get();

        $balita     = null;
        $riwayat    = collect();

        if ($request->filled('balita_id')) {
            $balita  = Balita::findOrFail($request->integer('balita_id'));
            $riwayat = Pengukuran::with('statusGizi', 'user')
                ->where('id_balita', $balita->id)
                ->orderByDesc('tanggal_ukur')
                ->get();
        }

        return view('pengukuran.index', compact('balitaList', 'balita', 'riwayat'));
    }

    /**
     * Form input pengukuran baru.
     * F-15.1
     */
    public function create(Request $request): View
    {
        $balitaList  = Balita::orderBy('nama')->get();
        $balitaId    = $request->integer('balita_id') ?: null;
        $balitaPilih = $balitaId ? Balita::find($balitaId) : null;

        return view('pengukuran.create', compact('balitaList', 'balitaPilih'));
    }

    /**
     * F-15.1 Simpan pengukuran + hitung status gizi otomatis.
     * F-15.3
     */
    public function store(PengukuranRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $balita = Balita::findOrFail($data['id_balita']);

        // Hitung usia_bulan otomatis
        $data['usia_bulan'] = (int) $balita->tanggal_lahir
            ->diffInMonths(Carbon::parse($data['tanggal_ukur']));

        // Tambahkan id_user (kader yang login)
        $data['id_user'] = auth()->id();

        $pengukuran = Pengukuran::create($data);

        // F-15.3: Hitung dan simpan status gizi otomatis
        $this->statusGiziService->hitungDanSimpan($pengukuran);

        return redirect()->route('pengukuran.show', $pengukuran)
            ->with('success', 'Pengukuran berhasil disimpan dan status gizi telah dihitung.');
    }

    /**
     * Detail hasil pengukuran + status gizi.
     */
    public function show(Pengukuran $pengukuran): View
    {
        $pengukuran->load(['balita', 'statusGizi', 'user']);
        return view('pengukuran.show', compact('pengukuran'));
    }

    /**
     * Form ubah pengukuran.
     * F-15.2
     */
    public function edit(Pengukuran $pengukuran): View
    {
        $pengukuran->load('balita');
        $balitaList = Balita::orderBy('nama')->get();
        return view('pengukuran.edit', compact('pengukuran', 'balitaList'));
    }

    /**
     * F-15.2 Simpan perubahan + hitung ulang status gizi.
     */
    public function update(PengukuranRequest $request, Pengukuran $pengukuran): RedirectResponse
    {
        $data = $request->validated();

        $balita = Balita::findOrFail($data['id_balita']);

        // Hitung ulang usia_bulan
        $data['usia_bulan'] = (int) $balita->tanggal_lahir
            ->diffInMonths(Carbon::parse($data['tanggal_ukur']));

        $pengukuran->update($data);

        // Hitung ulang status gizi
        $pengukuran->refresh();
        $this->statusGiziService->hitungDanSimpan($pengukuran);

        return redirect()->route('pengukuran.show', $pengukuran)
            ->with('success', 'Pengukuran diperbarui dan status gizi dihitung ulang.');
    }

    /**
     * F-15.2 Hapus pengukuran (status gizi ikut terhapus via cascade).
     */
    public function destroy(Pengukuran $pengukuran): RedirectResponse
    {
        $balitaId = $pengukuran->id_balita;
        $pengukuran->delete();

        return redirect()->route('pengukuran.index', ['balita_id' => $balitaId])
            ->with('success', 'Data pengukuran berhasil dihapus.');
    }
}
