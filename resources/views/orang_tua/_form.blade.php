@php
    $o = $orangTua ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $o?->nama) }}"
               class="form-control @error('nama') is-invalid @enderror">
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">NIK (16 digit)</label>
        <input type="text" name="nik" value="{{ old('nik', $o?->nik) }}"
               class="form-control @error('nik') is-invalid @enderror">
        @error('nik')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">No. Telepon</label>
        <input type="text" name="no_telepon" value="{{ old('no_telepon', $o?->no_telepon) }}"
               class="form-control @error('no_telepon') is-invalid @enderror">
        @error('no_telepon')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $o?->alamat) }}</textarea>
        @error('alamat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>