<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PosyanduKu') - PosyanduKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/posyanduku.css') }}" rel="stylesheet">
</head>
<body>
<header class="topbar d-flex justify-content-between align-items-center">
    <a class="brand" href="{{ url('/') }}"><x-logo size="2.4rem" /> PosyanduKu</a>
    <div class="d-flex align-items-center gap-3">
        <div class="user-chip">
            <span class="avatar teal">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</span>
            <div class="d-none d-sm-block">
                <strong>{{ auth()->user()->nama }}</strong>
                <small>{{ str_replace('_', ' ', auth()->user()->role) }}</small>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</header>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 sidebar">
            <ul class="nav flex-column gap-1">
                {{-- Menu dimuat otomatis dari resources/views/layouts/menu/*.blade.php (satu file per fitur) --}}
                @foreach (glob(resource_path('views/layouts/menu/*.blade.php')) as $menu)
                    @include('layouts.menu.'.basename($menu, '.blade.php'))
                @endforeach
            </ul>
        </nav>
        <main class="col-md-9 col-lg-10 p-3 p-lg-4">
            @if (session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
