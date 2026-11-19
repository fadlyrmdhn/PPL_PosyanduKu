@extends('layouts.app')

@section('title', 'Tambah Pengumuman - PosyanduKu')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">Tambah Pengumuman</h1>

    @include('pengumuman._form', [
        'action' => route('pengumuman.store'),
        'method' => 'POST',
    ])
@endsection
