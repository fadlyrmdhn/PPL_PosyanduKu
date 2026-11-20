@php $u = $unit ?? null; @endphp
<div class="form-section"><span class="ico"><i class="bi bi-geo-alt"></i></span> Informasi Pos</div>
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama pos</label>
        <input type="text" name="nama_pos" value="{{ old('nama_pos', $u?->nama_pos) }}" placeholder="contoh: Posyandu Melati"
               class="form-control @error('nama_pos') is-invalid @enderror">
        @error('nama_pos') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">RW/Dusun</label>
        <input type="text" name="rw_dusun" value="{{ old('rw_dusun', $u?->rw_dusun) }}" placeholder="contoh: RW 01"
               class="form-control @error('rw_dusun') is-invalid @enderror">
        @error('rw_dusun') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $u?->alamat) }}</textarea>
        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
