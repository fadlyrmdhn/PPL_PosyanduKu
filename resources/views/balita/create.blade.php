@extends('layouts.app')
@section('title', 'Tambah Balita')
@section('content')
<h4 class="mb-3">Tambah Balita</h4>
<div class="card"><div class="card-body">
    <form action="{{ route('balita.store') }}" method="POST">
        @csrf
        @include('balita._form')
        <div class="mt-4">
            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('balita.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection