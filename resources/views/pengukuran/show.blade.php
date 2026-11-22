@extends('layouts.app')

@section('title', 'Detail Pengukuran')

@section('content')
@php
    $balita = $pengukuran->balita;
    $sg     = $pengukuran->statusGizi;
@endphp

<div class="flex mb-1">
    <h1>Detail Pengukuran — {{ $balita->nama }}</h1>
    <a href="{{ route('pengukuran.edit', $pengukuran) }}" class="btn btn-primary btn-sm">Ubah</a>
    <form method="POST" action="{{ route('pengukuran.destroy', $pengukuran) }}"
          onsubmit="return confirm('Hapus data pengukuran ini?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger btn-sm">Hapus</button>
    </form>
</div>

{{-- Data Balita --}}
<div class="card">
    <h2>Data Balita</h2>
    <table style="width:auto">
        <tr><th>Nama</th><td>{{ $balita->nama }}</td></tr>
        <tr><th>Jenis Kelamin</th><td>{{ $balita->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
        <tr><th>Tanggal Lahir</th><td>{{ $balita->tanggal_lahir->format('d M Y') }}</td></tr>
    </table>
</div>

{{-- Data Pengukuran --}}
<div class="card">
    <h2>Hasil Pengukuran</h2>
    <table style="width:auto">
        <tr><th>Tanggal Ukur</th><td>{{ $pengukuran->tanggal_ukur->format('d M Y') }}</td></tr>
        <tr><th>Usia Saat Ukur</th><td><strong>{{ $pengukuran->usia_bulan }} bulan</strong></td></tr>
        <tr><th>Berat Badan</th><td>{{ number_format($pengukuran->berat_badan, 2) }} kg</td></tr>
        <tr><th>Tinggi/Panjang Badan</th><td>{{ number_format($pengukuran->tinggi_badan, 2) }} cm</td></tr>
        <tr><th>Cara Ukur</th><td>{{ ucfirst($pengukuran->cara_ukur) }}</td></tr>
        @if($pengukuran->lingkar_kepala)
        <tr><th>Lingkar Kepala</th><td>{{ number_format($pengukuran->lingkar_kepala, 1) }} cm</td></tr>
        @endif
        @if($pengukuran->lila)
        <tr><th>LiLA</th><td>{{ number_format($pengukuran->lila, 1) }} cm</td></tr>
        @endif
        <tr><th>Dicatat oleh</th><td>{{ $pengukuran->user?->name }}</td></tr>
    </table>
</div>

{{-- Status Gizi --}}
<div class="card">
    <h2>Status Gizi Otomatis (WHO 2006)</h2>
    @if($sg)
        <table>
            <thead>
                <tr><th>Indikator</th><th>Z-Score</th><th>Kategori</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>BB/U (Berat Badan per Umur)</td>
                    <td>{{ $sg->zscore_bb_u !== null ? number_format($sg->zscore_bb_u, 3) : '-' }}</td>
                    <td>
                        @if($sg->kategori_bb_u)
                            @php $w = \App\Models\StatusGizi::warnaBadge($sg->kategori_bb_u); @endphp
                            <span class="badge badge-{{ $w }}">{{ $sg->kategori_bb_u }}</span>
                        @else - @endif
                    </td>
                </tr>
                <tr>
                    <td>TB/U (Tinggi Badan per Umur)</td>
                    <td>{{ $sg->zscore_tb_u !== null ? number_format($sg->zscore_tb_u, 3) : '-' }}</td>
                    <td>
                        @if($sg->kategori_tb_u)
                            @php $w = \App\Models\StatusGizi::warnaBadge($sg->kategori_tb_u); @endphp
                            <span class="badge badge-{{ $w }}">{{ $sg->kategori_tb_u }}</span>
                        @else - @endif
                    </td>
                </tr>
                <tr>
                    <td>BB/TB (Berat Badan per Tinggi Badan)</td>
                    <td>{{ $sg->zscore_bb_tb !== null ? number_format($sg->zscore_bb_tb, 3) : '-' }}</td>
                    <td>
                        @if($sg->kategori_bb_tb)
                            @php $w = \App\Models\StatusGizi::warnaBadge($sg->kategori_bb_tb); @endphp
                            <span class="badge badge-{{ $w }}">{{ $sg->kategori_bb_tb }}</span>
                        @else - @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="text-muted mt-1" style="font-size:.8rem">
            Ambang batas WHO 2006 — Normal: z-score ≥ −2 s.d. ≤ 2 | Kurang/Pendek/Kurus: &lt; −2 | Buruk/Sangat Pendek/Sangat Kurus: &lt; −3
        </p>
    @else
        <p class="text-muted">Status gizi belum tersedia (data referensi tidak ditemukan untuk usia/tinggi ini).</p>
    @endif
</div>

<div class="flex">
    <a href="{{ route('pengukuran.index', ['balita_id' => $balita->id]) }}" class="btn btn-secondary">
        ← Riwayat {{ $balita->nama }}
    </a>
</div>
@endsection
