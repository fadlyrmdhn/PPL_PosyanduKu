@extends('layouts.app')
@section('title', 'Ubah Balita')
@section('content')
<h4 class="mb-3">Ubah Data Balita</h4>
<div class="card"><div class="card-body">
    <form action="{{ route('balita.update', $balita) }}" method="POST">
        @csrf @method('PUT')
        @include('balita._form')
        <div class="mt-4">
            <button class="btn btn-primary">Perbarui</button>
            <a href="{{ route('balita.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection