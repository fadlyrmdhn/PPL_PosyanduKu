<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PosyanduKu')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand navbar-dark bg-success mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('balita.index') }}">PosyanduKu</a>
        {{-- Menu dimuat otomatis dari resources/views/layouts/menu/*.blade.php (satu file per fitur) --}}
        <div class="navbar-nav">
            @foreach (glob(resource_path('views/layouts/menu/*.blade.php')) as $menu)
                @include('layouts.menu.'.basename($menu, '.blade.php'))
            @endforeach
        </div>
    </div>
</nav>
<div class="container pb-5">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @yield('content')
</div>
</body>
</html>
