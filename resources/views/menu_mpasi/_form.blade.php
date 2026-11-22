{{-- Partial form untuk create & edit Menu MPASI --}}
@php $menu = $menu ?? null; @endphp

<div class="form-group">
    <label>Nama Menu <span style="color:red">*</span></label>
    <input type="text" name="nama_menu" value="{{ old('nama_menu', $menu?->nama_menu) }}" required maxlength="255">
    @error('nama_menu')<p class="error-msg">{{ $message }}</p>@enderror
</div>

<div class="flex">
    <div class="form-group" style="flex:1">
        <label>Usia Minimum (bulan) <span style="color:red">*</span></label>
        <input type="number" name="usia_min_bulan" value="{{ old('usia_min_bulan', $menu?->usia_min_bulan ?? 6) }}"
               min="0" max="60" required>
        @error('usia_min_bulan')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
    <div class="form-group" style="flex:1">
        <label>Usia Maksimum (bulan) <span style="color:red">*</span></label>
        <input type="number" name="usia_max_bulan" value="{{ old('usia_max_bulan', $menu?->usia_max_bulan ?? 8) }}"
               min="0" max="60" required>
        @error('usia_max_bulan')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
</div>

<div class="form-group">
    <label>Bahan <span style="color:red">*</span></label>
    <textarea name="bahan" rows="3" required>{{ old('bahan', $menu?->bahan) }}</textarea>
    @error('bahan')<p class="error-msg">{{ $message }}</p>@enderror
</div>

<div class="form-group">
    <label>Cara Pembuatan <span style="color:red">*</span></label>
    <textarea name="cara_pembuatan" rows="4" required>{{ old('cara_pembuatan', $menu?->cara_pembuatan) }}</textarea>
    @error('cara_pembuatan')<p class="error-msg">{{ $message }}</p>@enderror
</div>

<div class="form-group">
    <label>Kandungan Gizi</label>
    <textarea name="kandungan_gizi" rows="2" placeholder="Contoh: Energi 150 kkal, Protein 5 g, Lemak 3 g">{{ old('kandungan_gizi', $menu?->kandungan_gizi) }}</textarea>
    @error('kandungan_gizi')<p class="error-msg">{{ $message }}</p>@enderror
</div>

<div class="form-group">
    <label>Gambar (opsional, maks. 2 MB)</label>
    @if($menu?->gambar)
        <div class="mb-1">
            <img src="{{ Storage::url($menu->gambar) }}" alt="Gambar menu" style="max-height:120px;border-radius:6px">
        </div>
    @endif
    <input type="file" name="gambar" accept="image/*">
    @error('gambar')<p class="error-msg">{{ $message }}</p>@enderror
</div>
