@extends('layouts.app')
@section('title', 'Unit Posyandu')
@section('content')
<x-page-head title="Unit Posyandu" subtitle="Daftar pos pelayanan beserta wilayahnya.">
    <a href="{{ route('unit-posyandu.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Unit</a>
</x-page-head>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Nama Pos</th><th>RW/Dusun</th><th>Alamat</th><th>Balita</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($unit as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar teal"><i class="bi bi-geo-alt-fill"></i></span>
                            <strong>{{ $u->nama_pos }}</strong>
                        </div>
                    </td>
                    <td><span class="pk-badge teal">{{ $u->rw_dusun }}</span></td>
                    <td class="text-muted">{{ $u->alamat }}</td>
                    <td><span class="pk-badge warm"><i class="bi bi-emoji-smile"></i> {{ $u->balita_count }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('unit-posyandu.edit', $u) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('unit-posyandu.destroy', $u) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus unit posyandu ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty"><i class="bi bi-geo-alt"></i>Belum ada unit posyandu.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $unit->links() }}</div>
@endsection
