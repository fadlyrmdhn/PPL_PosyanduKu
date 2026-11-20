<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - PosyanduKu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/posyanduku.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-wrap">
    <div class="login-side">
        <div style="position:relative;z-index:1;max-width:460px">
            <div class="brand text-white mb-4"><x-logo size="3rem" :light="true" /> PosyanduKu</div>
            <h2 class="text-white mb-3">Tumbuh sehat dimulai dari posyandu.</h2>
            <p class="mb-4 opacity-75">Catat data balita, pantau pertumbuhan, dan jaga jadwal imunisasi dalam satu tempat.</p>
            <div class="feat"><i class="bi bi-emoji-smile"></i> Data balita dan orang tua/wali terpusat</div>
            <div class="feat"><i class="bi bi-graph-up-arrow"></i> Pemantauan pertumbuhan dan gizi</div>
            <div class="feat"><i class="bi bi-shield-check"></i> Akses sesuai peran pengguna</div>
        </div>
    </div>
    <div class="login-form">
        <div class="login-card">
            <div class="brand mb-4 d-lg-none"><x-logo size="2.6rem" /> PosyanduKu</div>
            <h3 class="mb-1">Selamat datang</h3>
            <p class="text-muted mb-4">Masuk untuk melanjutkan ke PosyanduKu.</p>
            <div class="card"><div class="card-body p-4">
                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Email atau username</label>
                        <div class="search-wrap">
                            <i class="bi bi-person"></i>
                            <input type="text" name="login" value="{{ old('login') }}" class="form-control" placeholder="contoh: admin" autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kata sandi</label>
                        <div class="search-wrap">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi">
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" name="ingat" value="1" class="form-check-input" id="ingat">
                        <label class="form-check-label" for="ingat">Ingat saya</label>
                    </div>
                    <button class="btn btn-success w-100 py-2">Masuk <i class="bi bi-arrow-right"></i></button>
                </form>
            </div></div>
        </div>
    </div>
</div>
</body>
</html>
