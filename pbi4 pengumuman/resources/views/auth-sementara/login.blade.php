@extends('layouts.app')

@section('title', 'Masuk - PosyanduKu')

@section('content')
    <div class="mx-auto max-w-sm">
        <h1 class="mb-4 text-xl font-semibold">Masuk</h1>

        <form method="POST" action="{{ route('login') }}" class="space-y-4 rounded border bg-white p-5">
            @csrf

            <div>
                <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus
                       class="w-full rounded border border-gray-300 px-3 py-2 text-sm @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium">Kata sandi</label>
                <input type="password" id="password" name="password"
                       class="w-full rounded border border-gray-300 px-3 py-2 text-sm @error('password') border-red-500 @enderror">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full rounded bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                Masuk
            </button>

            <p class="text-xs text-gray-500">Halaman login sementara untuk pengujian PBI-4.</p>
        </form>
    </div>
@endsection
