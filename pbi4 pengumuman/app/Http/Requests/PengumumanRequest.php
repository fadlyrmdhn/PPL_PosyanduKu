<?php

namespace App\Http\Requests;

use App\Models\Pengumuman;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengumumanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $pengumuman = $this->route('pengumuman');

        return $pengumuman instanceof Pengumuman
            ? $user->can('update', $pengumuman)
            : $user->can('create', Pengumuman::class);
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string', 'max:10000'],
            'tanggal_terbit' => ['nullable', 'date'],
            'status' => ['required', Rule::in([
                Pengumuman::STATUS_DRAFT,
                Pengumuman::STATUS_DIPUBLIKASIKAN,
            ])],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul' => 'judul',
            'isi' => 'isi pengumuman',
            'tanggal_terbit' => 'tanggal terbit',
            'status' => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'judul.max' => 'Judul pengumuman maksimal 150 karakter.',
            'isi.required' => 'Isi pengumuman wajib diisi.',
            'isi.max' => 'Isi pengumuman maksimal 10.000 karakter.',
            'tanggal_terbit.date' => 'Tanggal terbit tidak valid.',
            'status.required' => 'Status pengumuman wajib dipilih.',
            'status.in' => 'Status pengumuman tidak valid.',
        ];
    }
}
