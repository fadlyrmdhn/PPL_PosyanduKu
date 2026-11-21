<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $orangTua = OrangTua::withCount('balita')
            ->when($request->q, function ($query, $q) {
                $query->where(fn ($w) => $w->where('nama', 'like', "%{$q}%")->orWhere('nik', 'like', "%{$q}%"));
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('orang_tua.index', compact('orangTua'));
    }

    public function create()
    {
        return view('orang_tua.create', $this->pilihan());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->pesan());

        $orangTua = OrangTua::create($data);
        $orangTua->balita()->sync($data['balita'] ?? []);

        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua/wali berhasil ditambahkan.');
    }

    public function edit(OrangTua $orangTua)
    {
        $orangTua->load('balita');

        return view('orang_tua.edit', ['orangTua' => $orangTua] + $this->pilihan());
    }

    public function update(Request $request, OrangTua $orangTua)
    {
        $data = $request->validate($this->rules($orangTua), $this->pesan());

        $orangTua->update($data);
        $orangTua->balita()->sync($data['balita'] ?? []);

        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua/wali berhasil diperbarui.');
    }

    public function destroy(OrangTua $orangTua)
    {
        $orangTua->delete();

        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua/wali berhasil dihapus.');
    }

    private function pilihan(): array
    {
        return [
            'balita' => Balita::orderBy('nama')->get(),
            'akun' => User::where('role', 'orang_tua')->orderBy('nama')->get(),
        ];
    }

    private function rules(?OrangTua $orangTua = null): array
    {
        return [
            'id_user' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', 'orang_tua'),
                Rule::unique('orang_tua', 'id_user')->ignore($orangTua?->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16', Rule::unique('orang_tua', 'nik')->ignore($orangTua?->id)],
            'hubungan' => ['required', 'in:ayah,ibu,wali'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'balita' => ['nullable', 'array'],
            'balita.*' => ['exists:balita,id'],
        ];
    }

    private function pesan(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar pada orang tua/wali lain.',
            'hubungan.required' => 'Hubungan wajib dipilih.',
            'id_user.unique' => 'Akun tersebut sudah terhubung ke orang tua/wali lain.',
        ];
    }
}
