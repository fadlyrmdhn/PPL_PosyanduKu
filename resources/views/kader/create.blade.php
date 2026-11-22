@extends('layouts.app')
@section('title', 'Tambah Kader')
@section('content')

<x-page-head title="Tambah Kader" subtitle="Daftarkan kader baru ke dalam sistem posyandu.">
    <a href="{{ route('kader.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card p-4">
    <form action="{{ route('kader.store') }}" method="POST" novalidate>
        @csrf
        @include('kader._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('kader.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
