<?php

namespace App\Http\Controllers;

use App\Models\Kader;
use App\Models\UnitPosyandu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KaderController extends Controller
{
    public function index(Request $request)
    {
        $query = Kader::with('unit')->orderBy('nama');

        if ($request->filled('id_unit')) {
            $query->where('id_unit', $request->id_unit);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('nik', 'like', "%{$cari}%")
                  ->orWhere('no_hp', 'like', "%{$cari}%");
            });
        }

        $kader = $query->paginate(15)->withQueryString();
        $units  = UnitPosyandu::orderBy('nama_pos')->get();

        return view('kader.index', compact('kader', 'units'));
    }

    public function create()
    {
        $units = UnitPosyandu::orderBy('nama_pos')->get();

        return view('kader.create', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        DB::transaction(function () use ($data, $request) {
            // Buat akun user jika email disertakan
            $userId = null;

            if ($request->filled('email')) {
                $user = User::create([
                    'nama'     => $data['nama'],
                    'username' => $request->username ?? str($data['nama'])->slug('_')->toString(),
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 'kader',
                ]);
                $userId = $user->id;
            }

            Kader::create(array_merge(
                collect($data)->except(['email', 'password', 'username'])->all(),
                ['id_user' => $userId]
            ));
        });

        return redirect()->route('kader.index')
            ->with('success', 'Data kader berhasil ditambahkan.');
    }

    public function show(Kader $kader)
    {
        $kader->load(['unit', 'user', 'absensi.jadwal']);

        return view('kader.show', compact('kader'));
    }

    public function edit(Kader $kader)
    {
        $units = UnitPosyandu::orderBy('nama_pos')->get();

        return view('kader.edit', compact('kader', 'units'));
    }

    public function update(Request $request, Kader $kader)
    {
        $data = $this->validasi($request, $kader->id);

        $kader->update(collect($data)->except(['email', 'password', 'username'])->all());

        return redirect()->route('kader.index')
            ->with('success', 'Data kader berhasil diperbarui.');
    }

    public function destroy(Kader $kader)
    {
        // Hapus kader; akun user tetap dipertahankan (nullOnDelete di FK)
        $kader->delete();

        return redirect()->route('kader.index')
            ->with('success', 'Data kader berhasil dihapus.');
    }

    /** Toggle status aktif ↔ nonaktif. */
    public function toggleStatus(Kader $kader)
    {
        $kader->update([
            'status' => $kader->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        $label = $kader->fresh()->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Kader {$kader->nama} berhasil {$label}.");
    }

    // ── Private ──────────────────────────────────────────────

    private function validasi(Request $request, ?int $kaderId = null): array
    {
        return $request->validate(
            [
                'id_unit'           => ['required', 'exists:unit_posyandu,id'],
                'nama'              => ['required', 'string', 'max:255'],
                'nik'               => ['required', 'digits:16', Rule::unique('kader', 'nik')->ignore($kaderId)],
                'jenis_kelamin'     => ['required', Rule::in(['L', 'P'])],
                'tanggal_lahir'     => ['nullable', 'date', 'before:today'],
                'no_hp'             => ['required', 'string', 'max:20'],
                'alamat'            => ['nullable', 'string'],
                'status'            => ['required', Rule::in(['aktif', 'nonaktif'])],
                'tanggal_bergabung' => ['nullable', 'date'],
            ],
            [
                'id_unit.required'       => 'Unit posyandu wajib dipilih.',
                'id_unit.exists'         => 'Unit posyandu tidak ditemukan.',
                'nama.required'          => 'Nama kader wajib diisi.',
                'nik.required'           => 'NIK wajib diisi.',
                'nik.digits'             => 'NIK harus tepat 16 digit angka.',
                'nik.unique'             => 'NIK sudah terdaftar untuk kader lain.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'no_hp.required'         => 'Nomor HP wajib diisi.',
                'status.required'        => 'Status wajib dipilih.',
            ]
        );
    }
}
