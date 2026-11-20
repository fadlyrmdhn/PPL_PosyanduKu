<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" href="{{ route(auth()->user()->homeRoute()) }}"><i class="bi bi-house-heart"></i> Beranda</a>
</li>
