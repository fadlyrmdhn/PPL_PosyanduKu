{{-- Partial form untuk create & edit Pengukuran --}}
@php $p = $pengukuran ?? null; @endphp

<div class="form-group">
    <label>Balita <span style="color:red">*</span></label>
    @if($p)
        {{-- Edit: tampilkan nama balita dan kirim sebagai hidden --}}
        <input type="text" value="{{ $p->balita->nama }}" disabled style="background:#f9fafb;color:#6b7280">
        <input type="hidden" name="id_balita" value="{{ $p->id_balita }}">
    @else
        <select name="id_balita" required>
            <option value="">-- Pilih Balita --</option>
            @foreach($balitaList as $b)
                <option value="{{ $b->id }}"
                    {{ old('id_balita', optional($balitaPilih ?? null)->id) == $b->id ? 'selected' : '' }}>
                    {{ $b->nama }} — lahir {{ $b->tanggal_lahir->format('d M Y') }}
                </option>
            @endforeach
        </select>
        @error('id_balita')<p class="error-msg">{{ $message }}</p>@enderror
    @endif
</div>

<div class="form-group">
    <label>Tanggal Ukur <span style="color:red">*</span></label>
    <input type="date" name="tanggal_ukur"
           value="{{ old('tanggal_ukur', $p?->tanggal_ukur?->format('Y-m-d')) }}"
           max="{{ now()->format('Y-m-d') }}" required>
    @error('tanggal_ukur')<p class="error-msg">{{ $message }}</p>@enderror
</div>

<div class="flex">
    <div class="form-group" style="flex:1">
        <label>Berat Badan (kg) <span style="color:red">*</span></label>
        <input type="number" name="berat_badan" step="0.01" min="0.1" max="100"
               value="{{ old('berat_badan', $p?->berat_badan) }}" required>
        @error('berat_badan')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
    <div class="form-group" style="flex:1">
        <label>Tinggi/Panjang Badan (cm) <span style="color:red">*</span></label>
        <input type="number" name="tinggi_badan" step="0.1" min="1" max="250"
               value="{{ old('tinggi_badan', $p?->tinggi_badan) }}" required>
        @error('tinggi_badan')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
</div>

<div class="form-group">
    <label>Cara Ukur <span style="color:red">*</span></label>
    <select name="cara_ukur" required>
        <option value="">-- Pilih --</option>
        <option value="terlentang" {{ old('cara_ukur', $p?->cara_ukur) == 'terlentang' ? 'selected' : '' }}>
            Terlentang (panjang badan, usia &lt; 24 bulan)
        </option>
        <option value="berdiri" {{ old('cara_ukur', $p?->cara_ukur) == 'berdiri' ? 'selected' : '' }}>
            Berdiri (tinggi badan, usia ≥ 24 bulan)
        </option>
    </select>
    @error('cara_ukur')<p class="error-msg">{{ $message }}</p>@enderror
</div>

<div class="flex">
    <div class="form-group" style="flex:1">
        <label>Lingkar Kepala (cm)</label>
        <input type="number" name="lingkar_kepala" step="0.1" min="1" max="100"
               value="{{ old('lingkar_kepala', $p?->lingkar_kepala) }}">
        @error('lingkar_kepala')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
    <div class="form-group" style="flex:1">
        <label>LiLA (cm)</label>
        <input type="number" name="lila" step="0.1" min="1" max="50"
               value="{{ old('lila', $p?->lila) }}">
        @error('lila')<p class="error-msg">{{ $message }}</p>@enderror
    </div>
</div>

<p class="text-muted mt-1">
    <em>Usia dalam bulan dan status gizi akan dihitung otomatis saat menyimpan.</em>
</p>
