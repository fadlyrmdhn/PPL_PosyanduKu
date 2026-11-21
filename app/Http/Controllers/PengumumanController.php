<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = $this->adalahAdmin($request);

        $pengumuman = Pengumuman::with('pembuat')
            // Kader dan Orang Tua hanya melihat yang sudah dipublikasikan.
            ->when(! $isAdmin, fn ($query) => $query->dipublikasikan())
            ->when($isAdmin && in_array($request->status, Pengumuman::STATUS, true), fn ($query) => $query->where('status', $request->status))
            ->when($request->q, function ($query, $q) {
                $query->where(fn ($w) => $w->where('judul', 'like', "%{$q}%")->orWhere('isi', 'like', "%{$q}%"));
            })
            ->terbaru()
            ->paginate(10)
            ->withQueryString();

        return view('pengumuman.index', compact('pengumuman', 'isAdmin'));
    }

    public function show(Request $request, Pengumuman $pengumuman)
    {
        $isAdmin = $this->adalahAdmin($request);

        abort_unless($isAdmin || $pengumuman->isDipublikasikan(), 403, 'Pengumuman ini belum dipublikasikan.');

        $pengumuman->load('pembuat');

        return view('pengumuman.show', compact('pengumuman', 'isAdmin'));
    }

    public function create()
    {
        return view('pengumuman.create', ['pengumuman' => new Pengumuman(['status' => Pengumuman::STATUS_DRAFT])]);
    }

    public function store(Request $request)
    {
        $data = $this->siapkan($request->validate($this->rules(), $this->pesan()));
        $data['id_user'] = $request->user()->id;

        Pengumuman::create($data);

        return redirect()->route('pengumuman.index')->with('success', 'Data pengumuman berhasil ditambahkan.');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $pengumuman->update($this->siapkan($request->validate($this->rules(), $this->pesan())));

        return redirect()->route('pengumuman.index')->with('success', 'Data pengumuman berhasil diperbarui.');
    }

    /** Tombol cepat untuk menerbitkan draft. */
    public function publikasikan(Pengumuman $pengumuman)
    {
        if ($pengumuman->isDipublikasikan()) {
            return back()->with('success', 'Pengumuman sudah dipublikasikan.');
        }

        $pengumuman->publikasikan();

        return back()->with('success', 'Data pengumuman berhasil dipublikasikan.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Data pengumuman berhasil dihapus.');
    }

    private function adalahAdmin(Request $request): bool
    {
        return $request->user()->role === 'admin';
    }

    /** Dipublikasikan tanpa tanggal terbit: isi otomatis dengan waktu sekarang (WIB). */
    private function siapkan(array $data): array
    {
        if ($data['status'] === Pengumuman::STATUS_DIPUBLIKASIKAN && empty($data['tanggal_terbit'])) {
            $data['tanggal_terbit'] = Pengumuman::sekarang();
        }

        return $data;
    }

    private function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string', 'max:10000'],
            'tanggal_terbit' => ['nullable', 'date'],
            'status' => ['required', 'in:'.implode(',', Pengumuman::STATUS)],
        ];
    }

    private function pesan(): array
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
