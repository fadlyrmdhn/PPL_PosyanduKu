@extends('layouts.app')
@section('title', 'Orang Tua/Wali')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Orang Tua/Wali</h4>
    <a href="{{ route('orang-tua.create') }}" class="btn btn-success">+ Tambah</a>
</div>
<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama">
        <button class="btn btn-outline-secondary">Cari</button>
    </div>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nama</th><th>NIK</th><th>No. Telepon</th><th>Jumlah Balita</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($orangTua as $o)
                <tr>
                    <td>{{ $o->nama }}</td>
                    <td>{{ $o->nik ?? '-' }}</td>
                    <td>{{ $o->no_telepon ?? '-' }}</td>
                    <td>{{ $o->balita_count }}</td>
                    <td class="text-end">
                        <a href="{{ route('orang-tua.edit', $o) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                        <form action="{{ route('orang-tua.destroy', $o) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $orangTua->links() }}</div>
@endsection