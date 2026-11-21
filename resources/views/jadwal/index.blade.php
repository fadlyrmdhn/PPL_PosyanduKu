@extends('layouts.app')
@section('title', 'Jadwal Kegiatan')
@section('content')

<x-page-head title="Jadwal Kegiatan" subtitle="Daftar seluruh jadwal kegiatan posyandu.">
    @if (auth()->user()->role === 'admin')
        <a href="{{ route('jadwal.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Jadwal</a>
    @endif
</x-page-head>

{{-- Filter --}}
<form method="GET" action="{{ route('jadwal.index') }}" class="filter-bar mb-3">
    <div class="row g-2 align-items-end">
        @if (auth()->user()->role === 'admin')
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
        @endif
        <div class="col-sm-3">
            <select name="jenis" class="form-select">
                <option value="">Semua Jenis</option>
                @foreach (['penimbangan' => 'Penimbangan', 'imunisasi' => 'Imunisasi', 'penyuluhan' => 'Penyuluhan', 'lainnya' => 'Lainnya'] as $v => $l)
                    <option value="{{ $v }}" {{ request('jenis') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control">
        </div>
        <div class="col-sm-auto">
            <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Jenis</th>
                    <th>Unit</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($jadwal as $j)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar teal"><i class="bi {{ $j->iconJenis() }}"></i></span>
                            <strong>{{ $j->nama_kegiatan }}</strong>
                        </div>
                    </td>
                    <td><span class="pk-badge warm">{{ $j->labelJenis() }}</span></td>
                    <td><span class="pk-badge teal"><i class="bi bi-geo-alt"></i> {{ $j->unit?->nama_pos ?? '—' }}</span></td>
                    <td>{{ $j->tanggal->translatedFormat('d M Y') }}</td>
                    <td class="text-muted">{{ substr($j->waktu_mulai, 0, 5) }} – {{ substr($j->waktu_selesai, 0, 5) }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('jadwal.show', $j) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('jadwal.edit', $j) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('jadwal.destroy', $j) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus jadwal {{ addslashes($j->nama_kegiatan) }}? Semua data absensi terkait ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('absensi.create', $j) }}" class="btn btn-sm btn-outline-success btn-icon" title="Catat Kehadiran">
                                <i class="bi bi-clipboard-plus"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty"><i class="bi bi-calendar3"></i>Belum ada jadwal kegiatan.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $jadwal->links() }}</div>

@endsection
