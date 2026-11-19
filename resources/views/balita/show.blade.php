@extends('layouts.app')
@section('title', 'Detail Balita')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Detail Balita</h4>
    <a href="{{ route('balita.index') }}" class="btn btn-secondary">Kembali</a>
</div>
<div class="card"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">Nama</dt><dd class="col-sm-8">{{ $balita->nama }}</dd>
        <dt class="col-sm-4">NIK</dt><dd class="col-sm-8">{{ $balita->nik ?? '-' }}</dd>
        <dt class="col-sm-4">Tanggal lahir</dt><dd class="col-sm-8">{{ $balita->tanggal_lahir->format('d-m-Y') }}</dd>
        <dt class="col-sm-4">Jenis kelamin</dt><dd class="col-sm-8">{{ $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
        <dt class="col-sm-4">Orang tua/wali</dt><dd class="col-sm-8">{{ $balita->orangTua->nama }}</dd>
        <dt class="col-sm-4">Posyandu</dt><dd class="col-sm-8">{{ $balita->unitPosyandu->nama_pos }} ({{ $balita->unitPosyandu->rw_dusun }})</dd>
        <dt class="col-sm-4">Berat lahir</dt><dd class="col-sm-8">{{ $balita->berat_lahir ? $balita->berat_lahir.' kg' : '-' }}</dd>
        <dt class="col-sm-4">Panjang lahir</dt><dd class="col-sm-8">{{ $balita->panjang_lahir ? $balita->panjang_lahir.' cm' : '-' }}</dd>
        <dt class="col-sm-4">Usia kehamilan</dt><dd class="col-sm-8">{{ $balita->usia_kehamilan ? $balita->usia_kehamilan.' minggu' : '-' }}</dd>
        <dt class="col-sm-4">Penolong persalinan</dt><dd class="col-sm-8">{{ $balita->penolong_persalinan ?? '-' }}</dd>
    </dl>
</div></div>
@endsection