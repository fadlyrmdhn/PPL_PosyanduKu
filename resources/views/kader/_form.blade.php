{{-- Partial form kader — dipakai di create.blade.php dan edit.blade.php --}}
@php $k = $kader ?? null; @endphp

{{-- Seksi: Informasi Pribadi --}}
<div class="form-section"><span class="ico"><i class="bi bi-person"></i></span> Informasi Pribadi</div>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Nama lengkap <span class="text-danger">*</span></label>
        <input type="text" name="nama" value="{{ old('nama', $k?->nama) }}" class="form-control @error('nama') is-invalid @enderror"
               placeholder="Nama lengkap kader" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">NIK (16 digit) <span class="text-danger">*</span></label>
        <input type="text" name="nik" value="{{ old('nik', $k?->nik) }}" maxlength="16" inputmode="numeric"
               class="form-control @error('nik') is-invalid @enderror" placeholder="16 digit angka" required>
        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Jenis kelamin <span class="text-danger">*</span></label>
        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
            <option value="">— Pilih —</option>
            <option value="L" {{ old('jenis_kelamin', $k?->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $k?->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal lahir</label>
        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $k?->tanggal_lahir?->format('Y-m-d')) }}"
               class="form-control @error('tanggal_lahir') is-invalid @enderror" max="{{ date('Y-m-d') }}">
        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $k?->no_hp) }}" class="form-control @error('no_hp') is-invalid @enderror"
               placeholder="08xx-xxxx-xxxx" required>
        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror"
                  placeholder="Alamat lengkap">{{ old('alamat', $k?->alamat) }}</textarea>
        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Seksi: Unit & Status --}}
<div class="form-section"><span class="ico"><i class="bi bi-geo-alt"></i></span> Unit & Status</div>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Unit posyandu <span class="text-danger">*</span></label>
        <select name="id_unit" class="form-select @error('id_unit') is-invalid @enderror" required>
            <option value="">— Pilih unit —</option>
            @foreach ($units as $u)
                <option value="{{ $u->id }}" {{ old('id_unit', $k?->id_unit) == $u->id ? 'selected' : '' }}>
                    {{ $u->nama_pos }}
                </option>
            @endforeach
        </select>
        @error('id_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="aktif"    {{ old('status', $k?->status ?? 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ old('status', $k?->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Tanggal bergabung</label>
        <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $k?->tanggal_bergabung?->format('Y-m-d')) }}"
               class="form-control @error('tanggal_bergabung') is-invalid @enderror">
        @error('tanggal_bergabung') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Seksi: Akun Login (hanya saat tambah baru) --}}
@unless ($k)
<div class="form-section"><span class="ico"><i class="bi bi-lock"></i></span> Akun Login (opsional)</div>
<p class="text-muted small mb-3">Isi bagian ini untuk membuat akun login bagi kader. Kosongkan jika belum diperlukan.</p>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror"
               placeholder="email@contoh.com">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror"
               placeholder="username_unik">
        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               placeholder="Min. 8 karakter">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
@endunless
