{{-- Menu PBI-10 & PBI-11: Vaksin, Imunisasi, Vitamin, Jadwal --}}

{{-- Kader: kelola semua fitur --}}
@if (auth()->user()->role === 'kader')
    <li class="menu-title">Kesehatan Balita</li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('vaksin.*') ? 'active' : '' }}" href="{{ route('vaksin.index') }}">
            <i class="bi bi-capsule-pill"></i> Master Vaksin
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('imunisasi.*') ? 'active' : '' }}" href="{{ route('imunisasi.index') }}">
            <i class="bi bi-syringe"></i> Imunisasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pemberian-vitamin.*') ? 'active' : '' }}" href="{{ route('pemberian-vitamin.index') }}">
            <i class="bi bi-capsule"></i> Pemberian Vitamin
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('jadwal-imunisasi.*') ? 'active' : '' }}" href="{{ route('jadwal-imunisasi.index') }}">
            <i class="bi bi-calendar2-heart"></i> Jadwal Imunisasi
        </a>
    </li>
@endif

{{-- Orang Tua: hanya lihat data anak sendiri --}}
@if (auth()->user()->role === 'orang_tua')
    <li class="menu-title">Kesehatan Anak</li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('anak.*') ? 'active' : '' }}" href="{{ route('anak.index') }}">
            <i class="bi bi-syringe"></i> Imunisasi Anak
        </a>
    </li>
@endif
