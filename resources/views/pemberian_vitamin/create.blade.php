@extends('layouts.app')
@section('title', 'Catat Pemberian Vitamin')
@section('content')
<x-page-head title="Catat Pemberian Vitamin">
    <a href="{{ route('pemberian-vitamin.index', $balitaTerpilih ? ['balita' => $balitaTerpilih->id] : []) }}" class="btn btn-light border">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('pemberian-vitamin.store') }}" method="POST">
            @csrf
            @include('pemberian_vitamin._form', ['balita' => $balita, 'balitaTerpilih' => $balitaTerpilih])
            <div class="mt-4">
                <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
