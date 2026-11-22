<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuMpasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya Admin yang boleh mengelola menu MPASI
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $imageRules = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        return [
            'nama_menu'       => ['required', 'string', 'max:255'],
            'usia_min_bulan'  => ['required', 'integer', 'min:0', 'max:60'],
            'usia_max_bulan'  => ['required', 'integer', 'min:0', 'max:60', 'gte:usia_min_bulan'],
            'bahan'           => ['required', 'string'],
            'cara_pembuatan'  => ['required', 'string'],
            'kandungan_gizi'  => ['nullable', 'string'],
            'gambar'          => $imageRules,
        ];
    }

    public function messages(): array
    {
        return [
            'nama_menu.required'       => 'Nama menu wajib diisi.',
            'usia_min_bulan.required'  => 'Usia minimum wajib diisi.',
            'usia_min_bulan.min'       => 'Usia minimum tidak boleh negatif.',
            'usia_max_bulan.required'  => 'Usia maksimum wajib diisi.',
            'usia_max_bulan.gte'       => 'Usia maksimum harus ≥ usia minimum.',
            'bahan.required'           => 'Bahan wajib diisi.',
            'cara_pembuatan.required'  => 'Cara pembuatan wajib diisi.',
            'gambar.image'             => 'File harus berupa gambar.',
            'gambar.max'               => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
