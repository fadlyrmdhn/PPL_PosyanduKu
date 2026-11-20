@if (auth()->user()->role === 'admin')
    <li class="menu-title">Logistik</li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('stok-vitamin-obat.*') ? 'active' : '' }}" href="{{ route('stok-vitamin-obat.index') }}"><i class="bi bi-capsule"></i> Stok Vitamin/Obat</a>
    </li>
@endif
