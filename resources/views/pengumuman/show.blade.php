@extends('layouts.app')

@section('title', $pengumuman->judul . ' - PosyanduKu')

@section('content')
    <a href="{{ route('pengumuman.index') }}" class="text-sm text-gray-600 hover:text-emerald-700">&larr; Kembali ke daftar pengumuman</a>

    <article class="mt-3 rounded border bg-white p-5">
        <h1 class="text-xl font-semibold">{{ $pengumuman->judul }}</h1>

        <p class="mt-1 text-xs text-gray-500">
            {{ $pengumuman->tanggal_terbit ? $pengumuman->tanggal_terbit->locale('id')->translatedFormat('d F Y, H.i').' WIB' : 'Belum diterbitkan' }}
            @if ($pengumuman->nama_pembuat)
                &middot; oleh {{ $pengumuman->nama_pembuat }}
            @endif
            @can('update', $pengumuman)
                &middot; {{ $pengumuman->isDipublikasikan() ? 'Dipublikasikan' : 'Draft' }}
            @endcan
        </p>

        <div class="mt-4 text-sm leading-relaxed">{!! nl2br(e($pengumuman->isi)) !!}</div>

        @can('update', $pengumuman)
            <div class="mt-5 flex flex-wrap items-center gap-3 border-t pt-4 text-sm">
                @unless ($pengumuman->isDipublikasikan())
                    <form method="POST" action="{{ route('pengumuman.publikasikan', $pengumuman) }}">
                        @csrf
                        <button type="submit" class="text-emerald-700 hover:underline">Publikasikan</button>
                    </form>
                @endunless

                <a href="{{ route('pengumuman.edit', $pengumuman) }}" class="text-blue-700 hover:underline">Ubah</a>

                <form method="POST" action="{{ route('pengumuman.destroy', $pengumuman) }}"
                      onsubmit="return confirm('Hapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-700 hover:underline">Hapus</button>
                </form>
            </div>
        @endcan
    </article>
@endsection
