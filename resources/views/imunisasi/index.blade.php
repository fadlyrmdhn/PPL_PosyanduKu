@extends('layouts.app')
@section('title', 'Riwayat Imunisasi')
@section('content')
<x-page-head title="Riwayat Imunisasi" subtitle="Pilih balita untuk melihat riwayat imunisasi.">
    @if ($balitaTerpilih)
        <a href="{{ route('imunisasi.create', ['balita' => $balitaTerpilih->id]) }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Catat Imunisasi
        </a>
    @endif
</x-page-head>

{{-- Pemilih balita --}}
<form method="GET" action="{{ route('imunisasi.index') }}" class="filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-6">
            <label class="form-label small mb-1">Pilih Balita</label>
            <select name="balita" class="form-select" onchange="this.form.submit()">
                <option value="">-- Pilih balita --</option>
                @foreach ($balita as $b)
                    <option value="{{ $b->id }}" @selected($balitaTerpilih?->id == $b->id)>{{ $b->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

@if ($balitaTerpilih)
<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-syringe"></i>
        Riwayat Imunisasi — <strong>{{ $balitaTerpilih->nama }}</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Vaksin</th><th>Tgl. Pemberian</th><th>Dosis Ke-</th><th>Dicatat Oleh</th><th>Keterangan</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($imunisasi as $im)
                <tr>
                    <td><strong>{{ $im->vaksin->nama_vaksin }}</strong></td>
                    <td>{{ $im->tanggal_pemberian->format('d-m-Y') }}</td>
                    <td>{{ $im->dosis_ke ?? '-' }}</td>
                    <td class="text-muted small">{{ $im->pencatat->nama }}</td>
                    <td class="text-muted small">{{ $im->keterangan ?? '-' }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('imunisasi.show', $im) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('imunisasi.edit', $im) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('imunisasi.destroy', $im) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus catatan imunisasi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty"><i class="bi bi-syringe"></i>Belum ada catatan imunisasi.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
