@extends('layouts.app')

@section('title', 'Menu MPASI')

@section('content')
<div class="flex mb-1">
    <h1>Menu MPASI</h1>
    <a href="{{ route('menu-mpasi.create') }}" class="btn btn-primary">+ Tambah Menu</a>
</div>

{{-- Filter Rentang Usia --}}
<div class="card" style="padding:1rem">
    <form method="GET" action="{{ route('menu-mpasi.index') }}" class="flex">
        <label style="margin:0;font-weight:normal">Filter Usia:</label>
        @foreach($rentangOptions as $key => $label)
            <a href="{{ route('menu-mpasi.index', ['rentang' => $key]) }}"
               class="btn btn-sm {{ $rentang === $key ? 'btn-primary' : 'btn-secondary' }}">
                {{ $label }}
            </a>
        @endforeach
        <a href="{{ route('menu-mpasi.index') }}" class="btn btn-sm btn-secondary">Semua</a>
    </form>
</div>

@if($menuList->isEmpty())
    <div class="card"><p class="text-muted">Belum ada menu MPASI{{ $rentang ? ' untuk rentang usia ini' : '' }}.</p></div>
@else
    <div class="card" style="padding:0">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Menu</th>
                    <th>Rentang Usia</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menuList as $i => $menu)
                <tr>
                    <td>{{ $menuList->firstItem() + $i }}</td>
                    <td><strong>{{ $menu->nama_menu }}</strong></td>
                    <td>{{ $menu->usia_min_bulan }}–{{ $menu->usia_max_bulan }} bulan</td>
                    <td>
                        <div class="flex">
                            <a href="{{ route('menu-mpasi.show', $menu) }}" class="btn btn-sm btn-secondary">Detail</a>
                            <a href="{{ route('menu-mpasi.edit', $menu) }}" class="btn btn-sm btn-primary">Ubah</a>
                            <form method="POST" action="{{ route('menu-mpasi.destroy', $menu) }}"
                                  onsubmit="return confirm('Hapus menu ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $menuList->links('pagination::simple-default') }}</div>
@endif
@endsection
