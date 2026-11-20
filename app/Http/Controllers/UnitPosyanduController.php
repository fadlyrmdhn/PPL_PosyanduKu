<?php

namespace App\Http\Controllers;

use App\Models\UnitPosyandu;
use Illuminate\Http\Request;

class UnitPosyanduController extends Controller
{
    public function index()
    {
        $unit = UnitPosyandu::withCount('balita')->orderBy('nama_pos')->paginate(10);

        return view('unit_posyandu.index', compact('unit'));
    }

    public function create()
    {
        return view('unit_posyandu.create');
    }

    public function store(Request $request)
    {
        UnitPosyandu::create($this->validasi($request));

        return redirect()->route('unit-posyandu.index')->with('success', 'Unit posyandu berhasil ditambahkan.');
    }

    public function edit(UnitPosyandu $unitPosyandu)
    {
        return view('unit_posyandu.edit', ['unit' => $unitPosyandu]);
    }

    public function update(Request $request, UnitPosyandu $unitPosyandu)
    {
        $unitPosyandu->update($this->validasi($request));

        return redirect()->route('unit-posyandu.index')->with('success', 'Unit posyandu berhasil diperbarui.');
    }

    public function destroy(UnitPosyandu $unitPosyandu)
    {
        if ($unitPosyandu->masihDipakai()) {
            return back()->with('error', 'Unit tidak bisa dihapus karena masih dipakai data balita atau kader.');
        }

        $unitPosyandu->delete();

        return redirect()->route('unit-posyandu.index')->with('success', 'Unit posyandu berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama_pos' => ['required', 'string', 'max:255'],
            'rw_dusun' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
        ], [
            'nama_pos.required' => 'Nama pos wajib diisi.',
            'rw_dusun.required' => 'RW/Dusun wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);
    }
}
