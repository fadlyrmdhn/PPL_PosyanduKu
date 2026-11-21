@extends('layouts.app')
@section('title', 'Riwayat Pemberian Vitamin')
@section('content')
<x-page-head title="Riwayat Pemberian Vitamin" subtitle="Pilih balita untuk melihat riwayat vitamin.">
    @if ($balitaTerpilih)
        <a href="{{ route('pemberian-vitamin.create', ['balita' => $balitaTerpilih->id]) }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Catat Vitamin
        </a>
    @endif
</x-page-head>

<form method="GET" action="{{ route('pemberian-vitamin.index') }}" class="filter-bar mb-3">
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
        <i class="bi bi-capsule"></i>
        Riwayat Vitamin — <strong>{{ $balitaTerpilih->nama }}</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nama Vitamin</th><th>Tgl. Pemberian</th><th>Dosis</th><th>Dicatat Oleh</th><th>Keterangan</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($pemberian as $p)
                <tr>
                    <td><strong>{{ $p->nama_vitamin }}</strong></td>
                    <td>{{ $p->tanggal_pemberian->format('d-m-Y') }}</td>
                    <td>{{ $p->dosis ?? '-' }}</td>
                    <td class="text-muted small">{{ $p->pencatat->nama }}</td>
                    <td class="text-muted small">{{ $p->keterangan ?? '-' }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('pemberian-vitamin.show', $p) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('pemberian-vitamin.edit', $p) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('pemberian-vitamin.destroy', $p) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus catatan pemberian vitamin ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty"><i class="bi bi-capsule"></i>Belum ada catatan pemberian vitamin.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
