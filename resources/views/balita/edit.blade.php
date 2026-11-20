@extends('layouts.app')
@section('title', 'Ubah Balita')
@section('content')
<x-page-head title="Ubah Data Balita" :subtitle="$balita->nama" />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('balita.update', $balita) }}" method="POST">
        @csrf @method('PUT')
        @include('balita._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('balita.show', $balita) }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
