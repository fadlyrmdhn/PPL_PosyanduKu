@extends('layouts.app')
@section('title', 'Ubah Catatan Vitamin')
@section('content')
<x-page-head title="Ubah Catatan Pemberian Vitamin">
    <a href="{{ route('pemberian-vitamin.index', ['balita' => $pemberianVitamin->id_balita]) }}" class="btn btn-light border">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('pemberian-vitamin.update', $pemberianVitamin) }}" method="POST">
            @csrf @method('PUT')
            @include('pemberian_vitamin._form', [
                'pemberianVitamin' => $pemberianVitamin,
                'balita'           => collect([$pemberianVitamin->balita]),
            ])
            <div class="mt-4">
                <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
