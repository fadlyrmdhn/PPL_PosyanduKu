@php $s = $stok ?? null; @endphp
<div class="form-section"><span class="ico"><i class="bi bi-capsule"></i></span> Informasi Item</div>
<div class="row g-3 mb-4 pb-2 border-bottom">
    <div class="col-md-6">
        <label class="form-label">Nama vitamin/obat</label>
        <input type="text" name="nama" value="{{ old('nama', $s?->nama) }}" placeholder="contoh: Vitamin A Biru"
               class="form-control @error('nama') is-invalid @enderror">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach (\App\Models\StokVitaminObat::JENIS as $opsi)
                <option value="{{ $opsi }}" @selected(old('jenis', $s?->jenis) === $opsi)>{{ ucfirst($opsi) }}</option>
            @endforeach
        </select>
        @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Satuan</label>
        <input type="text" name="satuan" value="{{ old('satuan', $s?->satuan) }}" placeholder="tablet, kapsul, botol"
               class="form-control @error('satuan') is-invalid @enderror">
        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-section"><span class="ico"><i class="bi bi-box-seam"></i></span> Persediaan</div>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Jumlah</label>
        <input type="number" name="jumlah" min="0" value="{{ old('jumlah', $s?->jumlah) }}"
               class="form-control @error('jumlah') is-invalid @enderror">
        @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok minimum</label>
        <input type="number" name="stok_minimum" min="0" value="{{ old('stok_minimum', $s?->stok_minimum) }}"
               class="form-control @error('stok_minimum') is-invalid @enderror">
        @error('stok_minimum') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Di bawah atau sama dengan angka ini, stok ditandai menipis.</div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal kedaluwarsa</label>
        <input type="date" name="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa', $s?->tanggal_kedaluwarsa?->format('Y-m-d')) }}"
               class="form-control @error('tanggal_kedaluwarsa') is-invalid @enderror">
        @error('tanggal_kedaluwarsa') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
