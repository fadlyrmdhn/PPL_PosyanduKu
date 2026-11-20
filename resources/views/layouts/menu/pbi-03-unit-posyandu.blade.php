@if (in_array(auth()->user()->role, ['admin', 'kader']))
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('unit-posyandu.*') ? 'active' : '' }}" href="{{ route('unit-posyandu.index') }}"><i class="bi bi-geo-alt"></i> Unit Posyandu</a>
    </li>
@endif
