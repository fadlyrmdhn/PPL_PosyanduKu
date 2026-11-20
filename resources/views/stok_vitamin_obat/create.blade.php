@extends('layouts.app')
@section('title', 'Tambah Stok')
@section('content')
<x-page-head title="Tambah Stok Vitamin/Obat" subtitle="Catat persediaan vitamin atau obat baru." />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('stok-vitamin-obat.store') }}" method="POST">
        @csrf
        @include('stok_vitamin_obat._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('stok-vitamin-obat.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
