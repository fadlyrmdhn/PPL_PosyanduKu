@php
    $b = $balita ?? null;
    $terpilih = collect(old('orang_tua', $b ? $b->orangTua->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    $fields = [
        ['nama', 'Nama balita', 'text', 'col-md-6', null],
        ['nik', 'NIK (16 digit)', 'text', 'col-md-6', null],
        ['tanggal_lahir', 'Tanggal lahir', 'date', 'col-md-4', null],
        ['berat_lahir', 'Berat lahir (kg)', 'number', 'col-md-4', '0.01'],
        ['panjang_lahir', 'Panjang lahir (cm)', 'number', 'col-md-4', '0.1'],
        ['usia_kehamilan', 'Usia kehamilan (minggu)', 'number', 'col-md-6', '1'],
        ['penolong_persalinan', 'Penolong persalinan (bidan, dokter, dll.)', 'text', 'col-md-6', null],
    ];
@endphp
<div class="form-section"><span class="ico"><i class="bi bi-person-vcard"></i></span> Identitas</div>
<div class="row g-3 mb-4 pb-2 border-bottom">
    @foreach (array_slice($fields, 0, 2) as [$name, $label, $type, $col, $step])
        <div class="{{ $col }}">
            <label class="form-label">{{ $label }}</label>
            <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $b?->{$name}) }}"
                   class="form-control @error($name) is-invalid @enderror">
            @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endforeach
    <div class="col-md-4">
        <label class="form-label">Tanggal lahir</label>
        <input type="date" name="tanggal_lahir" max="{{ now()->toDateString() }}"
               value="{{ old('tanggal_lahir', $b?->tanggal_lahir?->format('Y-m-d')) }}"
               class="form-control @error('tanggal_lahir') is-invalid @enderror">
        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Jenis kelamin</label>
        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('jenis_kelamin', $b?->jenis_kelamin) == 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $b?->jenis_kelamin) == 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Unit posyandu</label>
        <select name="id_unit" class="form-select @error('id_unit') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach ($unit as $u)
                <option value="{{ $u->id }}" @selected(old('id_unit', $b?->id_unit) == $u->id)>{{ $u->nama_pos }} ({{ $u->rw_dusun }})</option>
            @endforeach
        </select>
        @error('id_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-section"><span class="ico"><i class="bi bi-clipboard2-pulse"></i></span> Riwayat Kelahiran</div>
<div class="row g-3 mb-4">
    @foreach (array_slice($fields, 3) as [$name, $label, $type, $col, $step])
        <div class="{{ $col }}">
            <label class="form-label">{{ $label }}</label>
            <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $b?->{$name}) }}"
                   @if ($step) step="{{ $step }}" @endif
                   class="form-control @error($name) is-invalid @enderror">
            @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endforeach
</div>

<div class="form-section"><span class="ico"><i class="bi bi-people"></i></span> Orang Tua/Wali</div>
<div class="mb-2">
    <select name="orang_tua[]" multiple size="5" class="form-select @error('orang_tua') is-invalid @enderror">
        @foreach ($orangTua as $o)
            <option value="{{ $o->id }}" @selected(in_array($o->id, $terpilih, true))>{{ $o->nama }} ({{ ucfirst($o->hubungan) }}) - {{ $o->nik }}</option>
        @endforeach
    </select>
    @error('orang_tua') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @error('orang_tua.*') <div class="text-danger small">{{ $message }}</div> @enderror
    <div class="form-text">Tahan Ctrl untuk memilih lebih dari satu. Belum terdaftar? <a href="{{ route('orang-tua.create') }}">Tambah orang tua/wali</a></div>
</div>
