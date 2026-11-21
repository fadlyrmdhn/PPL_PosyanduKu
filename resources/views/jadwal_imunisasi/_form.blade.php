@props(['jadwalImunisasi' => null, 'balita', 'vaksin', 'balitaTerpilih' => null])
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Balita <span class="text-danger">*</span></label>
        <select name="id_balita" class="form-select @error('id_balita') is-invalid @enderror" {{ $jadwalImunisasi ? 'disabled' : '' }}>
            <option value="">-- Pilih balita --</option>
            @foreach ($balita as $b)
                <option value="{{ $b->id }}"
                    @selected(old('id_balita', $jadwalImunisasi?->id_balita ?? $balitaTerpilih?->id) == $b->id)>
                    {{ $b->nama }}
                </option>
            @endforeach
        </select>
        @if ($jadwalImunisasi)
            <input type="hidden" name="id_balita" value="{{ $jadwalImunisasi->id_balita }}">
        @endif
        @error('id_balita')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Vaksin <span class="text-danger">*</span></label>
        <select name="id_vaksin" class="form-select @error('id_vaksin') is-invalid @enderror">
            <option value="">-- Pilih vaksin --</option>
            @foreach ($vaksin as $v)
                <option value="{{ $v->id }}"
                    @selected(old('id_vaksin', $jadwalImunisasi?->id_vaksin) == $v->id)>
                    {{ $v->nama_vaksin }} ({{ $v->usia_pemberian_bulan }} bln)
                </option>
            @endforeach
        </select>
        @error('id_vaksin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal Jadwal <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_jadwal"
               value="{{ old('tanggal_jadwal', $jadwalImunisasi?->tanggal_jadwal?->format('Y-m-d')) }}"
               class="form-control @error('tanggal_jadwal') is-invalid @enderror">
        @error('tanggal_jadwal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach (['terjadwal' => 'Terjadwal', 'selesai' => 'Selesai', 'terlewat' => 'Terlewat'] as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $jadwalImunisasi?->status ?? 'terjadwal') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
