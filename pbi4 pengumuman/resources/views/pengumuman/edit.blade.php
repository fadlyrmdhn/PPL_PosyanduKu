@extends('layouts.app')

@section('title', 'Ubah Pengumuman - PosyanduKu')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">Ubah Pengumuman</h1>

    @include('pengumuman._form', [
        'action' => route('pengumuman.update', $pengumuman),
        'method' => 'PUT',
    ])
@endsection
