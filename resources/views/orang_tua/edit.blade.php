@extends('layouts.app')
@section('title', 'Ubah Orang Tua/Wali')
@section('content')
<x-page-head title="Ubah Orang Tua/Wali" :subtitle="$orangTua->nama" />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('orang-tua.update', $orangTua) }}" method="POST">
        @csrf @method('PUT')
        @include('orang_tua._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('orang-tua.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
