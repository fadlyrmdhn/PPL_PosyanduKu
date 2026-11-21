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
                $query->where(fn ($w) => $w->where('nama', 'like', "%{$q}%")->orWhere('nik', 'like', "%{$q}%"));
            })
            ->when($request->unit, fn ($query, $unit) => $query->where('id_unit', $unit))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $unit = UnitPosyandu::orderBy('nama_pos')->get();

        return view('balita.index', compact('balita', 'unit'));
    }

    public function create()
    {
        return view('balita.create', $this->pilihan());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->pesan());

        $balita = Balita::create($data);
        $balita->orangTua()->sync($data['orang_tua'] ?? []);

        return redirect()->route('balita.show', $balita)->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function show(Balita $balita)
    {
        $balita->load(['orangTua', 'unitPosyandu']);

        return view('balita.show', compact('balita'));
    }

    public function edit(Balita $balita)
    {
        $balita->load('orangTua');

        return view('balita.edit', ['balita' => $balita] + $this->pilihan());
    }

    public function update(Request $request, Balita $balita)
    {
        $data = $request->validate($this->rules($balita), $this->pesan());

        $balita->update($data);
        $balita->orangTua()->sync($data['orang_tua'] ?? []);

        return redirect()->route('balita.show', $balita)->with('success', 'Data balita berhasil diperbarui.');
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();

        return redirect()->route('balita.index')->with('success', 'Data balita berhasil dihapus.');
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
            'id_unit' => ['required', 'exists:unit_posyandu,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16', Rule::unique('balita', 'nik')->ignore($balita?->id)],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'berat_lahir' => ['nullable', 'numeric', 'min:0.5', 'max:9.99'],
            'panjang_lahir' => ['nullable', 'numeric', 'min:20', 'max:99.9'],
            'usia_kehamilan' => ['nullable', 'integer', 'min:20', 'max:45'],
            'penolong_persalinan' => ['nullable', 'string', 'max:255'],
            'orang_tua' => ['nullable', 'array'],
            'orang_tua.*' => ['exists:orang_tua,id'],
        ];
    }

    private function pesan(): array
    {
        return [
            'id_unit.required' => 'Unit posyandu wajib dipilih.',
            'nama.required' => 'Nama balita wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar pada balita lain.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        ];
    }
}
