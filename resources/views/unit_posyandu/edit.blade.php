@extends('layouts.app')
@section('title', 'Ubah Unit Posyandu')
@section('content')
<x-page-head title="Ubah Unit Posyandu" :subtitle="$unit->nama_pos" />
<div class="card"><div class="card-body p-4">
    <form action="{{ route('unit-posyandu.update', $unit) }}" method="POST">
        @csrf @method('PUT')
        @include('unit_posyandu._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('unit-posyandu.index') }}" class="btn btn-light border">Batal</a>
        </div>
    </form>
</div></div>
@endsection
