@extends('layouts.app')
@section('title', 'Ubah Jadwal')
@section('content')

<x-page-head title="Ubah Jadwal Kegiatan" subtitle="{{ $jadwal->nama_kegiatan }}">
    <a href="{{ route('jadwal.show', $jadwal) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card p-4">
    <form action="{{ route('jadwal.update', $jadwal) }}" method="POST" novalidate>
        @csrf @method('PUT')
        @include('jadwal._form')
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('jadwal.show', $jadwal) }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
