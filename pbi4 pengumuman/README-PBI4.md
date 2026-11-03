# PBI-4 Pengumuman Posyandu (PosyanduKu)

Sudah disesuaikan dengan repo `PPL_PosyanduKu` (Laravel 13, PHP 8.3, Tailwind 4, MySQL) dan diuji:
`php artisan test` -> 21 test lolos (termasuk uji role, validasi, XSS, dan login sementara).

## Cara memasang

1. Salin semua isi paket ke root repo (timpa `routes/web.php`; hanya 1 blok baris yang berubah, lihat bawah).
2. `php artisan migrate`
3. Data contoh (opsional):
   ```
   php artisan db:seed --class=AkunContohSeeder
   php artisan db:seed --class=PengumumanSeeder
   ```
   Akun contoh (kata sandi `password`): `admin@posyandu.test`, `kader@posyandu.test`, `ortu@posyandu.test`
4. `npm install && npm run build` (atau `npm run dev`), lalu `php artisan serve` dan buka `/login`.
5. Test: `php artisan test`

Satu-satunya file milik repo yang diubah adalah `routes/web.php`, ditambah:
```php
require __DIR__.'/pengumuman.php';
require __DIR__.'/auth-sementara.php'; // hapus setelah PBI-1 di-merge
```

## Yang bersifat SEMENTARA (hapus setelah autentikasi PBI-1 Fadly masuk ke main)

Repo saat ini belum punya login dan kolom `role`, jadi paket menyertakan stand-in agar PBI-4 bisa dicoba:

| File | Keterangan |
| --- | --- |
| `routes/auth-sementara.php`, `app/Http/Controllers/AuthSementaraController.php`, `resources/views/auth-sementara/` | Login/logout sederhana |
| `database/seeders/AkunContohSeeder.php`, `tests/Feature/AuthSementaraTest.php` | Akun contoh dan test-nya |
| `database/migrations/2026_11_02_000000_add_role_to_users_table.php` | Menambah kolom `role`. Aman: dilewati jika kolom sudah ada |
| `resources/views/layouts/app.blade.php` | Layout dasar. Pakai layout tim jika sudah ada |

Yang perlu dicocokkan dengan PBI-1: kolom `users.role` bernilai `admin` / `kader` / `orang_tua`
(jika beda, ubah 3 konstanta di `app/Policies/PengumumanPolicy.php`), dan route bernama `login` harus ada.

## Kesesuaian dengan proposal

| Ketentuan proposal | Implementasi |
| --- | --- |
| FR-06: Admin membuat, mengubah, menghapus pengumuman | CRUD, hanya role admin (`PengumumanPolicy`) |
| FR-06: Orang Tua membaca pengumuman yang dipublikasikan | `index/show` memfilter `status = dipublikasikan`; draft ditolak (403) |
| Tabel 1: Kader hanya Read pada pengumuman | Kader sama dengan Orang Tua (baca yang dipublikasikan saja) |
| ERD: tabel `pengumuman` | Migration `pengumuman` (judul, isi, tanggal_terbit, status, dibuat_oleh) |
| NFR-01 RBAC | Policy + middleware `auth` |
| NFR-03 Bahasa Indonesia | Label, pesan validasi, dan nama bulan berbahasa Indonesia |
| NFR-04 Responsif | Tailwind, tampilan kartu |
| NFR-05 Validasi, XSS, CSRF | `FormRequest`, output di-escape, `@csrf` (ada test XSS) |
| NFR-10 Konfirmasi hapus | Dialog konfirmasi sebelum hapus |
| FR-02 Dashboard: pengumuman terbaru | `Pengumuman::dipublikasikan()->terbaru()->limit(3)->get()` |

## Keputusan yang perlu disepakati tim

Proposal tidak konsisten: FR-06, Tabel 1, dan use case menulis **Admin** sebagai pengelola pengumuman,
sedangkan tabel "Pembagian PBI" menulis **Kader**. Paket ini mengikuti Admin. Jika tim memutuskan Kader
juga boleh mengelola, ubah `create`, `update`, `delete` di `PengumumanPolicy` agar menerima role `kader`,
lalu sesuaikan test `test_orang_tua_dan_kader_tidak_bisa_mengelola_pengumuman`.

## Catatan

- `npm run build` memerlukan internet karena plugin font Laravel mengunduh "Instrument Sans" dari fonts.bunny.net.
- Test mematikan Vite (`withoutVite()`), jadi `php artisan test` jalan tanpa perlu build aset dulu.
