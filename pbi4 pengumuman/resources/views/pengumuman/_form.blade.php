@php
    $tanggalTerbit = old('tanggal_terbit', $pengumuman->tanggal_terbit?->format('Y-m-d'));
    $status = old('status', $pengumuman->status);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-4 rounded border bg-white p-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="judul" class="mb-1 block text-sm font-medium">Judul</label>
        <input type="text" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" maxlength="150"
               class="w-full rounded border border-gray-300 px-3 py-2 text-sm @error('judul') border-red-500 @enderror">
        @error('judul') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="isi" class="mb-1 block text-sm font-medium">Isi Pengumuman</label>
        <textarea id="isi" name="isi" rows="8"
                  class="w-full rounded border border-gray-300 px-3 py-2 text-sm @error('isi') border-red-500 @enderror">{{ old('isi', $pengumuman->isi) }}</textarea>
        @error('isi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="tanggal_terbit" class="mb-1 block text-sm font-medium">Tanggal Terbit</label>
            <input type="date" id="tanggal_terbit" name="tanggal_terbit" value="{{ $tanggalTerbit }}"
                   class="w-full rounded border border-gray-300 px-3 py-2 text-sm @error('tanggal_terbit') border-red-500 @enderror">
            <p class="mt-1 text-xs text-gray-500">Kosongkan untuk memakai tanggal hari ini saat dipublikasikan.</p>
            @error('tanggal_terbit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="mb-1 block text-sm font-medium">Status</label>
            <select id="status" name="status"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm @error('status') border-red-500 @enderror">
                <option value="draft" @selected($status === 'draft')>Draft (belum dilihat Orang Tua)</option>
                <option value="dipublikasikan" @selected($status === 'dipublikasikan')>Dipublikasikan</option>
            </select>
            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Simpan</button>
        <a href="{{ route('pengumuman.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
    </div>
</form>
