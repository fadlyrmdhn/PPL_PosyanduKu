@extends('layouts.app')

@section('title', 'Tambah Menu MPASI')

@section('content')
<h1>Tambah Menu MPASI</h1>

<div class="card">
    <form method="POST" action="{{ route('menu-mpasi.store') }}" enctype="multipart/form-data">
        @csrf
        @include('menu_mpasi._form')
        <div class="flex mt-1">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('menu-mpasi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
