@extends('layouts.app')
@section('title', 'Tambah Orang Tua/Wali')
@section('content')
<h4 class="mb-3">Tambah Orang Tua/Wali</h4>
<div class="card"><div class="card-body">
    <form action="{{ route('orang-tua.store') }}" method="POST">
        @csrf
        @include('orang_tua._form')
        <div class="mt-4">
            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('orang-tua.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection