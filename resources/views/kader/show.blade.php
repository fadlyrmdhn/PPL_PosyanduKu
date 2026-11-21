@extends('layouts.app')
@section('title', 'Detail Kader')
@section('content')

<x-page-head title="Detail Kader" subtitle="{{ $kader->nama }}">
    <a href="{{ route('kader.edit', $kader) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i> Ubah</a>
    <form action="{{ route('kader.toggle-status', $kader) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn {{ $kader->status === 'aktif' ? 'btn-outline-warning' : 'btn-outline-success' }}">
            <i class="bi {{ $kader->status === 'aktif' ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
            {{ $kader->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
        </button>
    </form>
    <form action="{{ route('kader.destroy', $kader) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Hapus data kader ini secara permanen?')">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
    </form>
    <a href="{{ route('kader.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="row g-3">
    {{-- Kartu utama --}}
    <div class="col-lg-5">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="avatar lg {{ $kader->jenis_kelamin === 'L' ? 'boy' : 'girl' }}">
                    {{ strtoupper(substr($kader->nama, 0, 2)) }}
                </span>
                <div>
                    <h5 class="mb-0">{{ $kader->nama }}</h5>
                    <span class="pk-badge {{ $kader->badgeStatus() }} mt-1">
                        <i class="bi bi-circle-fill" style="font-size:.45rem"></i>
                        {{ $kader->labelStatus() }}
                    </span>
                </div>
            </div>
            <dl class="detail-list row row-cols-2 g-2 small mb-0">
                <div class="col">
                    <dt>NIK</dt>
                    <dd>{{ $kader->nik }}</dd>
                </div>
                <div class="col">
                    <dt>Jenis Kelamin</dt>
                    <dd>{{ $kader->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                </div>
                <div class="col">
                    <dt>Tanggal Lahir</dt>
                    <dd>{{ $kader->tanggal_lahir ? $kader->tanggal_lahir->translatedFormat('d F Y') : '—' }}</dd>
                </div>
                <div class="col">
                    <dt>No HP</dt>
                    <dd>{{ $kader->no_hp }}</dd>
                </div>
                <div class="col">
                    <dt>Unit Posyandu</dt>
                    <dd>{{ $kader->unit?->nama_pos ?? '—' }}</dd>
                </div>
                <div class="col">
                    <dt>Bergabung</dt>
                    <dd>{{ $kader->tanggal_bergabung ? $kader->tanggal_bergabung->translatedFormat('d F Y') : '—' }}</dd>
                </div>
                <div class="col-12">
                    <dt>Alamat</dt>
                    <dd>{{ $kader->alamat ?: '—' }}</dd>
                </div>
                <div class="col-12">
                    <dt>Akun Login</dt>
                    <dd>{{ $kader->user ? $kader->user->email : 'Belum ada akun' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Riwayat absensi --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-clipboard-check"></i> Riwayat Kehadiran</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($kader->absensi as $a)
                        <tr>
                            <td>{{ $a->jadwal?->nama_kegiatan ?? '—' }}</td>
                            <td>{{ $a->jadwal?->tanggal?->translatedFormat('d M Y') ?? '—' }}</td>
                            <td><span class="pk-badge {{ $a->badgeStatus() }}">{{ $a->labelStatus() }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty"><i class="bi bi-clipboard"></i>Belum ada riwayat kehadiran.</div>
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
