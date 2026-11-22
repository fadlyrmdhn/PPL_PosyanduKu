@extends('layouts.app')
@section('title', 'Imunisasi ' . $balita->nama)
@section('content')
@php $kelas = $balita->jenis_kelamin === 'L' ? 'boy' : 'girl'; @endphp

<x-page-head title="Imunisasi {{ $balita->nama }}">
    <a href="{{ route('anak.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

{{-- Jadwal berikutnya --}}
@if ($jadwalBerikutnya)
<div class="alert alert-info d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-calendar-check fs-5"></i>
    <div>
        <strong>Jadwal imunisasi berikutnya:</strong>
        {{ $jadwalBerikutnya->vaksin->nama_vaksin }}
        pada <strong>{{ $jadwalBerikutnya->tanggal_jadwal->format('d-m-Y') }}</strong>
    </div>
</div>
@endif

<div class="row g-3">
    {{-- Jadwal Imunisasi --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar2-heart"></i> Jadwal Imunisasi</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Vaksin</th><th>Tanggal Jadwal</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($jadwal as $j)
                        @php
                            $badgeClass = match($j->status) {
                                'selesai'   => 'success',
                                'terlewat'  => 'danger',
                                default     => 'warning text-dark',
                            };
                            $icon = match($j->status) {
                                'selesai'   => 'check-circle-fill',
                                'terlewat'  => 'exclamation-triangle-fill',
                                default     => 'clock-fill',
                            };
                        @endphp
                        <tr>
                            <td><strong>{{ $j->vaksin->nama_vaksin }}</strong></td>
                            <td>{{ $j->tanggal_jadwal->format('d-m-Y') }}</td>
                            <td><span class="badge bg-{{ $badgeClass }}"><i class="bi bi-{{ $icon }}"></i> {{ ucfirst($j->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty"><i class="bi bi-calendar2-heart"></i>Belum ada jadwal imunisasi.</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Imunisasi --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-syringe"></i> Riwayat Imunisasi</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Vaksin</th><th>Tgl. Pemberian</th><th>Dosis</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($imunisasi as $im)
                        <tr>
                            <td><strong>{{ $im->vaksin->nama_vaksin }}</strong></td>
                            <td>{{ $im->tanggal_pemberian->format('d-m-Y') }}</td>
                            <td>{{ $im->dosis_ke ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty"><i class="bi bi-syringe"></i>Belum ada catatan imunisasi.</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Vitamin --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-capsule"></i> Riwayat Vitamin</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Vitamin</th><th>Tgl. Pemberian</th><th>Dosis</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($vitamin as $v)
                        <tr>
                            <td><strong>{{ $v->nama_vitamin }}</strong></td>
                            <td>{{ $v->tanggal_pemberian->format('d-m-Y') }}</td>
                            <td>{{ $v->dosis ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty"><i class="bi bi-capsule"></i>Belum ada catatan vitamin.</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
