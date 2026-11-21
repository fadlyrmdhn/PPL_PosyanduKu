@extends('layouts.app')
@section('title', 'Rekap Kehadiran Kader')
@section('content')

<x-page-head title="Rekap Kehadiran Kader" subtitle="Ringkasan jumlah hadir, izin, sakit, dan alpa per kader dalam satu periode.">
</x-page-head>

{{-- Filter --}}
<form method="GET" action="{{ route('absensi.rekap') }}" class="filter-bar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-sm-3">
            <label class="form-label small mb-1">Unit Posyandu</label>
            <select name="id_unit" class="form-select">
                <option value="">Semua Unit</option>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}" {{ request('id_unit') == $u->id ? 'selected' : '' }}>{{ $u->nama_pos }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label small mb-1">Bulan</label>
            <input type="month" name="bulan" value="{{ $bulan }}" class="form-control">
        </div>
        <div class="col-sm-auto">
            <button class="btn btn-primary"><i class="bi bi-funnel"></i> Tampilkan</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kader</th>
                    <th>Unit</th>
                    <th class="text-center"><span class="pk-badge teal">Hadir</span></th>
                    <th class="text-center"><span class="pk-badge warm">Izin</span></th>
                    <th class="text-center"><span class="pk-badge girl">Sakit</span></th>
                    <th class="text-center"><span class="pk-badge boy">Alpa</span></th>
                    <th class="text-center">Total</th>
                    <th class="text-center">% Hadir</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($rekap as $r)
                @php $pct = $r['total'] > 0 ? round($r['hadir'] / $r['total'] * 100) : 0; @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar {{ $r['kader']->jenis_kelamin === 'L' ? 'boy' : 'girl' }}" style="width:2rem;height:2rem;font-size:.8rem">
                                {{ strtoupper(substr($r['kader']->nama, 0, 1)) }}
                            </span>
                            <div>
                                <strong>{{ $r['kader']->nama }}</strong>
                                <small class="d-block text-muted">{{ $r['kader']->nik }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="pk-badge teal">{{ $r['kader']->unit?->nama_pos ?? '—' }}</span></td>
                    <td class="text-center fw-bold text-success">{{ $r['hadir'] }}</td>
                    <td class="text-center fw-bold" style="color:#d26a2c">{{ $r['izin'] }}</td>
                    <td class="text-center fw-bold" style="color:var(--pk-girl)">{{ $r['sakit'] }}</td>
                    <td class="text-center fw-bold" style="color:var(--pk-boy)">{{ $r['alpa'] }}</td>
                    <td class="text-center text-muted">{{ $r['total'] }}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center gap-2 justify-content-center">
                            <div class="progress" style="width:60px;height:6px">
                                <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                            </div>
                            <small class="{{ $pct >= 80 ? 'text-success' : ($pct >= 60 ? 'text-warning' : 'text-danger') }} fw-bold">
                                {{ $pct }}%
                            </small>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty">
                            <i class="bi bi-bar-chart"></i>
                            Belum ada data absensi untuk periode ini.
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
            @if ($rekap->isNotEmpty())
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="2">Total Keseluruhan</td>
                    <td class="text-center text-success">{{ $rekap->sum('hadir') }}</td>
                    <td class="text-center" style="color:#d26a2c">{{ $rekap->sum('izin') }}</td>
                    <td class="text-center" style="color:var(--pk-girl)">{{ $rekap->sum('sakit') }}</td>
                    <td class="text-center" style="color:var(--pk-boy)">{{ $rekap->sum('alpa') }}</td>
                    <td class="text-center">{{ $rekap->sum('total') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
