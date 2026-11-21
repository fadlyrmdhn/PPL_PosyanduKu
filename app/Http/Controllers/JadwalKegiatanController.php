<?php

namespace App\Http\Controllers;

use App\Models\JadwalKegiatan;
use App\Models\UnitPosyandu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalKegiatan::with('unit')->orderByDesc('tanggal')->orderBy('waktu_mulai');

        if ($request->filled('id_unit')) {
            $query->where('id_unit', $request->id_unit);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_kegiatan', $request->jenis);
        }

        if ($request->filled('bulan')) {
            // format: YYYY-MM
            [$tahun, $bulan] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
        }

        $jadwal = $query->paginate(15)->withQueryString();
        $units  = UnitPosyandu::orderBy('nama_pos')->get();

        return view('jadwal.index', compact('jadwal', 'units'));
    }

    public function create()
    {
        $units = UnitPosyandu::orderBy('nama_pos')->get();

        return view('jadwal.create', compact('units'));
    }

    public function store(Request $request)
    {
        JadwalKegiatan::create($this->validasi($request));

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil ditambahkan.');
    }

    public function show(JadwalKegiatan $jadwal)
    {
        $jadwal->load('unit');

        // Rekap absensi untuk kegiatan ini
        $absensi = $jadwal->absensi()->with('kader.unit')->get();

        return view('jadwal.show', compact('jadwal', 'absensi'));
    }

    public function edit(JadwalKegiatan $jadwal)
    {
        $units = UnitPosyandu::orderBy('nama_pos')->get();

        return view('jadwal.edit', compact('jadwal', 'units'));
    }

    public function update(Request $request, JadwalKegiatan $jadwal)
    {
        $jadwal->update($this->validasi($request));

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil diperbarui.');
    }

    public function destroy(JadwalKegiatan $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kegiatan berhasil dihapus.');
    }

    // ── Private ──────────────────────────────────────────────

    private function validasi(Request $request): array
    {
        return $request->validate(
            [
                'id_unit'        => ['required', 'exists:unit_posyandu,id'],
                'nama_kegiatan'  => ['required', 'string', 'max:255'],
                'jenis_kegiatan' => ['required', Rule::in(['penimbangan', 'imunisasi', 'penyuluhan', 'lainnya'])],
                'tanggal'        => ['required', 'date'],
                'waktu_mulai'    => ['required', 'date_format:H:i'],
                'waktu_selesai'  => ['required', 'date_format:H:i', 'after:waktu_mulai'],
                'keterangan'     => ['nullable', 'string'],
            ],
            [
                'id_unit.required'        => 'Unit posyandu wajib dipilih.',
                'id_unit.exists'          => 'Unit posyandu tidak ditemukan.',
                'nama_kegiatan.required'  => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'tanggal.required'        => 'Tanggal wajib diisi.',
                'waktu_mulai.required'    => 'Waktu mulai wajib diisi.',
                'waktu_selesai.required'  => 'Waktu selesai wajib diisi.',
                'waktu_selesai.after'     => 'Waktu selesai harus lebih besar dari waktu mulai.',
            ]
        );
    }
}
