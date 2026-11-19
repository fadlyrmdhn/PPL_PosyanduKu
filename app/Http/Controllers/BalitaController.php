<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\OrangTua;
use App\Models\UnitPosyandu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $balita = Balita::with(['orangTua', 'unitPosyandu'])
            ->when($request->q, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%")
                      ->orWhere('nik', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('balita.index', compact('balita'));
    }

    public function create()
    {
        return view('balita.create', $this->pilihan());
    }

    public function store(Request $request)
    {
        Balita::create($request->validate($this->rules()));

        return redirect()->route('balita.index')
            ->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function show(Balita $balita)
    {
        $balita->load(['orangTua', 'unitPosyandu']);

        return view('balita.show', compact('balita'));
    }

    public function edit(Balita $balita)
    {
        return view('balita.edit', ['balita' => $balita] + $this->pilihan());
    }

    public function update(Request $request, Balita $balita)
    {
        $balita->update($request->validate($this->rules($balita)));

        return redirect()->route('balita.index')
            ->with('success', 'Data balita berhasil diperbarui.');
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();

        return redirect()->route('balita.index')
            ->with('success', 'Data balita berhasil dihapus.');
    }

    private function pilihan(): array
    {
        return [
            'orangTua' => OrangTua::orderBy('nama')->get(),
            'unit' => UnitPosyandu::orderBy('nama_pos')->get(),
        ];
    }

    private function rules(?Balita $balita = null): array
    {
        return [
            'orang_tua_id' => ['required', 'exists:orang_tua,id'],
            'unit_posyandu_id' => ['required', 'exists:unit_posyandu,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'digits:16', Rule::unique('balita', 'nik')->ignore($balita?->id)],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'berat_lahir' => ['nullable', 'numeric', 'min:0.5', 'max:9.99'],
            'panjang_lahir' => ['nullable', 'numeric', 'min:20', 'max:99.9'],
            'usia_kehamilan' => ['nullable', 'integer', 'min:20', 'max:45'],
            'penolong_persalinan' => ['nullable', 'string', 'max:255'],
        ];
    }
}