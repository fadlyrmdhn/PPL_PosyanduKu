@extends('layouts.app')

@section('title', 'Pengukuran Pertumbuhan')

@section('content')
<h1>Pengukuran Pertumbuhan & Status Gizi</h1>

{{-- Pilih Balita --}}
<div class="card">
    <form method="GET" action="{{ route('pengukuran.index') }}" class="flex">
        <div style="flex:1">
            <label>Pilih Balita:</label>
            <select name="balita_id" onchange="this.form.submit()">
                <option value="">-- Pilih Balita --</option>
                @foreach($balitaList as $b)
                    <option value="{{ $b->id }}" {{ optional($balita)->id == $b->id ? 'selected' : '' }}>
                        {{ $b->nama }} ({{ $b->jenis_kelamin }}) — lahir {{ $b->tanggal_lahir->format('d M Y') }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

@if($balita)
    {{-- Header Balita --}}
    <div class="card">
        <div class="flex">
            <div>
                <h2>{{ $balita->nama }}</h2>
                <p class="text-muted">
                    Jenis Kelamin: <strong>{{ $balita->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong> |
                    Lahir: <strong>{{ $balita->tanggal_lahir->format('d M Y') }}</strong>
                </p>
            </div>
            <a href="{{ route('pengukuran.create', ['balita_id' => $balita->id]) }}" class="btn btn-primary">
                + Catat Pengukuran Baru
            </a>
        </div>
    </div>

    {{-- Riwayat Pengukuran --}}
    @if($riwayat->isEmpty())
        <div class="card"><p class="text-muted">Belum ada riwayat pengukuran untuk balita ini.</p></div>
    @else
        <div class="card" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Usia (bln)</th>
                        <th>BB (kg)</th>
                        <th>TB (cm)</th>
                        <th>Status BB/U</th>
                        <th>Status TB/U</th>
                        <th>Status BB/TB</th>
                        <th>Dicatat oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $p)
                    @php $sg = $p->statusGizi; @endphp
                    <tr>
                        <td>{{ $p->tanggal_ukur->format('d M Y') }}</td>
                        <td>{{ $p->usia_bulan }}</td>
                        <td>{{ number_format($p->berat_badan, 1) }}</td>
                        <td>{{ number_format($p->tinggi_badan, 1) }}</td>
                        <td>
                            @if($sg?->kategori_bb_u)
                                @php $w = \App\Models\StatusGizi::warnaBadge($sg->kategori_bb_u); @endphp
                                <span class="badge badge-{{ $w }}">{{ $sg->kategori_bb_u }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($sg?->kategori_tb_u)
                                @php $w = \App\Models\StatusGizi::warnaBadge($sg->kategori_tb_u); @endphp
                                <span class="badge badge-{{ $w }}">{{ $sg->kategori_tb_u }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($sg?->kategori_bb_tb)
                                @php $w = \App\Models\StatusGizi::warnaBadge($sg->kategori_bb_tb); @endphp
                                <span class="badge badge-{{ $w }}">{{ $sg->kategori_bb_tb }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $p->user?->name }}</td>
                        <td>
                            <div class="flex">
                                <a href="{{ route('pengukuran.show', $p) }}" class="btn btn-sm btn-secondary">Detail</a>
                                <a href="{{ route('pengukuran.edit', $p) }}" class="btn btn-sm btn-primary">Ubah</a>
                                <form method="POST" action="{{ route('pengukuran.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus data pengukuran ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif
@endsection
