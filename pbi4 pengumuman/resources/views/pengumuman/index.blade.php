@extends('layouts.app')

@section('title', 'Pengumuman - PosyanduKu')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-semibold">Pengumuman Posyandu</h1>

        @if ($isAdmin)
            <a href="{{ route('pengumuman.create') }}"
               class="rounded bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                Tambah Pengumuman
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('pengumuman.index') }}" class="mb-4 flex flex-wrap gap-2">
        <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari judul atau isi..."
               class="w-full flex-1 rounded border border-gray-300 px-3 py-2 text-sm sm:w-auto">

        @if ($isAdmin)
            <select name="status" class="rounded border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua status</option>
                <option value="dipublikasikan" @selected(request('status') === 'dipublikasikan')>Dipublikasikan</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </select>
        @endif

        <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-900">Cari</button>
    </form>

    <div class="space-y-3">
        @forelse ($pengumuman as $item)
            <article class="rounded border bg-white p-4">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <h2 class="font-medium">
                            <a href="{{ route('pengumuman.show', $item) }}" class="hover:text-emerald-700">{{ $item->judul }}</a>
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $item->tanggal_terbit ? $item->tanggal_terbit->locale('id')->translatedFormat('d F Y') : 'Belum diterbitkan' }}
                            @if ($isAdmin && $item->pembuat)
                                &middot; oleh {{ $item->pembuat->name }}
                            @endif
                        </p>
                    </div>

                    @if ($isAdmin)
                        <span class="rounded-full px-2 py-0.5 text-xs {{ $item->isDipublikasikan() ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ $item->isDipublikasikan() ? 'Dipublikasikan' : 'Draft' }}
                        </span>
                    @endif
                </div>

                <p class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($item->isi, 160) }}</p>

                <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                    <a href="{{ route('pengumuman.show', $item) }}" class="text-emerald-700 hover:underline">Baca selengkapnya</a>

                    @if ($isAdmin)
                        <a href="{{ route('pengumuman.edit', $item) }}" class="text-blue-700 hover:underline">Ubah</a>

                        <form method="POST" action="{{ route('pengumuman.destroy', $item) }}"
                              onsubmit="return confirm('Hapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-700 hover:underline">Hapus</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded border bg-white p-6 text-center text-sm text-gray-500">
                Belum ada pengumuman.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $pengumuman->links() }}
    </div>
@endsection
