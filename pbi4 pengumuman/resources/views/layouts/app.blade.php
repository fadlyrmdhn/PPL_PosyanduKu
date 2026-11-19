<!DOCTYPE html>
{{-- LAYOUT SEMENTARA: lewati/hapus file ini jika tim sudah punya layout utama (dari PBI-1/autentikasi).
     Jika layout tim berbeda, ubah @extends('layouts.app') dan @section('content') di view pengumuman. --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PosyanduKu')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">
    <header class="bg-white border-b">
        <div class="mx-auto max-w-5xl px-4 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="font-semibold text-emerald-700">PosyanduKu</a>
            <nav class="flex items-center gap-4 text-sm">
                @auth
                    <a href="{{ route('pengumuman.index') }}" class="text-gray-600 hover:text-emerald-700">Pengumuman</a>
                    <span class="hidden text-gray-400 sm:inline">{{ auth()->user()->name }}</span>
                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-700">Keluar</button>
                        </form>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-6">
        @if (session('sukses'))
            <div class="mb-4 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('sukses') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
