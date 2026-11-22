@extends('layouts.app')
@section('title', 'Ubah Jadwal Imunisasi')
@section('content')
<x-page-head title="Ubah Jadwal Imunisasi">
    <a href="{{ route('jadwal-imunisasi.index', ['balita' => $jadwalImunisasi->id_balita]) }}" class="btn btn-light border">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('jadwal-imunisasi.update', $jadwalImunisasi) }}" method="POST">
            @csrf @method('PUT')
            @include('jadwal_imunisasi._form', [
                'jadwalImunisasi' => $jadwalImunisasi,
                'balita'          => collect([$jadwalImunisasi->balita]),
                'vaksin'          => $vaksin,
            ])
            <div class="mt-4">
                <button class="btn btn-success"><i class="bi bi-check-lg"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
