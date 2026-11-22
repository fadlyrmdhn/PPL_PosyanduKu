@php
    $o = $orangTua ?? null;
    $terpilih = collect(old('balita', $o ? $o->balita->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
@endphp
<div class="form-section"><span class="ico"><i class="bi bi-people"></i></span> Data Orang Tua/Wali</div>
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $o?->nama) }}"
               class="form-control @error('nama') is-invalid @enderror">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">NIK (16 digit)</label>
        <input type="text" name="nik" value="{{ old('nik', $o?->nik) }}"
               class="form-control @error('nik') is-invalid @enderror">
        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Hubungan</label>
        <select name="hubungan" class="form-select @error('hubungan') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach (['ayah' => 'Ayah', 'ibu' => 'Ibu', 'wali' => 'Wali'] as $nilai => $label)
                <option value="{{ $nilai }}" @selected(old('hubungan', $o?->hubungan) == $nilai)>{{ $label }}</option>
            @endforeach
        </select>
        @error('hubungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">No. HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $o?->no_hp) }}"
               class="form-control @error('no_hp') is-invalid @enderror">
        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Akun login Orang Tua</label>
        <select name="id_user" class="form-select @error('id_user') is-invalid @enderror">
            <option value="">-- Tidak ada --</option>
            @foreach ($akun as $a)
                <option value="{{ $a->id }}" @selected(old('id_user', $o?->id_user) == $a->id)>{{ $a->nama }} ({{ $a->username }})</option>
            @endforeach
        </select>
        @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $o?->alamat) }}</textarea>
        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Balita yang dikaitkan</label>
        <select name="balita[]" multiple size="5" class="form-select @error('balita') is-invalid @enderror">
            @foreach ($balita as $b)
                <option value="{{ $b->id }}" @selected(in_array($b->id, $terpilih, true))>{{ $b->nama }} - {{ $b->nik }}</option>
            @endforeach
        </select>
        @error('balita') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Tahan Ctrl untuk memilih lebih dari satu balita.</div>
    </div>
</div>
