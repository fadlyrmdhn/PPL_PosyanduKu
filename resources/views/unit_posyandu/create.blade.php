@extends('layouts.app')
@section('title', 'Tambah Unit Posyandu')
@section('content')
<x-page-head title="Tambah Unit Posyandu" subtitle="Isi data pos pelayanan baru." />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('unit-posyandu.store') }}" method="POST">
        @csrf
        @include('unit_posyandu._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('unit-posyandu.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
