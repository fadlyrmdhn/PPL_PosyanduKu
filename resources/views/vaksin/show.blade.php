@extends('layouts.app')
@section('title', 'Detail Vaksin')
@section('content')
<x-page-head title="Detail Vaksin">
    <a href="{{ route('vaksin.edit', $vaksin) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Ubah</a>
    <a href="{{ route('vaksin.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <dl class="row detail-list mb-0">
            <dt class="col-sm-4">Nama Vaksin</dt><dd class="col-sm-8">{{ $vaksin->nama_vaksin }}</dd>
            <dt class="col-sm-4">Usia Pemberian</dt><dd class="col-sm-8">{{ $vaksin->usia_pemberian_bulan }} bulan</dd>
            <dt class="col-sm-4">Dosis Ke-</dt><dd class="col-sm-8">{{ $vaksin->dosis_ke }}</dd>
            <dt class="col-sm-4">Jumlah Dosis</dt><dd class="col-sm-8">{{ $vaksin->jumlah_dosis ?? '-' }}</dd>
            <dt class="col-sm-4">Keterangan</dt><dd class="col-sm-8">{{ $vaksin->keterangan ?? '-' }}</dd>
        </dl>
    </div>
</div>
@endsection
