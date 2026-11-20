@props(['size' => '2.6rem', 'light' => false])
{{-- Logo PosyanduKu: ibu menggendong bayi (animasi ayunan lembut). $light = latar putih untuk area berwarna. --}}
<svg class="pk-logo" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 64 64" role="img" aria-label="Logo PosyanduKu" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="pkLogoBg{{ $light ? 'L' : 'D' }}" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#8b5cf6"/>
            <stop offset="1" stop-color="#5b21b6"/>
        </linearGradient>
    </defs>
    <rect width="64" height="64" rx="18" fill="{{ $light ? '#ffffff' : 'url(#pkLogoBg'.'D)' }}"/>
    <path class="heart" d="M50 14c-1.6-2.4-5-1.2-4.2 1.4.6 1.8 4.2 4 4.2 4s3.6-2.2 4.2-4c.8-2.6-2.6-3.8-4.2-1.4z" fill="{{ $light ? '#c4b5fd' : '#fbcfe8' }}"/>
    <g class="mother">
        {{-- badan/gaun --}}
        <path d="M14 58c0-16 5-25 14-25s14 9 14 25z" fill="{{ $light ? '#7c3aed' : '#ffffff' }}"/>
        {{-- kepala, rambut, sanggul --}}
        <circle cx="28" cy="22" r="7.5" fill="#ffd9c2"/>
        <path d="M20.2 21.5c0-5 3.5-8 8-8s7.8 3 7.8 7.5c-3-.5-6-2.2-8-4.5-1.6 2.6-4.6 4.4-7.8 5z" fill="#4c1d95"/>
        <circle cx="22" cy="14.5" r="3.6" fill="#4c1d95"/>
        <circle cx="25.5" cy="23" r=".9" fill="#4c1d95"/>
        <circle cx="31.5" cy="23" r=".9" fill="#4c1d95"/>
        <path d="M26.5 26c.9.8 2.1.8 3 0" stroke="#d9697a" stroke-width="1.1" fill="none" stroke-linecap="round"/>
    </g>
    <g class="baby">
        {{-- gendongan/selimut bayi --}}
        <ellipse cx="44" cy="41" rx="10.5" ry="8" transform="rotate(-18 44 41)" fill="{{ $light ? '#ddd0fb' : '#e9d5ff' }}"/>
        <circle cx="49" cy="33" r="5.6" fill="#ffd9c2"/>
        <path d="M43.5 32.5c.3-3.6 3.2-5.3 6-5 2.8.3 4.6 2.5 4.6 5-2-1.2-4.2-1.6-6.2-1.6-1.6 0-3 .6-4.4 1.6z" fill="#f9a8d4"/>
        <circle cx="47.4" cy="34" r=".8" fill="#4c1d95"/>
        <circle cx="51" cy="34" r=".8" fill="#4c1d95"/>
        <path d="M48 36.4c.6.5 1.4.5 2 0" stroke="#d9697a" stroke-width=".9" fill="none" stroke-linecap="round"/>
    </g>
    {{-- lengan ibu menopang bayi --}}
    <path d="M30 40c5 6 12 7 19 3" stroke="#ffd9c2" stroke-width="3.6" fill="none" stroke-linecap="round"/>
</svg>
