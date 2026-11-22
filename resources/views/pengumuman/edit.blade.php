@extends('layouts.app')
@section('title', 'Ubah Pengumuman')
@section('content')
<x-page-head title="Ubah Pengumuman" :subtitle="$pengumuman->judul" />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('pengumuman.update', $pengumuman) }}" method="POST">
        @csrf @method('PUT')
        @include('pengumuman._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('pengumuman.show', $pengumuman) }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
