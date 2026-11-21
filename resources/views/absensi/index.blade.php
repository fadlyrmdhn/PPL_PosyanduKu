@extends('layouts.app')
@section('title', 'Kehadiran Saya')
@section('content')

<x-page-head title="Riwayat Kehadiran Saya" subtitle="Jadwal kegiatan di unit {{ $kader->unit?->nama_pos }} beserta status kehadiran Anda.">
</x-page-head>

{{-- Filter bulan --}}
<form method="GET" action="{{ route('absensi.index') }}" class="filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-sm-3">
            <label class="form-label small mb-1">Filter Bulan</label>
            <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control">
        </div>
        <div class="col-sm-auto">
            <button class="btn btn-primary"><i class="bi bi-funnel"></i> Tampilkan</button>
            <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
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
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status Kehadiran</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($jadwal as $j)
                @php $myAbsensi = $j->absensi->first(); @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar teal"><i class="bi {{ $j->iconJenis() }}"></i></span>
                            <strong>{{ $j->nama_kegiatan }}</strong>
                        </div>
                    </td>
                    <td><span class="pk-badge warm">{{ $j->labelJenis() }}</span></td>
                    <td>{{ $j->tanggal->translatedFormat('d M Y') }}</td>
                    <td class="text-muted">{{ substr($j->waktu_mulai, 0, 5) }} – {{ substr($j->waktu_selesai, 0, 5) }}</td>
                    <td>
                        @if ($myAbsensi)
                            <span class="pk-badge {{ $myAbsensi->badgeStatus() }}">{{ $myAbsensi->labelStatus() }}</span>
                        @else
                            <span class="pk-badge" style="background:#f1f5f9;color:#94a3b8">Belum dicatat</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('absensi.create', $j) }}" class="btn btn-sm {{ $myAbsensi ? 'btn-outline-primary' : 'btn-outline-success' }} btn-icon"
                           title="{{ $myAbsensi ? 'Ubah kehadiran' : 'Catat kehadiran' }}">
                            <i class="bi {{ $myAbsensi ? 'bi-pencil' : 'bi-clipboard-plus' }}"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty">
                            <i class="bi bi-calendar3"></i>
                            Belum ada jadwal kegiatan di unit Anda.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $jadwal->links() }}</div>

@endsection
