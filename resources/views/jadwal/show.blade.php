@extends('layouts.app')
@section('title', 'Detail Jadwal')
@section('content')

<x-page-head title="{{ $jadwal->nama_kegiatan }}" subtitle="{{ $jadwal->tanggal->translatedFormat('l, d F Y') }}">
    @if (auth()->user()->role === 'admin')
        <a href="{{ route('jadwal.edit', $jadwal) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i> Ubah</a>
        <form action="{{ route('jadwal.destroy', $jadwal) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Hapus jadwal ini? Semua catatan absensi ikut terhapus.')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
        </form>
    @else
        <a href="{{ route('absensi.create', $jadwal) }}" class="btn btn-success">
            <i class="bi bi-clipboard-plus"></i> Catat Kehadiranku
        </a>
    @endif
    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="row g-3">
    {{-- Info kegiatan --}}
    <div class="col-lg-4">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="avatar lg teal"><i class="bi {{ $jadwal->iconJenis() }}"></i></span>
                <div>
                    <h5 class="mb-1">{{ $jadwal->nama_kegiatan }}</h5>
                    <span class="pk-badge warm">{{ $jadwal->labelJenis() }}</span>
                </div>
            </div>
            <dl class="detail-list row row-cols-1 g-2 small mb-0">
                <div class="col">
                    <dt>Unit Posyandu</dt>
                    <dd><span class="pk-badge teal"><i class="bi bi-geo-alt"></i> {{ $jadwal->unit?->nama_pos ?? '—' }}</span></dd>
                </div>
                <div class="col">
                    <dt>Tanggal</dt>
                    <dd>{{ $jadwal->tanggal->translatedFormat('d F Y') }}</dd>
                </div>
                <div class="col">
                    <dt>Waktu</dt>
                    <dd>{{ substr($jadwal->waktu_mulai, 0, 5) }} – {{ substr($jadwal->waktu_selesai, 0, 5) }} WIB</dd>
                </div>
                @if ($jadwal->keterangan)
                <div class="col">
                    <dt>Keterangan</dt>
                    <dd>{{ $jadwal->keterangan }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Daftar absensi --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard-check"></i> Daftar Kehadiran Kader</span>
                <span class="pk-badge teal">{{ $absensi->count() }} tercatat</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kader</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Dicatat</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($absensi as $a)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar {{ $a->kader?->jenis_kelamin === 'L' ? 'boy' : 'girl' }}" style="width:2rem;height:2rem;font-size:.8rem">
                                        {{ strtoupper(substr($a->kader?->nama ?? '?', 0, 1)) }}
                                    </span>
                                    {{ $a->kader?->nama ?? '—' }}
                                </div>
                            </td>
                            <td class="text-muted">{{ $a->kader?->unit?->nama_pos ?? '—' }}</td>
                            <td><span class="pk-badge {{ $a->badgeStatus() }}">{{ $a->labelStatus() }}</span></td>
                            <td class="text-muted">{{ $a->keterangan ?: '—' }}</td>
                            <td class="text-muted" style="font-size:.8rem">
                                {{ $a->dicatat_pada ? $a->dicatat_pada->format('d/m/Y H:i') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty"><i class="bi bi-clipboard"></i>Belum ada catatan kehadiran untuk kegiatan ini.</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
