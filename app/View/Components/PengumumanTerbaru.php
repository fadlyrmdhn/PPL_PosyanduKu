<?php

namespace App\View\Components;

use App\Models\Pengumuman;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/** Widget "Pengumuman Terbaru" untuk dashboard semua peran (hanya yang dipublikasikan). */
class PengumumanTerbaru extends Component
{
    public function render(): View
    {
        return view('components.pengumuman-terbaru', [
            'daftar' => Pengumuman::dipublikasikan()->terbaru()->limit(3)->get(),
        ]);
    }
}
