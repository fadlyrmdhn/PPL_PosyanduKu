@extends('layouts.app')

@section('title', $menuMpasi->nama_menu)

@section('content')
<div class="flex mb-1">
    <h1>{{ $menuMpasi->nama_menu }}</h1>
    <a href="{{ route('menu-mpasi.edit', $menuMpasi) }}" class="btn btn-primary btn-sm">Ubah</a>
    <form method="POST" action="{{ route('menu-mpasi.destroy', $menuMpasi) }}"
          onsubmit="return confirm('Hapus menu ini?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger btn-sm">Hapus</button>
    </form>
</div>

<div class="card">
    <p class="text-muted mb-1">Rentang Usia: <strong>{{ $menuMpasi->usia_min_bulan }}–{{ $menuMpasi->usia_max_bulan }} bulan</strong></p>

    @if($menuMpasi->gambar)
        <img src="{{ Storage::url($menuMpasi->gambar) }}" alt="Gambar"
             style="max-height:220px;border-radius:8px;margin-bottom:1rem">
    @endif

    <h2>Bahan</h2>
    <p style="white-space:pre-line">{{ $menuMpasi->bahan }}</p>

    <h2 style="margin-top:1rem">Cara Pembuatan</h2>
    <p style="white-space:pre-line">{{ $menuMpasi->cara_pembuatan }}</p>

    @if($menuMpasi->kandungan_gizi)
        <h2 style="margin-top:1rem">Kandungan Gizi</h2>
        <p style="white-space:pre-line">{{ $menuMpasi->kandungan_gizi }}</p>
    @endif
</div>

<a href="{{ route('menu-mpasi.index') }}" class="btn btn-secondary">← Kembali ke Daftar</a>
@endsection
