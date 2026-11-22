@extends('layouts.app')
@section('title', 'Tambah Balita')
@section('content')
<x-page-head title="Tambah Balita" subtitle="Lengkapi identitas, riwayat kelahiran, dan orang tua/wali." />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('balita.store') }}" method="POST">
        @csrf
        @include('balita._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('balita.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
