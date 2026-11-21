@extends('layouts.app')
@section('title', 'Master Vaksin')
@section('content')
<x-page-head title="Master Vaksin" subtitle="Daftar jenis vaksin imunisasi beserta usia pemberian.">
    <a href="{{ route('vaksin.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Vaksin</a>
</x-page-head>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Vaksin</th>
                    <th>Usia Pemberian</th>
                    <th>Dosis Ke-</th>
                    <th>Jumlah Dosis</th>
                    <th>Keterangan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($vaksin as $v)
                <tr>
                    <td><strong>{{ $v->nama_vaksin }}</strong></td>
                    <td>{{ $v->usia_pemberian_bulan }} bulan</td>
                    <td>{{ $v->dosis_ke }}</td>
                    <td>{{ $v->jumlah_dosis ?? '-' }}</td>
                    <td class="text-muted small">{{ $v->keterangan ?? '-' }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('vaksin.edit', $v) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('vaksin.destroy', $v) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus vaksin {{ $v->nama_vaksin }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty"><i class="bi bi-capsule"></i>Belum ada data vaksin.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
