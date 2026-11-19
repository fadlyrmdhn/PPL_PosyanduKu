# PBI-4 Pengumuman Posyandu (PosyanduKu)

Disusun di atas `main` (Laravel 13, PHP 8.3, Tailwind 4, MySQL) dan diselaraskan dengan ERD serta class diagram proposal.
Diuji pada `main` + paket ini: `php artisan test` -> 31 test lolos (27 pengumuman + 4 login sementara),
ditambah uji alur lewat HTTP (login, tambah, publikasikan, baca per role).

## Cara memasang (di clone `main` yang bersih)

1. Salin semua isi paket ke root repo (menimpa `routes/web.php`, `config/app.php`).
2. `php artisan migrate`
3. Data contoh (opsional):
   ```
   php artisan db:seed --class=AkunContohSeeder
   php artisan db:seed --class=PengumumanSeeder
   ```
   Akun (kata sandi `password`): `admin@posyandu.test`, `kader@posyandu.test`, `ortu@posyandu.test`
4. `npm install && npm run build`, lalu `php artisan serve` dan buka `/login`.
5. `php artisan test`

File milik repo yang berubah (cek diff sebelum commit):
- `routes/web.php`: tambah `require` untuk `pengumuman.php` dan `auth-sementara.php`
- `config/app.php`: zona waktu menjadi `env('APP_TIMEZONE', 'Asia/Jakarta')`

## Kesesuaian dengan proposal

| Ketentuan proposal | Implementasi |
| --- | --- |
| ERD `pengumuman` | `id_pengumuman`, `id_user`, `judul` varchar(150), `isi` text, `status` enum, `tanggal_terbit` datetime. Tanpa `created_at`/`updated_at` (tidak ada di ERD) |
| Class diagram `Pengumuman::publikasikan()` | Ada di model; tombol "Publikasikan" untuk draft |
| Class diagram: User 1 - 0..* Pengumuman ("membuat") | `Pengumuman::pembuat()`; `id_user` dikosongkan (bukan dihapus) jika akun pembuat dihapus |
| FR-06: Admin membuat, mengubah, menghapus | CRUD, hanya Admin (`PengumumanPolicy`) |
| FR-06 & Tabel 1: Orang Tua dan Kader membaca yang dipublikasikan | `index/show` memfilter `status = dipublikasikan`; draft ditolak (403) |
| Use case "Kelola pengumuman" (Admin), "Melihat pengumuman" (Orang Tua) | Sesuai |
| NFR-01 RBAC | Policy + middleware `auth`; memakai `User::hasRole()` jika PBI-1 menyediakannya |
| NFR-03 Bahasa Indonesia | Label, pesan validasi, nama bulan, dan jam "WIB" |
| NFR-04 Responsif | Tailwind, tampilan kartu |
| NFR-05 Validasi, XSS, CSRF | `FormRequest`, output di-escape, `@csrf` (ada test XSS) |
| NFR-10 Konfirmasi hapus | Dialog konfirmasi |
| FR-02 Dashboard: pengumuman terbaru | `Pengumuman::dipublikasikan()->terbaru()->limit(3)->get()` |

Nilai enum `status`: proposal tidak menyebutkannya, jadi dipakai `draft` dan `dipublikasikan`.

## Kompatibilitas dengan PBI-1 (autentikasi, Fadly)

`main` saat ini belum memiliki login dan kolom `role`, sehingga paket menyertakan stand-in SEMENTARA:

| File | Keterangan |
| --- | --- |
| `routes/auth-sementara.php`, `app/Http/Controllers/AuthSementaraController.php`, `resources/views/auth-sementara/` | Login/logout sederhana |
| `database/seeders/AkunContohSeeder.php`, `tests/Feature/AuthSementaraTest.php` | Akun contoh dan test-nya |
| `database/migrations/2026_11_02_000000_add_role_to_users_table.php` | Menambah `role` (enum admin/kader/orang_tua). Dilewati otomatis jika kolom sudah ada |
| `resources/views/layouts/app.blade.php` | Layout dasar. Pakai layout tim jika sudah ada |

Hapus file-file tersebut dan dua baris `require` di `routes/web.php` setelah PBI-1 di-merge. Paket ini sudah dibuat toleran terhadap skema ERD:
- Kunci asing `pengumuman.id_user` otomatis menunjuk `users.id_user` jika PBI-1 memakai `id_user` (diuji), atau `users.id` jika tidak.
- Nama pembuat dibaca dari `users.nama` atau `users.name`.
- Cek peran memakai `User::hasRole()` jika ada, jika tidak memakai kolom `role`. Nilai peran yang dipakai: `admin`, `kader`, `orang_tua`.
- Route bernama `login` harus tetap ada.

## Catatan

- **Zona waktu:** Laravel bawaan memakai UTC. Pukul 00.00-07.00 WIB, "hari ini" di aplikasi masih tanggal kemarin, sehingga waktu terbit otomatis meleset. Karena itu `config/app.php` diubah ke `Asia/Jakarta`; ini berlaku untuk semua fitur, jadi kabari anggota tim.
- **Pengelola pengumuman:** FR-06, Tabel 1, dan use case menulis **Admin**, sedangkan tabel "Pembagian PBI" menulis **Kader**. Paket ini mengikuti Admin. Jika Kader juga boleh mengelola, ubah `create`, `update`, `delete` di `PengumumanPolicy` dan test `test_orang_tua_dan_kader_tidak_bisa_mengelola_pengumuman`.
- `npm run build` memerlukan internet (plugin font Laravel mengunduh font). Test tidak bergantung pada build aset.
