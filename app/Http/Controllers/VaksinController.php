<?php

namespace App\Http\Controllers;

use App\Models\Vaksin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VaksinController extends Controller
{
    public function index()
    {
        $vaksin = Vaksin::orderBy('usia_pemberian_bulan')->orderBy('dosis_ke')->get();

        return view('vaksin.index', compact('vaksin'));
    }

    public function create()
    {
        return view('vaksin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->pesan());

        Vaksin::create($data);

        return redirect()->route('vaksin.index')->with('success', 'Data vaksin berhasil ditambahkan.');
    }

    public function show(Vaksin $vaksin)
    {
        return view('vaksin.show', compact('vaksin'));
    }

    public function edit(Vaksin $vaksin)
    {
        return view('vaksin.edit', compact('vaksin'));
    }

    public function update(Request $request, Vaksin $vaksin)
    {
        $data = $request->validate($this->rules($vaksin), $this->pesan());

        $vaksin->update($data);

        return redirect()->route('vaksin.index')->with('success', 'Data vaksin berhasil diperbarui.');
    }

    public function destroy(Vaksin $vaksin)
    {
        if ($vaksin->sudahDipakai()) {
            return back()->with('error', 'Vaksin tidak dapat dihapus karena sudah dipakai dalam catatan imunisasi.');
        }

        $vaksin->delete();

        return redirect()->route('vaksin.index')->with('success', 'Data vaksin berhasil dihapus.');
    }

    private function rules(?Vaksin $vaksin = null): array
    {
        return [
            'nama_vaksin'           => ['required', 'string', 'max:255', Rule::unique('vaksin', 'nama_vaksin')->ignore($vaksin?->id)],
            'usia_pemberian_bulan'  => ['required', 'integer', 'min:0'],
            'dosis_ke'              => ['required', 'integer', 'min:1'],
            'jumlah_dosis'          => ['nullable', 'string', 'max:100'],
            'keterangan'            => ['nullable', 'string'],
        ];
    }

    private function pesan(): array
    {
        return [
            'nama_vaksin.required'          => 'Nama vaksin wajib diisi.',
            'nama_vaksin.unique'            => 'Nama vaksin sudah terdaftar.',
            'usia_pemberian_bulan.required' => 'Usia pemberian wajib diisi.',
            'usia_pemberian_bulan.min'      => 'Usia pemberian tidak boleh negatif.',
            'dosis_ke.required'             => 'Dosis ke- wajib diisi.',
            'dosis_ke.min'                  => 'Dosis ke- minimal 1.',
        ];
    }
}
