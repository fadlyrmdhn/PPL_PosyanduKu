<?php

namespace App\Http\Controllers;

use App\Models\AbsensiKader;
use App\Models\JadwalKegiatan;
use App\Models\Kader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AbsensiKaderController extends Controller
{
    /**
     * Kader: daftar jadwal di unitnya + status kehadirannya sendiri.
     * Admin: rekap per kegiatan (via show di JadwalKegiatanController).
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $kader = Kader::where('id_user', $user->id)->first();

        if (! $kader) {
            return redirect()->route('dashboard.kader')
                ->with('error', 'Profil kader tidak ditemukan untuk akun Anda.');
        }

        $query = JadwalKegiatan::where('id_unit', $kader->id_unit)
            ->with(['unit', 'absensi' => fn ($q) => $q->where('id_kader', $kader->id)])
            ->orderByDesc('tanggal')
            ->orderBy('waktu_mulai');

        if ($request->filled('bulan')) {
            [$tahun, $bulan] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
        }

        $jadwal = $query->paginate(15)->withQueryString();

        return view('absensi.index', compact('kader', 'jadwal'));
    }

    /**
     * Form catat/ubah kehadiran (Kader).
     */
    public function create(JadwalKegiatan $jadwal)
    {
        $user  = Auth::user();
        $kader = Kader::where('id_user', $user->id)->firstOrFail();

        // Pastikan jadwal ini memang di unit kader
        abort_unless($jadwal->id_unit === $kader->id_unit, 403, 'Anda tidak terdaftar di unit kegiatan ini.');

        $absensi = AbsensiKader::where('id_jadwal', $jadwal->id)
            ->where('id_kader', $kader->id)
            ->first();

        return view('absensi.create', compact('jadwal', 'kader', 'absensi'));
    }

    /**
     * Simpan / perbarui catatan kehadiran kader untuk satu kegiatan.
     */
    public function store(Request $request, JadwalKegiatan $jadwal)
    {
        $user  = Auth::user();
        $kader = Kader::where('id_user', $user->id)->firstOrFail();

        // Pastikan kader hanya mencatat untuk diri sendiri
        abort_unless($jadwal->id_unit === $kader->id_unit, 403, 'Anda tidak terdaftar di unit kegiatan ini.');

        $data = $request->validate(
            [
                'status'     => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alpa'])],
                'keterangan' => ['nullable', 'string', 'max:500'],
            ],
            [
                'status.required' => 'Status kehadiran wajib dipilih.',
                'status.in'       => 'Status kehadiran tidak valid.',
            ]
        );

        // updateOrCreate agar catatan ganda menjadi pembaruan data
        AbsensiKader::updateOrCreate(
            ['id_jadwal' => $jadwal->id, 'id_kader' => $kader->id],
            array_merge($data, ['dicatat_pada' => now()])
        );

        return redirect()->route('absensi.index')
            ->with('success', 'Kehadiran berhasil dicatat.');
    }

    /**
     * Admin: rekap kehadiran per kader dalam rentang periode.
     */
    public function rekap(Request $request)
    {
        $units  = \App\Models\UnitPosyandu::orderBy('nama_pos')->get();
        $kaders = collect();
        $rekap  = collect();

        $bulan = $request->filled('bulan') ? $request->bulan : now()->format('Y-m');
        [$tahun, $bln] = explode('-', $bulan);

        $query = Kader::with(['unit', 'absensi' => function ($q) use ($tahun, $bln) {
            $q->whereHas('jadwal', fn ($jq) =>
                $jq->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bln)
            )->with('jadwal');
        }])->orderBy('nama');

        if ($request->filled('id_unit')) {
            $query->where('id_unit', $request->id_unit);
        }

        $kaders = $query->get();

        // Hitung ringkasan per kader
        $rekap = $kaders->map(function ($k) {
            $abs = $k->absensi;
            return [
                'kader'   => $k,
                'hadir'   => $abs->where('status', 'hadir')->count(),
                'izin'    => $abs->where('status', 'izin')->count(),
                'sakit'   => $abs->where('status', 'sakit')->count(),
                'alpa'    => $abs->where('status', 'alpa')->count(),
                'total'   => $abs->count(),
            ];
        });

        return view('absensi.rekap', compact('rekap', 'units', 'bulan'));
    }
}
