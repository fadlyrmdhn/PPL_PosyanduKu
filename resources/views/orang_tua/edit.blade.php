@extends('layouts.app')
@section('title', 'Ubah Orang Tua/Wali')
@section('content')
<h4 class="mb-3">Ubah Orang Tua/Wali</h4>
<div class="card"><div class="card-body">
    <form action="{{ route('orang-tua.update', $orangTua) }}" method="POST">
        @csrf @method('PUT')
        @include('orang_tua._form')
        <div class="mt-4">
            <button class="btn btn-primary">Perbarui</button>
            <a href="{{ route('orang-tua.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection