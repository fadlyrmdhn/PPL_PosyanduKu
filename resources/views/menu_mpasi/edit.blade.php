@extends('layouts.app')

@section('title', 'Ubah Menu MPASI')

@section('content')
<h1>Ubah Menu MPASI</h1>

<div class="card">
    <form method="POST" action="{{ route('menu-mpasi.update', $menuMpasi) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('menu_mpasi._form', ['menu' => $menuMpasi])
        <div class="flex mt-1">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('menu-mpasi.show', $menuMpasi) }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
