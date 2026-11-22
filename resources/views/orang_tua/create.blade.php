@extends('layouts.app')
@section('title', 'Tambah Orang Tua/Wali')
@section('content')
<x-page-head title="Tambah Orang Tua/Wali" subtitle="Isi data dan kaitkan ke balita." />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('orang-tua.store') }}" method="POST">
        @csrf
        @include('orang_tua._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('orang-tua.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
