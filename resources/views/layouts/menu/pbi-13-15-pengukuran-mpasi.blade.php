{{-- Menu PBI-13: Pengukuran Pertumbuhan & Status Gizi (Kader + Admin) --}}
@if (in_array(auth()->user()->role, ['admin', 'kader']) && Route::has('pengukuran.index'))
    <li class="menu-title">Pertumbuhan Balita</li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pengukuran.*') ? 'active' : '' }}" href="{{ route('pengukuran.index') }}">
            <i class="bi bi-graph-up-arrow"></i> Pengukuran & Status Gizi
        </a>
    </li>
@endif

{{-- Menu PBI-15: Menu MPASI (Admin saja) --}}
@if (auth()->user()->role === 'admin' && Route::has('menu-mpasi.index'))
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('menu-mpasi.*') ? 'active' : '' }}" href="{{ route('menu-mpasi.index') }}">
            <i class="bi bi-egg-fried"></i> Menu MPASI
        </a>
    </li>
@endif
