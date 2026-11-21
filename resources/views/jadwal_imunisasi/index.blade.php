@extends('layouts.app')
@section('title', 'Jadwal Imunisasi')
@section('content')
<x-page-head title="Jadwal Imunisasi" subtitle="Pilih balita untuk melihat jadwal imunisasinya.">
    @if ($balitaTerpilih)
        <a href="{{ route('jadwal-imunisasi.create', ['balita' => $balitaTerpilih->id]) }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Tambah Jadwal
        </a>
        {{-- Tombol buat jadwal otomatis --}}
        <form action="{{ route('jadwal-imunisasi.otomatis') }}" method="POST" class="d-inline"
              onsubmit="return confirm('Buat jadwal otomatis dari master vaksin? Jadwal yang sudah ada tidak akan digandakan.')">
            @csrf
            <input type="hidden" name="id_balita" value="{{ $balitaTerpilih->id }}">
            <button class="btn btn-outline-primary"><i class="bi bi-magic"></i> Buat Otomatis</button>
        </form>
    @endif
</x-page-head>

<form method="GET" action="{{ route('jadwal-imunisasi.index') }}" class="filter-bar mb-3">
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
        <i class="bi bi-calendar2-heart"></i>
        Jadwal Imunisasi — <strong>{{ $balitaTerpilih->nama }}</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Vaksin</th><th>Tanggal Jadwal</th><th>Status</th><th class="text-end">Aksi</th></tr>
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
                    <td>
                        <span class="badge bg-{{ $badgeClass }}">
                            <i class="bi bi-{{ $icon }}"></i>
                            {{ ucfirst($j->status) }}
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('jadwal-imunisasi.edit', $j) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('jadwal-imunisasi.destroy', $j) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus jadwal imunisasi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4"><div class="empty"><i class="bi bi-calendar2-heart"></i>Belum ada jadwal imunisasi.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
