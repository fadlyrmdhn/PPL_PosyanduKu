<?php

namespace App\Http\Controllers;

/** Halaman awal per peran (placeholder, isi dashboard di Sprint 2). */
class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboard.index', ['judul' => 'Beranda Admin']);
    }

    public function kader()
    {
        return view('dashboard.index', ['judul' => 'Beranda Kader']);
    }

    public function orangTua()
    {
        return view('dashboard.index', ['judul' => 'Beranda Orang Tua']);
    }
}
