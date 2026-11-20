@extends('layouts.app')
@section('title', $judul)
@section('content')
@php $staf = in_array(auth()->user()->role, ['admin', 'kader']); @endphp
<div class="hero mb-4">
    <div style="position:relative;z-index:1">
        <span class="pk-badge" style="background:rgba(255,255,255,.2);color:#fff"><i class="bi bi-stars"></i> {{ $judul }}</span>
        <h3 class="mt-3 mb-1">Halo, {{ auth()->user()->nama }}!</h3>
        <p class="mb-0 opacity-75">Selamat datang di PosyanduKu. Ringkasan dashboard akan hadir di Sprint 2.</p>
    </div>
</div>

@if ($staf)
    <div class="row g-3">
        <div class="col-md-6">
            <a href="{{ route('balita.index') }}" class="card quick">
                <span class="ico"><i class="bi bi-emoji-smile"></i></span>
                <div><strong>Data Balita</strong><div class="text-muted small">Lihat dan kelola data balita</div></div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('orang-tua.index') }}" class="card quick">
                <span class="ico pink"><i class="bi bi-people"></i></span>
                <div><strong>Orang Tua/Wali</strong><div class="text-muted small">Kelola data orang tua dan wali</div></div>
            </a>
        </div>
    </div>
@else
    <div class="card"><div class="empty">
        <i class="bi bi-heart-pulse"></i>
        Informasi anak Anda akan tampil di sini pada pembaruan berikutnya.
    </div></div>
@endif
@endsection
