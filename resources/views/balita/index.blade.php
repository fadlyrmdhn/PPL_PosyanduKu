@extends('layouts.app')
@section('title', 'Data Balita')
@section('content')
<x-page-head title="Data Balita" subtitle="Kelola identitas, riwayat kelahiran, dan orang tua/wali balita.">
    <a href="{{ route('balita.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Balita</a>
</x-page-head>
<form method="GET" class="filter-bar row g-2">
    <div class="col-md-6 search-wrap">
        <i class="bi bi-search" style="left:1.6rem"></i>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau NIK">
    </div>
    <div class="col-md-4">
        <select name="unit" class="form-select">
            <option value="">Semua unit posyandu</option>
            @foreach ($unit as $u)
                <option value="{{ $u->id }}" @selected(request('unit') == $u->id)>{{ $u->nama_pos }} ({{ $u->rw_dusun }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-success"><i class="bi bi-funnel"></i> Cari</button>
    </div>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Balita</th><th>Tgl. Lahir</th><th>JK</th><th>Orang Tua/Wali</th><th>Posyandu</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($balita as $b)
                @php $kelas = $b->jenis_kelamin === 'L' ? 'boy' : 'girl'; @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar {{ $kelas }}">{{ strtoupper(substr($b->nama, 0, 1)) }}</span>
                            <div><strong>{{ $b->nama }}</strong><div class="text-muted small">{{ $b->nik }}</div></div>
                        </div>
                    </td>
                    <td>{{ $b->tanggal_lahir->format('d-m-Y') }}</td>
                    <td><span class="pk-badge {{ $kelas }}"><i class="bi bi-gender-{{ $b->jenis_kelamin === 'L' ? 'male' : 'female' }}"></i> {{ $b->jenis_kelamin }}</span></td>
                    <td>{{ $b->orangTua->pluck('nama')->join(', ') ?: '-' }}</td>
                    <td><span class="pk-badge teal">{{ $b->unitPosyandu->nama_pos }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('balita.show', $b) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('balita.edit', $b) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('balita.destroy', $b) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus data balita ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty"><i class="bi bi-emoji-smile"></i>Belum ada data balita.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $balita->links() }}</div>
@endsection
