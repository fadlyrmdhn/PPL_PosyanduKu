<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-megaphone"></i> Pengumuman Terbaru</span>
        <a href="{{ route('pengumuman.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
    </div>
    <ul class="list-group list-group-flush">
        @forelse ($daftar as $p)
            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                <span class="avatar teal"><i class="bi bi-megaphone-fill"></i></span>
                <div class="flex-grow-1">
                    <a href="{{ route('pengumuman.show', $p) }}" class="fw-semibold text-decoration-none">{{ $p->judul }}</a>
                    <div class="text-muted small">{{ $p->tanggalTerbitLabel() }}</div>
                </div>
            </li>
        @empty
            <li class="list-group-item"><div class="empty py-3"><i class="bi bi-megaphone"></i>Belum ada pengumuman.</div></li>
        @endforelse
    </ul>
</div>
