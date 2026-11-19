<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PosyanduKu') — PosyanduKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --posyandu-primary: #2D5A27;
            --posyandu-primary-dark: #1f401b;
            --posyandu-soft: #eaf4e8;
            --posyandu-soft-border: #cde3c8;
            --posyandu-bg: #f6f8f7;
        }

        body {
            background-color: var(--posyandu-bg);
            color: #1f2937;
            font-size: 0.92rem;
        }

        .app-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 245px;
            flex-shrink: 0;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-box {
            padding: 1rem 1.15rem;
            border-bottom: 1px solid #eef2f0;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background-color: var(--posyandu-primary);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            color: #9ca3af;
            padding: 1rem 1.15rem 0.4rem;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            color: #4b5563;
            padding: 0.58rem 0.85rem;
            margin: 0.12rem 0.7rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: var(--posyandu-soft);
            color: var(--posyandu-primary);
        }

        .sidebar .nav-link.active {
            background-color: var(--posyandu-primary);
            color: #ffffff;
        }

        .sidebar-footer-card {
            margin: 0.85rem;
            padding: 0.75rem;
            border-radius: 10px;
            background-color: var(--posyandu-soft);
            border: 1px solid var(--posyandu-soft-border);
        }

        /* Main Area */
        .main-panel {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.7rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .btn-posyandu {
            background-color: var(--posyandu-primary);
            border-color: var(--posyandu-primary);
            color: #ffffff;
        }

        .btn-posyandu:hover {
            background-color: var(--posyandu-primary-dark);
            border-color: var(--posyandu-primary-dark);
            color: #ffffff;
        }

        .btn-soft-green {
            background-color: var(--posyandu-soft);
            border-color: var(--posyandu-soft-border);
            color: var(--posyandu-primary);
            font-weight: 600;
        }

        .btn-soft-green:hover {
            background-color: #dbeed7;
            color: var(--posyandu-primary-dark);
        }

        .soft-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--posyandu-soft);
            color: var(--posyandu-primary);
            font-weight: 700;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .badge-soft-green {
            background-color: var(--posyandu-soft);
            color: var(--posyandu-primary);
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .app-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }
        }
    </style>
</head>
<body>
<div class="app-wrapper">
    <aside class="sidebar">
        <div>
            <div class="brand-box">
                <div class="brand-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div>
                    <div class="fw-bold lh-1" style="color: var(--posyandu-primary); font-size: 1rem;">PosyanduKu</div>
                    <small class="text-muted" style="font-size: 0.68rem; letter-spacing: 0.04em;">KESEHATAN ANAK</small>
                </div>
            </div>

            <div class="nav-section-title">Layanan Posyandu</div>
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('balita.index') }}">
                    <span><i class="bi bi-grid me-2"></i>Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('balita.*') ? 'active' : '' }}" href="{{ route('balita.index') }}">
                    <span><i class="bi bi-emoji-smile me-2"></i>Data Balita</span>
                </a>
                <a class="nav-link {{ request()->routeIs('orang-tua.*') ? 'active' : '' }}" href="{{ route('orang-tua.index') }}">
                    <span><i class="bi bi-people me-2"></i>Orang Tua / Wali</span>
                </a>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span><i class="bi bi-rulers me-2"></i>Pengukuran Pertumbuhan</span>
                </a>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span><i class="bi bi-clipboard2-pulse me-2"></i>Status Gizi</span>
                    <span class="badge rounded-pill badge-soft-green" style="font-size: 0.68rem;">Lihat</span>
                </a>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span><i class="bi bi-shield-check me-2"></i>Imunisasi &amp; Jadwal</span>
                </a>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span><i class="bi bi-calendar-event me-2"></i>Jadwal Kegiatan</span>
                </a>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span><i class="bi bi-person-check me-2"></i>Absensi Saya</span>
                </a>
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                    <span><i class="bi bi-megaphone me-2"></i>Pengumuman</span>
                </a>
            </nav>
        </div>

        <div class="sidebar-footer-card d-flex align-items-center justify-content-between">
            <div>
                <div class="text-muted" style="font-size: 0.7rem;">Sistem Aktif</div>
                <div class="fw-bold" style="color: var(--posyandu-primary); font-size: 0.82rem;">Puskesmas Melati</div>
            </div>
            <i class="bi bi-arrow-repeat" style="color: var(--posyandu-primary);"></i>
        </div>
    </aside>

    <div class="main-panel">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2 text-muted small">
                <i class="bi bi-hospital" style="color: var(--posyandu-primary);"></i>
                <span>Posyandu Melati RW 04</span>
                <span>&gt;</span>
                <strong class="text-dark">Portal Kader</strong>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-circle">SN</div>
                    <div class="d-none d-sm-block lh-1">
                        <div class="fw-semibold small">Siti Nurhaliza <span class="badge badge-soft-green ms-1">Kader</span></div>
                        <small class="text-muted" style="font-size: 0.72rem;">Posyandu Melati RW 04</small>
                    </div>
                </div>
                <span class="btn btn-sm btn-outline-secondary disabled">
                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                </span>
            </div>
        </header>

        <main class="p-4 flex-grow-1">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>