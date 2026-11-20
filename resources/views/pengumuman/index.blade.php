@extends('layouts.app')
@section('title', 'Pengumuman')
@section('content')
<x-page-head title="Pengumuman" subtitle="Informasi kegiatan posyandu untuk kader dan orang tua/wali.">
    @if ($isAdmin)
        <a href="{{ route('pengumuman.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Pengumuman</a>
    @endif
</x-page-head>
<form method="GET" class="filter-bar row g-2">
    <div class="{{ $isAdmin ? 'col-md-6' : 'col-md-10' }} search-wrap">
        <i class="bi bi-search" style="left:1.6rem"></i>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul atau isi pengumuman">
    </div>
    @if ($isAdmin)
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">Semua status</option>
                <option value="dipublikasikan" @selected(request('status') === 'dipublikasikan')>Dipublikasikan</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </select>
        </div>
    @endif
    <div class="col-md-2 d-grid">
        <button class="btn btn-success"><i class="bi bi-funnel"></i> Cari</button>
    </div>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Pengumuman</th><th>Tgl. Terbit</th>
                    @if ($isAdmin) <th>Status</th><th>Dibuat Oleh</th> @endif
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($pengumuman as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar teal"><i class="bi bi-megaphone-fill"></i></span>
                            <div>
                                <a href="{{ route('pengumuman.show', $p) }}" class="fw-semibold text-decoration-none">{{ $p->judul }}</a>
                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit($p->isi, 80) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap">{{ $p->tanggalTerbitLabel() }}</td>
                    @if ($isAdmin)
                        <td>
                            @if ($p->isDipublikasikan())
                                <span class="pk-badge teal"><i class="bi bi-check-circle"></i> Dipublikasikan</span>
                            @else
                                <span class="pk-badge warm"><i class="bi bi-pencil-square"></i> Draft</span>
                            @endif
                        </td>
                        <td>{{ $p->pembuat?->nama ?? '-' }}</td>
                    @endif
                    <td class="text-end text-nowrap">
                        <a href="{{ route('pengumuman.show', $p) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail"><i class="bi bi-eye"></i></a>
                        @if ($isAdmin)
                            @unless ($p->isDipublikasikan())
                                <form action="{{ route('pengumuman.publikasikan', $p) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary btn-icon" title="Publikasikan"><i class="bi bi-send"></i></button>
                                </form>
                            @endunless
                            <a href="{{ route('pengumuman.edit', $p) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('pengumuman.destroy', $p) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ $isAdmin ? 5 : 3 }}"><div class="empty"><i class="bi bi-megaphone"></i>Belum ada pengumuman.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $pengumuman->links() }}</div>
@endsection
