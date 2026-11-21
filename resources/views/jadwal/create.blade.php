@extends('layouts.app')
@section('title', 'Tambah Jadwal')
@section('content')

<x-page-head title="Tambah Jadwal Kegiatan" subtitle="Rencanakan jadwal kegiatan posyandu baru.">
    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card p-4">
    <form action="{{ route('jadwal.store') }}" method="POST" novalidate>
        @csrf
        @include('jadwal._form')
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
