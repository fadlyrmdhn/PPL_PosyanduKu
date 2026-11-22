@extends('layouts.app')
@section('title', 'Imunisasi Anak')
@section('content')
<x-page-head title="Imunisasi Anak" subtitle="Pilih anak untuk melihat riwayat imunisasi dan jadwal."/>

@if ($balitaList->isEmpty())
    <div class="card">
        <div class="card-body">
            <div class="empty"><i class="bi bi-emoji-smile"></i>Belum ada data anak yang terhubung ke akun Anda.</div>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach ($balitaList as $b)
            @php $kelas = $b->jenis_kelamin === 'L' ? 'boy' : 'girl'; @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar lg {{ $kelas }}">{{ strtoupper(substr($b->nama, 0, 1)) }}</span>
                        <div>
                            <strong>{{ $b->nama }}</strong>
                            <div class="text-muted small">{{ $b->tanggal_lahir->format('d-m-Y') }}</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('anak.imunisasi', $b) }}" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-syringe"></i> Lihat Imunisasi
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
