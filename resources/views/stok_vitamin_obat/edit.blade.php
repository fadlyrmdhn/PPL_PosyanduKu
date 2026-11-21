@extends('layouts.app')
@section('title', 'Ubah Stok')
@section('content')
<x-page-head title="Ubah Stok Vitamin/Obat" :subtitle="$stok->nama" />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('stok-vitamin-obat.update', $stok) }}" method="POST">
        @csrf @method('PUT')
        @include('stok_vitamin_obat._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('stok-vitamin-obat.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
