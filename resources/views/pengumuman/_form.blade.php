@php
    $p = $pengumuman ?? null;
    $status = old('status', $p?->status);
@endphp
<div class="form-section"><span class="ico"><i class="bi bi-megaphone"></i></span> Isi Pengumuman</div>
<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Judul</label>
        <input type="text" name="judul" value="{{ old('judul', $p?->judul) }}" maxlength="150" placeholder="contoh: Jadwal Posyandu Bulan Ini"
               class="form-control @error('judul') is-invalid @enderror">
        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Isi pengumuman</label>
        <textarea name="isi" rows="8" class="form-control @error('isi') is-invalid @enderror">{{ old('isi', $p?->isi) }}</textarea>
        @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal dan jam terbit (WIB)</label>
        <input type="datetime-local" name="tanggal_terbit" value="{{ old('tanggal_terbit', $p?->tanggal_terbit?->format('Y-m-d\TH:i')) }}"
               class="form-control @error('tanggal_terbit') is-invalid @enderror">
        @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Kosongkan untuk memakai waktu sekarang saat dipublikasikan.</div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="draft" @selected($status === 'draft')>Draft (belum terlihat oleh kader dan orang tua)</option>
            <option value="dipublikasikan" @selected($status === 'dipublikasikan')>Dipublikasikan</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
