@extends('layouts.app')
@section('title', 'Data Kader')
@section('content')

<x-page-head title="Data Kader" subtitle="Daftar kader posyandu beserta status keaktifannya.">
    <a href="{{ route('kader.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Kader</a>
</x-page-head>

{{-- Filter --}}
<form method="GET" action="{{ route('kader.index') }}" class="filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-sm-4">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" class="form-control"
                       placeholder="Cari nama, NIK, no HP…">
            </div>
        </div>
        <div class="col-sm-3">
            <select name="id_unit" class="form-select">
                <option value="">Semua Unit</option>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}" {{ request('id_unit') == $u->id ? 'selected' : '' }}>
                        {{ $u->nama_pos }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-sm-auto">
            <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('kader.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Kader</th>
                    <th>NIK</th>
                    <th>No HP</th>
                    <th>Unit Posyandu</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($kader as $k)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar {{ $k->jenis_kelamin === 'L' ? 'boy' : 'girl' }}">
                                {{ strtoupper(substr($k->nama, 0, 1)) }}
                            </span>
                            <div>
                                <strong>{{ $k->nama }}</strong>
                                <small class="d-block text-muted">{{ $k->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">{{ $k->nik }}</td>
                    <td>{{ $k->no_hp }}</td>
                    <td><span class="pk-badge teal"><i class="bi bi-geo-alt"></i> {{ $k->unit?->nama_pos ?? '—' }}</span></td>
                    <td>
                        <form action="{{ route('kader.toggle-status', $k) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="pk-badge {{ $k->badgeStatus() }} border-0 bg-transparent"
                                    title="Klik untuk toggle status"
                                    style="cursor:pointer">
                                <i class="bi bi-circle-fill" style="font-size:.5rem"></i>
                                {{ $k->labelStatus() }}
                            </button>
                        </form>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('kader.show', $k) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('kader.edit', $k) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('kader.destroy', $k) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus data kader {{ addslashes($k->nama) }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty">
                            <i class="bi bi-people"></i>
                            Belum ada data kader. <a href="{{ route('kader.create') }}">Tambah sekarang</a>.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $kader->links() }}</div>

@endsection
