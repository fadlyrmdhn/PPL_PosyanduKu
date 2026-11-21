@extends('layouts.app')
@section('title', 'Tambah Jadwal Imunisasi')
@section('content')
<x-page-head title="Tambah Jadwal Imunisasi">
    <a href="{{ route('jadwal-imunisasi.index', $balitaTerpilih ? ['balita' => $balitaTerpilih->id] : []) }}" class="btn btn-light border">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('jadwal-imunisasi.store') }}" method="POST">
            @csrf
            @include('jadwal_imunisasi._form', ['balita' => $balita, 'vaksin' => $vaksin, 'balitaTerpilih' => $balitaTerpilih])
            <div class="mt-4">
                <button class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
