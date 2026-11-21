@props(['vaksin' => null])
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Vaksin <span class="text-danger">*</span></label>
        <input type="text" name="nama_vaksin" value="{{ old('nama_vaksin', $vaksin?->nama_vaksin) }}"
               class="form-control @error('nama_vaksin') is-invalid @enderror" placeholder="mis. DPT-HB-Hib 1">
        @error('nama_vaksin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Usia Pemberian (bulan) <span class="text-danger">*</span></label>
        <input type="number" name="usia_pemberian_bulan" min="0"
               value="{{ old('usia_pemberian_bulan', $vaksin?->usia_pemberian_bulan) }}"
               class="form-control @error('usia_pemberian_bulan') is-invalid @enderror">
        @error('usia_pemberian_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Dosis Ke- <span class="text-danger">*</span></label>
        <input type="number" name="dosis_ke" min="1"
               value="{{ old('dosis_ke', $vaksin?->dosis_ke) }}"
               class="form-control @error('dosis_ke') is-invalid @enderror">
        @error('dosis_ke')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Jumlah Dosis</label>
        <input type="text" name="jumlah_dosis" value="{{ old('jumlah_dosis', $vaksin?->jumlah_dosis) }}"
               class="form-control" placeholder="mis. 0,5 ml">
    </div>
    <div class="col-md-8">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="2"
                  placeholder="Keterangan tambahan">{{ old('keterangan', $vaksin?->keterangan) }}</textarea>
    </div>
</div>
