<?php

namespace App\Http\Controllers;

use App\Models\StokVitaminObat;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StokVitaminObatController extends Controller
{
    /** Pilihan filter peringatan => nama scope pada model. */
    public const PERINGATAN = [
        'menipis' => 'menipis',
        'mendekati' => 'mendekatiKedaluwarsa',
        'kedaluwarsa' => 'kedaluwarsa',
    ];

    public function index(Request $request)
    {
        $peringatan = array_key_exists((string) $request->peringatan, self::PERINGATAN) ? $request->peringatan : null;

        $daftarStok = StokVitaminObat::query()
            ->when($request->q, fn ($query, $q) => $query->where('nama', 'like', "%{$q}%"))
            ->when(in_array($request->jenis, StokVitaminObat::JENIS, true), fn ($query) => $query->where('jenis', $request->jenis))
            ->when($peringatan, fn ($query, $kunci) => $query->{self::PERINGATAN[$kunci]}())
            ->orderBy('nama')
            ->orderBy('tanggal_kedaluwarsa')
            ->paginate(10)
            ->withQueryString();

        $ringkasan = [
            'menipis' => StokVitaminObat::menipis()->count(),
            'mendekati' => StokVitaminObat::mendekatiKedaluwarsa()->count(),
            'kedaluwarsa' => StokVitaminObat::kedaluwarsa()->count(),
        ];

        return view('stok_vitamin_obat.index', compact('daftarStok', 'ringkasan', 'peringatan'));
    }

    public function create()
    {
        return view('stok_vitamin_obat.create', ['stok' => new StokVitaminObat(['jumlah' => 0, 'stok_minimum' => 10])]);
    }

    public function store(Request $request)
    {
        StokVitaminObat::create($request->validate($this->rules(), $this->pesan()));

        return redirect()->route('stok-vitamin-obat.index')->with('success', 'Data stok berhasil ditambahkan.');
    }

    public function edit(StokVitaminObat $stok)
    {
        return view('stok_vitamin_obat.edit', compact('stok'));
    }

    public function update(Request $request, StokVitaminObat $stok)
    {
        $stok->update($request->validate($this->rules(), $this->pesan()));

        return redirect()->route('stok-vitamin-obat.index')->with('success', 'Data stok berhasil diperbarui.');
    }

    public function destroy(StokVitaminObat $stok)
    {
        $stok->delete();

        return redirect()->route('stok-vitamin-obat.index')->with('success', 'Data stok berhasil dihapus.');
    }

    /**
     * Penambahan / pengurangan stok (FR-07).
     * Pengurangan dilakukan atomik di database sehingga stok tidak bisa menjadi negatif
     * walau ada dua permintaan bersamaan.
     */
    public function penyesuaian(Request $request, StokVitaminObat $stok)
    {
        $data = $request->validate([
            'tipe' => ['required', 'in:tambah,kurang'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000000'],
        ], [
            'tipe.required' => 'Pilih tambah atau kurangi stok.',
            'tipe.in' => 'Jenis penyesuaian stok tidak valid.',
            'jumlah.required' => 'Jumlah penyesuaian wajib diisi.',
            'jumlah.integer' => 'Jumlah penyesuaian harus berupa bilangan bulat.',
            'jumlah.min' => 'Jumlah penyesuaian minimal 1.',
            'jumlah.max' => 'Jumlah penyesuaian terlalu besar.',
        ]);
        $jumlah = (int) $data['jumlah'];

        if ($data['tipe'] === 'tambah') {
            $stok->increment('jumlah', $jumlah);
            $pesan = "Stok {$stok->nama} ditambah {$jumlah} {$stok->satuan}.";
        } else {
            $berhasil = StokVitaminObat::whereKey($stok->getKey())
                ->where('jumlah', '>=', $jumlah)
                ->decrement('jumlah', $jumlah);

            if (! $berhasil) {
                throw ValidationException::withMessages([
                    'jumlah' => "Stok {$stok->nama} tidak cukup. Sisa stok: {$stok->fresh()->jumlah} {$stok->satuan}.",
                ]);
            }

            $pesan = "Stok {$stok->nama} dikurangi {$jumlah} {$stok->satuan}.";
        }

        return redirect()
            ->route('stok-vitamin-obat.index', $request->only(['q', 'jenis', 'peringatan', 'page']))
            ->with('success', $pesan);
    }

    private function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:'.implode(',', StokVitaminObat::JENIS)],
            'jumlah' => ['required', 'integer', 'min:0', 'max:1000000'],
            'satuan' => ['required', 'string', 'max:50'],
            'tanggal_kedaluwarsa' => ['required', 'date'],
            'stok_minimum' => ['required', 'integer', 'min:0', 'max:1000000'],
        ];
    }

    private function pesan(): array
    {
        return [
            'nama.required' => 'Nama vitamin/obat wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'jenis.in' => 'Jenis yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa bilangan bulat.',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0.',
            'jumlah.max' => 'Jumlah terlalu besar (maksimal 1.000.000).',
            'satuan.required' => 'Satuan wajib diisi.',
            'satuan.max' => 'Satuan maksimal 50 karakter.',
            'tanggal_kedaluwarsa.required' => 'Tanggal kedaluwarsa wajib diisi.',
            'tanggal_kedaluwarsa.date' => 'Tanggal kedaluwarsa tidak valid.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'stok_minimum.integer' => 'Stok minimum harus berupa bilangan bulat.',
            'stok_minimum.min' => 'Stok minimum tidak boleh kurang dari 0.',
            'stok_minimum.max' => 'Stok minimum terlalu besar (maksimal 1.000.000).',
        ];
    }
}
