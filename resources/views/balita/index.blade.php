@extends('layouts.app')
@section('title', 'Data Balita')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Balita</h4>
    <a href="{{ route('balita.create') }}" class="btn btn-success">+ Tambah Balita</a>
</div>
<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau NIK">
        <button class="btn btn-outline-secondary">Cari</button>
    </div>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nama</th><th>Tgl. Lahir</th><th>JK</th><th>Orang Tua/Wali</th><th>Posyandu</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($balita as $b)
                <tr>
                    <td>{{ $b->nama }}</td>
                    <td>{{ $b->tanggal_lahir->format('d-m-Y') }}</td>
                    <td>{{ $b->jenis_kelamin }}</td>
                    <td>{{ $b->orangTua->nama }}</td>
                    <td>{{ $b->unitPosyandu->nama_pos }}</td>
                    <td class="text-end">
                        <a href="{{ route('balita.show', $b) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                        <a href="{{ route('balita.edit', $b) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                        <form action="{{ route('balita.destroy', $b) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus data balita ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data balita.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $balita->links() }}</div>
@endsection