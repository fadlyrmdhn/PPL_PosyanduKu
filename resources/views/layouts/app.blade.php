<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PosyanduKu')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; color: #333; }
        .navbar { background: #2563eb; color: white; padding: 0.75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-right: 1.25rem; font-weight: 500; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.12); padding: 1.5rem; margin-bottom: 1.25rem; }
        .btn { display: inline-block; padding: .45rem 1rem; border-radius: 6px; font-size: .9rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-secondary { background: #64748b; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-sm { padding: .25rem .6rem; font-size: .8rem; }
        .btn:hover { opacity: .88; }
        h1 { font-size: 1.6rem; margin-bottom: 1rem; }
        h2 { font-size: 1.25rem; margin-bottom: .75rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .6rem .8rem; border-bottom: 1px solid #e5e7eb; }
        th { background: #f1f5f9; font-weight: 600; }
        .alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-weight: 600; margin-bottom: .3rem; font-size: .9rem; }
        input, select, textarea { width: 100%; padding: .5rem .75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: .9rem; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,.2); }
        .error-msg { color: #dc2626; font-size: .8rem; margin-top: .25rem; }
        .badge { display: inline-block; padding: .2rem .55rem; border-radius: 999px; font-size: .78rem; font-weight: 600; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .flex { display: flex; gap: .75rem; align-items: center; flex-wrap: wrap; }
        .mt-1 { margin-top: .5rem; }
        .mb-1 { margin-bottom: .5rem; }
        .text-muted { color: #6b7280; font-size: .88rem; }
        .pagination { display: flex; gap: .5rem; justify-content: center; margin-top: 1rem; }
        .pagination a, .pagination span { padding: .35rem .7rem; border: 1px solid #d1d5db; border-radius: 5px; font-size: .88rem; text-decoration: none; color: #2563eb; }
        .pagination .active { background: #2563eb; color: white; border-color: #2563eb; }
    </style>
</head>
<body>
<nav class="navbar">
    <div>
        <strong>🏥 PosyanduKu</strong>
        <a href="{{ route('menu-mpasi.index') }}" style="margin-left:1.5rem">Menu MPASI</a>
        <a href="{{ route('pengukuran.index') }}">Pengukuran</a>
    </div>
    <div class="text-muted" style="color:rgba(255,255,255,.8); font-size:.88rem;">
        @auth {{ auth()->user()->name }} @endauth
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
