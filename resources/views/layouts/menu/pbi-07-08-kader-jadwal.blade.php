{{-- Menu FR-09: Data Kader (Admin saja) --}}
@if (auth()->user()->role === 'admin')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kader.*') ? 'active' : '' }}" href="{{ route('kader.index') }}">
            <i class="bi bi-people"></i> Data Kader
        </a>
    </li>
@endif

{{-- Menu FR-10: Jadwal & Absensi (Admin + Kader) --}}
@if (in_array(auth()->user()->role, ['admin', 'kader']))
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}" href="{{ route('jadwal.index') }}">
            <i class="bi bi-calendar3"></i> Jadwal Kegiatan
        </a>
    </li>
@endif

@if (auth()->user()->role === 'kader')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
            <i class="bi bi-clipboard-check"></i> Kehadiran Saya
        </a>
    </li>
@endif

@if (auth()->user()->role === 'admin')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('absensi.rekap') ? 'active' : '' }}" href="{{ route('absensi.rekap') }}">
            <i class="bi bi-bar-chart-line"></i> Rekap Kehadiran
        </a>
    </li>
@endif
