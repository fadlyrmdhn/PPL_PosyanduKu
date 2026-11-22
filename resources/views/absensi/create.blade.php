@extends('layouts.app')
@section('title', 'Catat Kehadiran')
@section('content')

<x-page-head title="Catat Kehadiran" subtitle="{{ $jadwal->nama_kegiatan }} — {{ $jadwal->tanggal->translatedFormat('d F Y') }}">
    <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</x-page-head>

<div class="row g-3 justify-content-center">
    <div class="col-lg-6">
        {{-- Info kegiatan --}}
        <div class="card p-3 mb-3" style="border-left: 4px solid var(--pk-primary)">
            <div class="d-flex align-items-center gap-3">
                <span class="avatar teal"><i class="bi {{ $jadwal->iconJenis() }}"></i></span>
                <div>
                    <strong>{{ $jadwal->nama_kegiatan }}</strong>
                    <small class="d-block text-muted">
                        <i class="bi bi-clock"></i> {{ substr($jadwal->waktu_mulai, 0, 5) }} – {{ substr($jadwal->waktu_selesai, 0, 5) }}
                        &nbsp;|&nbsp;
                        <i class="bi bi-geo-alt"></i> {{ $jadwal->unit?->nama_pos }}
                    </small>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="card p-4">
            <form action="{{ route('absensi.store', $jadwal) }}" method="POST" novalidate>
                @csrf

                @if ($absensi)
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-info-circle"></i>
                        Anda sudah mencatat kehadiran sebagai <strong>{{ $absensi->labelStatus() }}</strong>.
                        Anda dapat mengubahnya di bawah.
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Status Kehadiran <span class="text-danger">*</span></label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (['hadir' => ['teal', 'bi-check-circle'], 'izin' => ['warm', 'bi-clock'], 'sakit' => ['girl', 'bi-bandaid'], 'alpa' => ['boy', 'bi-x-circle']] as $val => [$badge, $icon])
                        <div>
                            <input type="radio" class="btn-check" name="status" id="status_{{ $val }}" value="{{ $val }}"
                                   {{ old('status', $absensi?->status) === $val ? 'checked' : '' }} required>
                            <label class="btn btn-outline-secondary px-4" for="status_{{ $val }}">
                                <i class="bi {{ $icon }}"></i>
                                {{ ucfirst($val) }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Keterangan <small class="text-muted">(opsional)</small></label>
                    <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                              placeholder="Alasan izin/sakit atau catatan lainnya…">{{ old('keterangan', $absensi?->keterangan) }}</textarea>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> {{ $absensi ? 'Perbarui Kehadiran' : 'Simpan Kehadiran' }}
                    </button>
                    <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
