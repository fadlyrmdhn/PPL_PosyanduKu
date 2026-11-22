<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengukuranRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya Kader yang boleh mencatat pengukuran
        return auth()->check() && in_array(auth()->user()->role, ['kader', 'admin']);
    }

    public function rules(): array
    {
        $balitaId = $this->input('id_balita');

        return [
            'id_balita'      => ['required', 'integer', Rule::exists('balita', 'id')],
            'tanggal_ukur'   => [
                'required',
                'date',
                'before_or_equal:today',
                // tanggal_ukur tidak boleh sebelum tanggal lahir balita
                function ($attribute, $value, $fail) use ($balitaId) {
                    if (!$balitaId) return;
                    $balita = \App\Models\Balita::find($balitaId);
                    if ($balita && \Carbon\Carbon::parse($value)->lt($balita->tanggal_lahir)) {
                        $fail('Tanggal ukur tidak boleh sebelum tanggal lahir balita.');
                    }
                },
            ],
            'berat_badan'    => ['required', 'numeric', 'min:0.1', 'max:100'],
            'tinggi_badan'   => ['required', 'numeric', 'min:1', 'max:250'],
            'cara_ukur'      => ['required', Rule::in(['terlentang', 'berdiri'])],
            'lingkar_kepala' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'lila'           => ['nullable', 'numeric', 'min:1', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_balita.required'          => 'Balita wajib dipilih.',
            'id_balita.exists'            => 'Balita tidak ditemukan.',
            'tanggal_ukur.required'       => 'Tanggal ukur wajib diisi.',
            'tanggal_ukur.before_or_equal'=> 'Tanggal ukur tidak boleh di masa depan.',
            'berat_badan.required'        => 'Berat badan wajib diisi.',
            'berat_badan.min'             => 'Berat badan harus lebih dari 0.',
            'berat_badan.max'             => 'Berat badan tidak wajar (maks. 100 kg).',
            'tinggi_badan.required'       => 'Tinggi badan wajib diisi.',
            'tinggi_badan.min'            => 'Tinggi badan harus lebih dari 0.',
            'tinggi_badan.max'            => 'Tinggi badan tidak wajar (maks. 250 cm).',
            'cara_ukur.required'          => 'Cara ukur wajib dipilih.',
            'cara_ukur.in'               => 'Cara ukur tidak valid.',
            'lingkar_kepala.min'          => 'Lingkar kepala tidak wajar.',
            'lila.min'                    => 'LiLA tidak wajar.',
        ];
    }
}
