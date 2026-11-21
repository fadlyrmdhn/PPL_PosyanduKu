@props(['imunisasi' => null, 'balita', 'vaksin', 'balitaTerpilih' => null])
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Balita <span class="text-danger">*</span></label>
        <select name="id_balita" class="form-select @error('id_balita') is-invalid @enderror" {{ $imunisasi ? 'disabled' : '' }}>
            <option value="">-- Pilih balita --</option>
            @foreach ($balita as $b)
                <option value="{{ $b->id }}"
                    @selected(old('id_balita', $imunisasi?->id_balita ?? $balitaTerpilih?->id) == $b->id)>
                    {{ $b->nama }}
                </option>
            @endforeach
        </select>
        @if ($imunisasi)
            <input type="hidden" name="id_balita" value="{{ $imunisasi->id_balita }}">
        @endif
        @error('id_balita')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Vaksin <span class="text-danger">*</span></label>
        <select name="id_vaksin" class="form-select @error('id_vaksin') is-invalid @enderror">
            <option value="">-- Pilih vaksin --</option>
            @foreach ($vaksin as $v)
                <option value="{{ $v->id }}"
                    @selected(old('id_vaksin', $imunisasi?->id_vaksin) == $v->id)>
                    {{ $v->nama_vaksin }} ({{ $v->usia_pemberian_bulan }} bln, dosis {{ $v->dosis_ke }})
                </option>
            @endforeach
        </select>
        @error('id_vaksin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal Pemberian <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_pemberian" max="{{ date('Y-m-d') }}"
               value="{{ old('tanggal_pemberian', $imunisasi?->tanggal_pemberian?->format('Y-m-d')) }}"
               class="form-control @error('tanggal_pemberian') is-invalid @enderror">
        @error('tanggal_pemberian')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label">Dosis Ke-</label>
        <input type="number" name="dosis_ke" min="1"
               value="{{ old('dosis_ke', $imunisasi?->dosis_ke) }}"
               class="form-control @error('dosis_ke') is-invalid @enderror">
        @error('dosis_ke')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $imunisasi?->keterangan) }}</textarea>
    </div>
</div>
