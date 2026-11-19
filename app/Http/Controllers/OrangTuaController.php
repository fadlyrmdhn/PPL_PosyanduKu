<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $orangTua = OrangTua::withCount('balita')
            ->when($request->q, fn ($query, $q) => $query->where('nama', 'like', "%{$q}%"))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('orang_tua.index', compact('orangTua'));
    }

    public function create()
    {
        return view('orang_tua.create');
    }

    public function store(Request $request)
    {
        OrangTua::create($request->validate($this->rules()));

        return redirect()->route('orang-tua.index')
            ->with('success', 'Data orang tua/wali berhasil ditambahkan.');
    }

    public function edit(OrangTua $orangTua)
    {
        return view('orang_tua.edit', compact('orangTua'));
    }

    public function update(Request $request, OrangTua $orangTua)
    {
        $orangTua->update($request->validate($this->rules($orangTua)));

        return redirect()->route('orang-tua.index')
            ->with('success', 'Data orang tua/wali berhasil diperbarui.');
    }

    public function destroy(OrangTua $orangTua)
    {
        if ($orangTua->balita()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus: orang tua/wali ini masih terhubung dengan data balita.');
        }

        $orangTua->delete();

        return redirect()->route('orang-tua.index')
            ->with('success', 'Data orang tua/wali berhasil dihapus.');
    }

    private function rules(?OrangTua $orangTua = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'digits:16', Rule::unique('orang_tua', 'nik')->ignore($orangTua?->id)],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ];
    }
}