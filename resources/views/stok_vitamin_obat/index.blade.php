@extends('layouts.app')
@section('title', 'Stok Vitamin/Obat')
@section('content')
<x-page-head title="Stok Vitamin dan Obat" subtitle="Pantau persediaan, tanggal kedaluwarsa, dan catat penambahan atau pengurangan stok.">
    <a href="{{ route('stok-vitamin-obat.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Stok</a>
</x-page-head>

@if ($errors->any())
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('stok-vitamin-obat.index', ['peringatan' => 'menipis']) }}" class="card quick">
            <span class="ico warm"><i class="bi bi-exclamation-diamond"></i></span>
            <div><strong class="fs-5">{{ $ringkasan['menipis'] }}</strong><div class="text-muted small">Stok menipis</div></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('stok-vitamin-obat.index', ['peringatan' => 'mendekati']) }}" class="card quick">
            <span class="ico"><i class="bi bi-hourglass-split"></i></span>
            <div><strong class="fs-5">{{ $ringkasan['mendekati'] }}</strong><div class="text-muted small">Mendekati kedaluwarsa ({{ \App\Models\StokVitaminObat::BATAS_HARI_MENDEKATI_KEDALUWARSA }} hari)</div></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('stok-vitamin-obat.index', ['peringatan' => 'kedaluwarsa']) }}" class="card quick">
            <span class="ico pink"><i class="bi bi-x-octagon"></i></span>
            <div><strong class="fs-5">{{ $ringkasan['kedaluwarsa'] }}</strong><div class="text-muted small">Sudah kedaluwarsa</div></div>
        </a>
    </div>
</div>

<form method="GET" class="filter-bar row g-2">
    <div class="col-md-5 search-wrap">
        <i class="bi bi-search" style="left:1.6rem"></i>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama vitamin atau obat">
    </div>
    <div class="col-md-2">
        <select name="jenis" class="form-select">
            <option value="">Semua jenis</option>
            @foreach (\App\Models\StokVitaminObat::JENIS as $opsi)
                <option value="{{ $opsi }}" @selected(request('jenis') === $opsi)>{{ ucfirst($opsi) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="peringatan" class="form-select">
            <option value="">Semua kondisi</option>
            <option value="menipis" @selected($peringatan === 'menipis')>Stok menipis</option>
            <option value="mendekati" @selected($peringatan === 'mendekati')>Mendekati kedaluwarsa</option>
            <option value="kedaluwarsa" @selected($peringatan === 'kedaluwarsa')>Sudah kedaluwarsa</option>
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-success"><i class="bi bi-funnel"></i> Cari</button>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Nama</th><th>Jumlah</th><th>Kedaluwarsa</th><th>Status</th><th>Tambah/Kurangi</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($daftarStok as $s)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar teal"><i class="bi bi-capsule"></i></span>
                            <div><strong>{{ $s->nama }}</strong><div class="text-muted small">{{ ucfirst($s->jenis) }}</div></div>
                        </div>
                    </td>
                    <td class="text-nowrap">{{ number_format($s->jumlah, 0, ',', '.') }} {{ $s->satuan }}<div class="text-muted small">Minimum {{ number_format($s->stok_minimum, 0, ',', '.') }}</div></td>
                    <td class="text-nowrap">{{ $s->tanggal_kedaluwarsa->format('d-m-Y') }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @forelse ($s->peringatan() as $label => $tingkat)
                                <span class="pk-badge {{ $tingkat === 'red' ? 'bg-danger-subtle text-danger-emphasis' : 'warm' }}">{{ $label }}</span>
                            @empty
                                <span class="pk-badge teal"><i class="bi bi-check-circle"></i> Aman</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('stok-vitamin-obat.penyesuaian', $s) }}" method="POST" class="mb-0">
                            @csrf
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                            <input type="hidden" name="peringatan" value="{{ $peringatan }}">
                            <input type="hidden" name="page" value="{{ $daftarStok->currentPage() }}">
                            <div class="input-group input-group-sm flex-nowrap" style="min-width:11rem">
                                <input type="number" name="jumlah" min="1" required class="form-control" placeholder="Jumlah" aria-label="Jumlah penyesuaian">
                                <button type="submit" name="tipe" value="tambah" class="btn btn-success" title="Tambah stok"><i class="bi bi-plus-lg"></i></button>
                                <button type="submit" name="tipe" value="kurang" class="btn btn-outline-danger" title="Kurangi stok"><i class="bi bi-dash-lg"></i></button>
                            </div>
                        </form>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('stok-vitamin-obat.edit', $s) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('stok-vitamin-obat.destroy', $s) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus data stok ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty"><i class="bi bi-capsule"></i>Belum ada data stok.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $daftarStok->links() }}</div>
@endsection
