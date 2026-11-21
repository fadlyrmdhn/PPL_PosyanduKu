@extends('layouts.app')
@section('title', 'Tambah Pengumuman')
@section('content')
<x-page-head title="Tambah Pengumuman" subtitle="Tulis informasi kegiatan untuk kader dan orang tua/wali." />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('pengumuman.store') }}" method="POST">
        @csrf
        @include('pengumuman._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('pengumuman.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
