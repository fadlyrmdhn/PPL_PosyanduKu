<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuMpasiRequest;
use App\Models\MenuMpasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller untuk mengelola Menu MPASI (FR-17).
 * Akses: Admin
 */
class MenuMpasiController extends Controller
{
    /**
     * F-17.2 Daftar menu MPASI dengan filter rentang usia.
     */
    public function index(Request $request): View
    {
        $rentang = $request->query('rentang'); // '6-8', '9-11', '12-23'

        $query = MenuMpasi::query()->orderBy('usia_min_bulan');

        if ($rentang && preg_match('/^(\d+)-(\d+)$/', $rentang, $m)) {
            $min = (int) $m[1];
            $max = (int) $m[2];
            // Tampilkan menu yang rentang usianya overlap dengan filter
            $query->where('usia_min_bulan', '<=', $max)
                  ->where('usia_max_bulan', '>=', $min);
        }

        $menuList = $query->paginate(12)->withQueryString();

        $rentangOptions = [
            '6-8'   => '6–8 bulan',
            '9-11'  => '9–11 bulan',
            '12-23' => '12–23 bulan',
        ];

        return view('menu_mpasi.index', compact('menuList', 'rentang', 'rentangOptions'));
    }

    /**
     * Form tambah menu baru.
     */
    public function create(): View
    {
        return view('menu_mpasi.create');
    }

    /**
     * F-17.1 Simpan menu MPASI baru.
     */
    public function store(MenuMpasiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('mpasi', 'public');
        }

        MenuMpasi::create($data);

        return redirect()->route('menu-mpasi.index')
            ->with('success', 'Menu MPASI berhasil ditambahkan.');
    }

    /**
     * Detail menu MPASI.
     */
    public function show(MenuMpasi $menuMpasi): View
    {
        return view('menu_mpasi.show', compact('menuMpasi'));
    }

    /**
     * Form ubah menu.
     */
    public function edit(MenuMpasi $menuMpasi): View
    {
        return view('menu_mpasi.edit', compact('menuMpasi'));
    }

    /**
     * F-17.1 Simpan perubahan menu MPASI.
     */
    public function update(MenuMpasiRequest $request, MenuMpasi $menuMpasi): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($menuMpasi->gambar) {
                Storage::disk('public')->delete($menuMpasi->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('mpasi', 'public');
        } else {
            unset($data['gambar']);
        }

        $menuMpasi->update($data);

        return redirect()->route('menu-mpasi.show', $menuMpasi)
            ->with('success', 'Menu MPASI berhasil diperbarui.');
    }

    /**
     * F-17.1 Hapus menu MPASI.
     */
    public function destroy(MenuMpasi $menuMpasi): RedirectResponse
    {
        if ($menuMpasi->gambar) {
            Storage::disk('public')->delete($menuMpasi->gambar);
        }

        $menuMpasi->delete();

        return redirect()->route('menu-mpasi.index')
            ->with('success', 'Menu MPASI berhasil dihapus.');
    }
}
