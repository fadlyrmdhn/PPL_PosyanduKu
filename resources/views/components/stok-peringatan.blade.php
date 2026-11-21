<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-capsule"></i> Peringatan Stok</span>
        <a href="{{ route('stok-vitamin-obat.index') }}" class="btn btn-sm btn-outline-primary">Kelola stok</a>
    </div>
    @if ($menipis + $mendekati + $kedaluwarsa === 0)
        <div class="card-body"><div class="empty py-3"><i class="bi bi-check-circle"></i>Semua stok vitamin dan obat aman.</div></div>
    @else
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('stok-vitamin-obat.index', ['peringatan' => 'menipis']) }}" class="text-decoration-none">Stok menipis</a>
                <span class="pk-badge warm">{{ $menipis }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('stok-vitamin-obat.index', ['peringatan' => 'mendekati']) }}" class="text-decoration-none">Mendekati kedaluwarsa</a>
                <span class="pk-badge teal">{{ $mendekati }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('stok-vitamin-obat.index', ['peringatan' => 'kedaluwarsa']) }}" class="text-decoration-none">Sudah kedaluwarsa</a>
                <span class="pk-badge bg-danger-subtle text-danger-emphasis">{{ $kedaluwarsa }}</span>
            </li>
        </ul>
    @endif
</div>
