@extends('layouts.app')

@section('title', 'Ubah Pengukuran')

@section('content')
<h1>Ubah Data Pengukuran</h1>

<div class="card">
    <form method="POST" action="{{ route('pengukuran.update', $pengukuran) }}">
        @csrf @method('PUT')
        @include('pengukuran._form', ['pengukuran' => $pengukuran])
        <div class="flex mt-1">
            <button type="submit" class="btn btn-primary">Simpan & Hitung Ulang Status Gizi</button>
            <a href="{{ route('pengukuran.show', $pengukuran) }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
