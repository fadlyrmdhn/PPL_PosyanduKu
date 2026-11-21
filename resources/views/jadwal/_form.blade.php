{{-- Partial form jadwal kegiatan --}}
@php $j = $jadwal ?? null; @endphp

<div class="form-section"><span class="ico"><i class="bi bi-calendar3"></i></span> Detail Kegiatan</div>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Nama kegiatan <span class="text-danger">*</span></label>
        <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $j?->nama_kegiatan) }}"
               class="form-control @error('nama_kegiatan') is-invalid @enderror"
               placeholder="contoh: Posyandu Bulanan" required>
        @error('nama_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis kegiatan <span class="text-danger">*</span></label>
        <select name="jenis_kegiatan" class="form-select @error('jenis_kegiatan') is-invalid @enderror" required>
            <option value="">— Pilih jenis —</option>
            @foreach (['penimbangan' => 'Penimbangan', 'imunisasi' => 'Imunisasi', 'penyuluhan' => 'Penyuluhan', 'lainnya' => 'Lainnya'] as $v => $l)
                <option value="{{ $v }}" {{ old('jenis_kegiatan', $j?->jenis_kegiatan) === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        @error('jenis_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Unit posyandu <span class="text-danger">*</span></label>
        <select name="id_unit" class="form-select @error('id_unit') is-invalid @enderror" required>
            <option value="">— Pilih unit —</option>
            @foreach ($units as $u)
                <option value="{{ $u->id }}" {{ old('id_unit', $j?->id_unit) == $u->id ? 'selected' : '' }}>
                    {{ $u->nama_pos }}
                </option>
            @endforeach
        </select>
        @error('id_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
        <input type="date" name="tanggal" value="{{ old('tanggal', $j?->tanggal?->format('Y-m-d')) }}"
               class="form-control @error('tanggal') is-invalid @enderror" required>
        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Waktu mulai <span class="text-danger">*</span></label>
        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $j ? substr($j->waktu_mulai, 0, 5) : '') }}"
               class="form-control @error('waktu_mulai') is-invalid @enderror" required>
        @error('waktu_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Waktu selesai <span class="text-danger">*</span></label>
        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $j ? substr($j->waktu_selesai, 0, 5) : '') }}"
               class="form-control @error('waktu_selesai') is-invalid @enderror" required>
        @error('waktu_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                  placeholder="Informasi tambahan tentang kegiatan ini…">{{ old('keterangan', $j?->keterangan) }}</textarea>
        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
