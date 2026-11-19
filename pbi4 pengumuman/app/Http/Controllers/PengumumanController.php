<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengumumanRequest;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Pengumuman::class);

        $isAdmin = $request->user()->can('create', Pengumuman::class);

        $query = Pengumuman::with('pembuat')->terbaru();

        if (! $isAdmin) {
            // Kader dan Orang Tua hanya melihat yang sudah dipublikasikan.
            $query->dipublikasikan();
        } elseif (in_array($request->query('status'), [Pengumuman::STATUS_DRAFT, Pengumuman::STATUS_DIPUBLIKASIKAN], true)) {
            $query->where('status', $request->query('status'));
        }

        $cari = trim((string) $request->query('cari', ''));
        if ($cari !== '') {
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('isi', 'like', "%{$cari}%");
            });
        }

        $pengumuman = $query->paginate(10)->withQueryString();

        return view('pengumuman.index', compact('pengumuman', 'isAdmin', 'cari'));
    }

    public function show(Pengumuman $pengumuman): View
    {
        Gate::authorize('view', $pengumuman);

        $pengumuman->load('pembuat');

        return view('pengumuman.show', compact('pengumuman'));
    }

    public function create(): View
    {
        Gate::authorize('create', Pengumuman::class);

        $pengumuman = new Pengumuman(['status' => Pengumuman::STATUS_DRAFT]);

        return view('pengumuman.create', compact('pengumuman'));
    }

    public function store(PengumumanRequest $request): RedirectResponse
    {
        $data = $this->siapkanData($request->validated());
        $data['dibuat_oleh'] = $request->user()->id;

        Pengumuman::create($data);

        return redirect()->route('pengumuman.index')
            ->with('sukses', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Pengumuman $pengumuman): View
    {
        Gate::authorize('update', $pengumuman);

        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(PengumumanRequest $request, Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->update($this->siapkanData($request->validated()));

        return redirect()->route('pengumuman.index')
            ->with('sukses', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        Gate::authorize('delete', $pengumuman);

        $pengumuman->delete();

        return redirect()->route('pengumuman.index')
            ->with('sukses', 'Pengumuman berhasil dihapus.');
    }

    /** Jika dipublikasikan tanpa tanggal terbit, isi otomatis dengan tanggal hari ini. */
    private function siapkanData(array $data): array
    {
        if ($data['status'] === Pengumuman::STATUS_DIPUBLIKASIKAN && empty($data['tanggal_terbit'])) {
            $data['tanggal_terbit'] = now()->toDateString();
        }

        return $data;
    }
}
