<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\PemberianVitamin;
use Illuminate\Http\Request;

class PemberianVitaminController extends Controller
{
    public function index(Request $request)
    {
        $balita = Balita::orderBy('nama')->get();
        $balitaTerpilih = null;
        $pemberian = collect();

        if ($request->filled('balita')) {
            $balitaTerpilih = Balita::findOrFail($request->balita);
            $pemberian = $balitaTerpilih->pemberianVitamin()
                ->with('pencatat')
                ->orderByDesc('tanggal_pemberian')
                ->get();
        }

        return view('pemberian_vitamin.index', compact('balita', 'balitaTerpilih', 'pemberian'));
    }

    public function create(Request $request)
    {
        $balita = Balita::orderBy('nama')->get();
        $balitaTerpilih = $request->filled('balita') ? Balita::findOrFail($request->balita) : null;

        return view('pemberian_vitamin.create', compact('balita', 'balitaTerpilih'));
    }

    public function store(Request $request)
    {
        $balita = Balita::findOrFail($request->id_balita);

        $data = $request->validate([
            'id_balita'         => ['required', 'exists:balita,id'],
            'nama_vitamin'      => ['required', 'string', 'max:255'],
            'tanggal_pemberian' => [
                'required', 'date',
                'before_or_equal:today',
                'after_or_equal:' . $balita->tanggal_lahir->toDateString(),
            ],
            'dosis'             => ['nullable', 'string', 'max:100'],
            'keterangan'        => ['nullable', 'string'],
        ], [
            'id_balita.required'                => 'Balita wajib dipilih.',
            'nama_vitamin.required'             => 'Nama vitamin wajib diisi.',
            'tanggal_pemberian.required'        => 'Tanggal pemberian wajib diisi.',
            'tanggal_pemberian.before_or_equal' => 'Tanggal pemberian tidak boleh di masa depan.',
            'tanggal_pemberian.after_or_equal'  => 'Tanggal pemberian tidak boleh sebelum tanggal lahir balita.',
        ]);

        $data['id_user'] = auth()->id();

        PemberianVitamin::create($data);

        return redirect()
            ->route('pemberian-vitamin.index', ['balita' => $data['id_balita']])
            ->with('success', 'Catatan pemberian vitamin berhasil disimpan.');
    }

    public function show(PemberianVitamin $pemberianVitamin)
    {
        $pemberianVitamin->load('balita', 'pencatat');

        return view('pemberian_vitamin.show', compact('pemberianVitamin'));
    }

    public function edit(PemberianVitamin $pemberianVitamin)
    {
        $pemberianVitamin->load('balita');

        return view('pemberian_vitamin.edit', compact('pemberianVitamin'));
    }

    public function update(Request $request, PemberianVitamin $pemberianVitamin)
    {
        $balita = $pemberianVitamin->balita;

        $data = $request->validate([
            'nama_vitamin'      => ['required', 'string', 'max:255'],
            'tanggal_pemberian' => [
                'required', 'date',
                'before_or_equal:today',
                'after_or_equal:' . $balita->tanggal_lahir->toDateString(),
            ],
            'dosis'             => ['nullable', 'string', 'max:100'],
            'keterangan'        => ['nullable', 'string'],
        ], [
            'nama_vitamin.required'             => 'Nama vitamin wajib diisi.',
            'tanggal_pemberian.required'        => 'Tanggal pemberian wajib diisi.',
            'tanggal_pemberian.before_or_equal' => 'Tanggal pemberian tidak boleh di masa depan.',
            'tanggal_pemberian.after_or_equal'  => 'Tanggal pemberian tidak boleh sebelum tanggal lahir balita.',
        ]);

        $pemberianVitamin->update($data);

        return redirect()
            ->route('pemberian-vitamin.index', ['balita' => $pemberianVitamin->id_balita])
            ->with('success', 'Catatan pemberian vitamin berhasil diperbarui.');
    }

    public function destroy(PemberianVitamin $pemberianVitamin)
    {
        $idBalita = $pemberianVitamin->id_balita;
        $pemberianVitamin->delete();

        return redirect()
            ->route('pemberian-vitamin.index', ['balita' => $idBalita])
            ->with('success', 'Catatan pemberian vitamin berhasil dihapus.');
    }
}
