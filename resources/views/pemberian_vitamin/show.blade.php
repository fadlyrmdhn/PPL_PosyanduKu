@extends('layouts.app')
@section('title', 'Detail Pemberian Vitamin')
@section('content')
<x-page-head title="Detail Pemberian Vitamin">
    <a href="{{ route('pemberian-vitamin.edit', $pemberianVitamin) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Ubah</a>
    <a href="{{ route('pemberian-vitamin.index', ['balita' => $pemberianVitamin->id_balita]) }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <dl class="row detail-list mb-0">
            <dt class="col-sm-4">Balita</dt><dd class="col-sm-8">{{ $pemberianVitamin->balita->nama }}</dd>
            <dt class="col-sm-4">Nama Vitamin</dt><dd class="col-sm-8">{{ $pemberianVitamin->nama_vitamin }}</dd>
            <dt class="col-sm-4">Tanggal Pemberian</dt><dd class="col-sm-8">{{ $pemberianVitamin->tanggal_pemberian->format('d-m-Y') }}</dd>
            <dt class="col-sm-4">Dosis</dt><dd class="col-sm-8">{{ $pemberianVitamin->dosis ?? '-' }}</dd>
            <dt class="col-sm-4">Dicatat Oleh</dt><dd class="col-sm-8">{{ $pemberianVitamin->pencatat->nama }}</dd>
            <dt class="col-sm-4">Keterangan</dt><dd class="col-sm-8">{{ $pemberianVitamin->keterangan ?? '-' }}</dd>
        </dl>
    </div>
</div>
@endsection
