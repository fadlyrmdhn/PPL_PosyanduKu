@extends('layouts.app')
@section('title', 'Ubah Kader')
@section('content')

<x-page-head title="Ubah Data Kader" subtitle="Perbarui informasi kader: {{ $kader->nama }}.">
    <a href="{{ route('kader.show', $kader) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card p-4">
    <form action="{{ route('kader.update', $kader) }}" method="POST" novalidate>
        @csrf @method('PUT')
        @include('kader._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('kader.show', $kader) }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
