@extends('layouts.app')
@section('title', 'Detail Pengumuman')
@section('content')
<x-page-head title="Detail Pengumuman">
    @if ($isAdmin)
        @unless ($pengumuman->isDipublikasikan())
            <form action="{{ route('pengumuman.publikasikan', $pengumuman) }}" method="POST" class="mb-0">
                @csrf
                <button class="btn btn-outline-primary"><i class="bi bi-send"></i> Publikasikan</button>
            </form>
        @endunless
        <a href="{{ route('pengumuman.edit', $pengumuman) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Ubah</a>
    @endif
    <a href="{{ route('pengumuman.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card mb-3"><div class="card-body p-4 d-flex align-items-center gap-3 flex-wrap">
    <span class="avatar lg teal"><i class="bi bi-megaphone-fill"></i></span>
    <div>
        <h5 class="mb-2">{{ $pengumuman->judul }}</h5>
        <span class="pk-badge teal"><i class="bi bi-calendar-event"></i> {{ $pengumuman->tanggalTerbitLabel() }}</span>
        @if ($isAdmin)
            @if ($pengumuman->isDipublikasikan())
                <span class="pk-badge teal"><i class="bi bi-check-circle"></i> Dipublikasikan</span>
            @else
                <span class="pk-badge warm"><i class="bi bi-pencil-square"></i> Draft</span>
            @endif
            <span class="pk-badge warm"><i class="bi bi-person"></i> {{ $pengumuman->pembuat?->nama ?? '-' }}</span>
        @endif
    </div>
</div></div>

<div class="card">
    <div class="card-header"><i class="bi bi-card-text"></i> Isi Pengumuman</div>
    <div class="card-body p-4">{!! nl2br(e($pengumuman->isi)) !!}</div>
</div>
@endsection
