@if (in_array(auth()->user()->role, ['admin', 'kader']))
    <li class="menu-title">Data Dasar</li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('balita.*') ? 'active' : '' }}" href="{{ route('balita.index') }}"><i class="bi bi-emoji-smile"></i> Data Balita</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('orang-tua.*') ? 'active' : '' }}" href="{{ route('orang-tua.index') }}"><i class="bi bi-people"></i> Orang Tua/Wali</a>
    </li>
@endif
