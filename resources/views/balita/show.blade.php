@extends('layouts.app')
@section('title', 'Detail Balita')
@section('content')
@php $kelas = $balita->jenis_kelamin === 'L' ? 'boy' : 'girl'; @endphp
<x-page-head title="Detail Balita">
    <a href="{{ route('balita.edit', $balita) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Ubah</a>
    <a href="{{ route('balita.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card mb-3"><div class="card-body p-4 d-flex align-items-center gap-3 flex-wrap">
    <span class="avatar lg {{ $kelas }}">{{ strtoupper(substr($balita->nama, 0, 1)) }}</span>
    <div>
        <h5 class="mb-1">{{ $balita->nama }}</h5>
        <span class="pk-badge {{ $kelas }}"><i class="bi bi-gender-{{ $kelas === 'boy' ? 'male' : 'female' }}"></i> {{ $kelas === 'boy' ? 'Laki-laki' : 'Perempuan' }}</span>
        <span class="pk-badge teal"><i class="bi bi-geo-alt"></i> {{ $balita->unitPosyandu->nama_pos }} ({{ $balita->unitPosyandu->rw_dusun }})</span>
    </div>
</div></div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100"><div class="card-header"><i class="bi bi-person-vcard"></i> Identitas</div><div class="card-body">
            <dl class="row mb-0 detail-list">
                <dt class="col-sm-5">NIK</dt><dd class="col-sm-7">{{ $balita->nik }}</dd>
                <dt class="col-sm-5">Tanggal lahir</dt><dd class="col-sm-7">{{ $balita->tanggal_lahir->format('d-m-Y') }}</dd>
                <dt class="col-sm-5">Unit posyandu</dt><dd class="col-sm-7">{{ $balita->unitPosyandu->nama_pos }}</dd>
            </dl>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100"><div class="card-header"><i class="bi bi-clipboard2-pulse"></i> Riwayat Kelahiran</div><div class="card-body">
            <dl class="row mb-0 detail-list">
                <dt class="col-sm-6">Berat lahir</dt><dd class="col-sm-6">{{ $balita->berat_lahir !== null ? $balita->berat_lahir.' kg' : '-' }}</dd>
                <dt class="col-sm-6">Panjang lahir</dt><dd class="col-sm-6">{{ $balita->panjang_lahir !== null ? $balita->panjang_lahir.' cm' : '-' }}</dd>
                <dt class="col-sm-6">Usia kehamilan</dt><dd class="col-sm-6">{{ $balita->usia_kehamilan !== null ? $balita->usia_kehamilan.' minggu' : '-' }}</dd>
                <dt class="col-sm-6">Penolong persalinan</dt><dd class="col-sm-6">{{ $balita->penolong_persalinan ?? '-' }}</dd>
            </dl>
        </div></div>
    </div>
    <div class="col-12">
        <div class="card"><div class="card-header"><i class="bi bi-people"></i> Orang Tua/Wali</div>
            <ul class="list-group list-group-flush">
                @forelse ($balita->orangTua as $o)
                    <li class="list-group-item d-flex align-items-center gap-3 py-3">
                        <span class="avatar teal">{{ strtoupper(substr($o->nama, 0, 1)) }}</span>
                        <div class="flex-grow-1">
                            <strong>{{ $o->nama }}</strong> <span class="pk-badge warm">{{ ucfirst($o->hubungan) }}</span>
                            <div class="text-muted small">NIK {{ $o->nik }} @if ($o->no_hp) &middot; {{ $o->no_hp }} @endif</div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item"><div class="empty py-3"><i class="bi bi-people"></i>Belum ada orang tua/wali yang dikaitkan.</div></li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
