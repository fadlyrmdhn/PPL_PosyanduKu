@extends('layouts.app')
@section('title', 'Orang Tua/Wali')
@section('content')
<x-page-head title="Orang Tua/Wali" subtitle="Data orang tua dan wali yang terhubung dengan balita.">
    <a href="{{ route('orang-tua.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah</a>
</x-page-head>
<form method="GET" class="filter-bar row g-2">
    <div class="col-md-10 search-wrap">
        <i class="bi bi-search" style="left:1.6rem"></i>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau NIK">
    </div>
    <div class="col-md-2 d-grid"><button class="btn btn-success"><i class="bi bi-funnel"></i> Cari</button></div>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Nama</th><th>Hubungan</th><th>No. HP</th><th>Balita</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($orangTua as $o)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar teal">{{ strtoupper(substr($o->nama, 0, 1)) }}</span>
                            <div><strong>{{ $o->nama }}</strong><div class="text-muted small">{{ $o->nik }}</div></div>
                        </div>
                    </td>
                    <td><span class="pk-badge warm">{{ ucfirst($o->hubungan) }}</span></td>
                    <td>{{ $o->no_hp ?? '-' }}</td>
                    <td><span class="pk-badge teal"><i class="bi bi-emoji-smile"></i> {{ $o->balita_count }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('orang-tua.edit', $o) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('orang-tua.destroy', $o) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty"><i class="bi bi-people"></i>Belum ada data.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $orangTua->links() }}</div>
@endsection
