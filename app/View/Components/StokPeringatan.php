<?php

namespace App\View\Components;

use App\Models\StokVitaminObat;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/** Widget "Peringatan Stok" di dashboard. Hanya tampil untuk Admin (pengelola stok). */
class StokPeringatan extends Component
{
    public function shouldRender(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function render(): View
    {
        return view('components.stok-peringatan', [
            'menipis' => StokVitaminObat::menipis()->count(),
            'mendekati' => StokVitaminObat::mendekatiKedaluwarsa()->count(),
            'kedaluwarsa' => StokVitaminObat::kedaluwarsa()->count(),
        ]);
    }
}
