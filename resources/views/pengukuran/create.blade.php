@extends('layouts.app')

@section('title', 'Catat Pengukuran')

@section('content')
<h1>Catat Pengukuran Baru</h1>

<div class="card">
    <form method="POST" action="{{ route('pengukuran.store') }}">
        @csrf
        @include('pengukuran._form')
        <div class="flex mt-1">
            <button type="submit" class="btn btn-primary">Simpan & Hitung Status Gizi</button>
            <a href="{{ route('pengukuran.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
