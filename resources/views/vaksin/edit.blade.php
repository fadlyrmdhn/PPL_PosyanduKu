@extends('layouts.app')
@section('title', 'Ubah Vaksin')
@section('content')
<x-page-head title="Ubah Vaksin">
    <a href="{{ route('vaksin.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('vaksin.update', $vaksin) }}" method="POST">
            @csrf @method('PUT')
            @include('vaksin._form', ['vaksin' => $vaksin])
            <div class="mt-4">
                <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
