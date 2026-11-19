@php
    $b = $balita ?? null;
    $fields = [
        ['nama', 'Nama balita', 'text', 'col-md-6', null],
        ['nik', 'NIK (16 digit)', 'text', 'col-md-6', null],
        ['tanggal_lahir', 'Tanggal lahir', 'date', 'col-md-4', null],
        ['berat_lahir', 'Berat lahir (kg)', 'number', 'col-md-4', '0.01'],
        ['panjang_lahir', 'Panjang lahir (cm)', 'number', 'col-md-4', '0.1'],
        ['usia_kehamilan', 'Usia kehamilan (minggu)', 'number', 'col-md-6', '1'],
        ['penolong_persalinan', 'Penolong persalinan', 'text', 'col-md-6', null],
    ];
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Orang tua/wali</label>
        <select name="orang_tua_id" class="form-select @error('orang_tua_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach ($orangTua as $o)
                <option value="{{ $o->id }}" @selected(old('orang_tua_id', $b?->orang_tua_id) == $o->id)>{{ $o->nama }}</option>
            @endforeach
        </select>
        @error('orang_tua_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Belum terdaftar? <a href="{{ route('orang-tua.create') }}">Tambah orang tua/wali</a></div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Posyandu</label>
        <select name="unit_posyandu_id" class="form-select @error('unit_posyandu_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach ($unit as $u)
                <option value="{{ $u->id }}" @selected(old('unit_posyandu_id', $b?->unit_posyandu_id) == $u->id)>{{ $u->nama_pos }} ({{ $u->rw_dusun }})</option>
            @endforeach
        </select>
        @error('unit_posyandu_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @foreach ($fields as [$name, $label, $type, $col, $step])
        @php
            $nilai = $name === 'tanggal_lahir' ? $b?->tanggal_lahir?->format('Y-m-d') : $b?->{$name};
        @endphp
        <div class="{{ $col }}">
            <label class="form-label">{{ $label }}</label>
            <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $nilai) }}"
                   @if ($step) step="{{ $step }}" @endif
                   class="form-control @error($name) is-invalid @enderror">
            @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endforeach

    <div class="col-md-6">
        <label class="form-label">Jenis kelamin</label>
        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('jenis_kelamin', $b?->jenis_kelamin) == 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $b?->jenis_kelamin) == 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>