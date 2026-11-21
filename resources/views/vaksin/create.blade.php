@extends('layouts.app')
@section('title', 'Tambah Vaksin')
@section('content')
<x-page-head title="Tambah Vaksin">
    <a href="{{ route('vaksin.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('vaksin.store') }}" method="POST">
            @csrf
            @include('vaksin._form')
            <div class="mt-4">
                <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
