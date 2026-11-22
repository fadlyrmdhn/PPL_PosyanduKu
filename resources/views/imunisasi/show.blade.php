@extends('layouts.app')
@section('title', 'Detail Imunisasi')
@section('content')
<x-page-head title="Detail Catatan Imunisasi">
    <a href="{{ route('imunisasi.edit', $imunisasi) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Ubah</a>
    <a href="{{ route('imunisasi.index', ['balita' => $imunisasi->id_balita]) }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="card">
    <div class="card-body p-4">
        <dl class="row detail-list mb-0">
            <dt class="col-sm-4">Balita</dt><dd class="col-sm-8">{{ $imunisasi->balita->nama }}</dd>
            <dt class="col-sm-4">Vaksin</dt><dd class="col-sm-8">{{ $imunisasi->vaksin->nama_vaksin }}</dd>
            <dt class="col-sm-4">Tanggal Pemberian</dt><dd class="col-sm-8">{{ $imunisasi->tanggal_pemberian->format('d-m-Y') }}</dd>
            <dt class="col-sm-4">Dosis Ke-</dt><dd class="col-sm-8">{{ $imunisasi->dosis_ke ?? '-' }}</dd>
            <dt class="col-sm-4">Dicatat Oleh</dt><dd class="col-sm-8">{{ $imunisasi->pencatat->nama }}</dd>
            <dt class="col-sm-4">Keterangan</dt><dd class="col-sm-8">{{ $imunisasi->keterangan ?? '-' }}</dd>
        </dl>
    </div>
</div>
@endsection
