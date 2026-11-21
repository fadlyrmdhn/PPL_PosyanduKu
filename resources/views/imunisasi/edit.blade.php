@extends('layouts.app')
@section('title', 'Ubah Catatan Imunisasi')
@section('content')
<x-page-head title="Ubah Catatan Imunisasi">
    <a href="{{ route('imunisasi.index', ['balita' => $imunisasi->id_balita]) }}" class="btn btn-light border">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('imunisasi.update', $imunisasi) }}" method="POST">
            @csrf @method('PUT')
            @include('imunisasi._form', [
                'imunisasi' => $imunisasi,
                'balita'    => collect([$imunisasi->balita]),
                'vaksin'    => $vaksin,
            ])
            <div class="mt-4">
                <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
