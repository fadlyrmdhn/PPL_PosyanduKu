@props(['pemberianVitamin' => null, 'balita', 'balitaTerpilih' => null])
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Balita <span class="text-danger">*</span></label>
        <select name="id_balita" class="form-select @error('id_balita') is-invalid @enderror" {{ $pemberianVitamin ? 'disabled' : '' }}>
            <option value="">-- Pilih balita --</option>
            @foreach ($balita as $b)
                <option value="{{ $b->id }}"
                    @selected(old('id_balita', $pemberianVitamin?->id_balita ?? $balitaTerpilih?->id) == $b->id)>
                    {{ $b->nama }}
                </option>
            @endforeach
        </select>
        @if ($pemberianVitamin)
            <input type="hidden" name="id_balita" value="{{ $pemberianVitamin->id_balita }}">
        @endif
        @error('id_balita')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Vitamin <span class="text-danger">*</span></label>
        <input type="text" name="nama_vitamin"
               value="{{ old('nama_vitamin', $pemberianVitamin?->nama_vitamin) }}"
               class="form-control @error('nama_vitamin') is-invalid @enderror"
               placeholder="mis. Vitamin A biru / merah">
        @error('nama_vitamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal Pemberian <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_pemberian" max="{{ date('Y-m-d') }}"
               value="{{ old('tanggal_pemberian', $pemberianVitamin?->tanggal_pemberian?->format('Y-m-d')) }}"
               class="form-control @error('tanggal_pemberian') is-invalid @enderror">
        @error('tanggal_pemberian')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label">Dosis</label>
        <input type="text" name="dosis" value="{{ old('dosis', $pemberianVitamin?->dosis) }}"
               class="form-control" placeholder="mis. 100.000 IU">
    </div>
    <div class="col-md-6">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $pemberianVitamin?->keterangan) }}</textarea>
    </div>
</div>
