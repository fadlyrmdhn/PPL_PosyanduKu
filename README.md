# PosyanduKu

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.4%20LTS-4479A1?logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/status-dalam%20pengembangan-yellow)

PosyanduKu adalah sistem informasi posyandu berbasis web untuk mencatat dan memantau data balita, pengukuran pertumbuhan, status gizi, imunisasi, serta informasi untuk orang tua. Dibangun dengan **Laravel 13**, **Blade**, dan **MySQL**.

Proyek ini adalah tugas besar mata kuliah Proyek Perangkat Lunak (SDG 3: Good Health and Well-being). Dokumen ini adalah **acuan bersama tim**: semua anggota memakai versi, cara setup, dan aturan kerja yang sama.

## Fitur Utama

| Kelompok | Isi |
|----------|-----|
| Data dasar | Data balita dan orang tua/wali, ibu hamil, wilayah dan unit posyandu |
| Gizi dan tumbuh kembang | Pengukuran pertumbuhan, status gizi otomatis, perkembangan anak, menu MPASI |
| Imunisasi | Master vaksin, pencatatan imunisasi dan jadwalnya, data KIPI |
| Kegiatan dan pelaporan | Data kader, jadwal kegiatan, absensi kader, laporan bulanan, stok vitamin dan obat |
| Informasi | Pengumuman kegiatan dan artikel edukasi untuk orang tua |

Pengguna terdiri dari tiga peran: **Admin**, **Kader**, dan **Orang Tua/Wali**.

## Daftar Isi

1. [Prasyarat](#1-prasyarat)
2. [Instalasi](#2-instalasi)
3. [Akun Demo](#3-akun-demo-lingkungan-lokal)
4. [Alur Kerja Git](#4-alur-kerja-git)
5. [Konvensi Kode dan Tampilan](#5-konvensi-kode-dan-tampilan)
6. [Pembagian Tugas dan Sprint](#6-pembagian-tugas-dan-sprint)
7. [Arsitektur Singkat](#7-arsitektur-singkat)
8. [Masalah Umum](#8-masalah-umum)

---

## 1. Prasyarat

Pastikan komputermu memenuhi semua syarat ini sebelum instalasi:

- **PHP 8.3, 8.4, atau 8.5**
  - Ekstensi yang dibutuhkan: `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `ctype`, `json`, `tokenizer`, `xml`, `curl`.
  - > [!IMPORTANT]
  - > Cek `php -v` di **terminal Laragon** atau terminal VS Code, bukan hanya di panel Laragon. PHP di bawah 8.3 tidak bisa menjalankan Laravel 13.
- **Composer 2.x**
- **MySQL 8.4 LTS** (bawaan Laragon). MySQL 8.0 sudah tidak lagi didukung resmi.
- **Git** dan **GitHub Desktop** (opsional)
- **Laragon** (disarankan di Windows) dan **VS Code**
- **Node.js** belum wajib. Tampilan saat ini memakai Bootstrap 5 lewat CDN (butuh internet), bukan Vite.

> [!TIP]
> Jangan simpan repo di folder yang disinkronkan OneDrive. Folder `vendor` berisi ribuan file dan bisa membuat Git lambat atau mengunci file. Taruh repo di `C:\laragon\www` atau folder lain di luar OneDrive.

---

## 2. Instalasi

Setiap anggota melakukan langkah ini **sekali** di komputernya masing-masing.

### Langkah 1: Clone repository
```bash
git clone https://github.com/fadlyrmdhn/PPL_PosyanduKu.git
cd PPL_PosyanduKu
git checkout develop
```

### Langkah 2: Install dependensi PHP
```bash
composer install
```

### Langkah 3: Siapkan environment
Salin `.env.example` menjadi `.env`.

```bash
# Terminal Laragon / Git Bash
cp .env.example .env

# Windows CMD
copy .env.example .env
```

Buat application key:
```bash
php artisan key:generate
```

### Langkah 4: Siapkan database
1. Nyalakan Laragon (**Start All**) sampai MySQL berjalan.
2. Buat database kosong bernama **`posyanduku`** dengan collation `utf8mb4_unicode_ci`:
   ```bash
   mysql -u root -e "CREATE DATABASE posyanduku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
   Atau lewat HeidiSQL (tombol **Database** di Laragon).
3. Pastikan bagian database di `.env` seperti ini (sudah terisi dari `.env.example`):
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=posyanduku
   DB_USERNAME=root
   DB_PASSWORD=
   ```

> Nama database wajib sama di semua komputer (`posyanduku`, huruf kecil) supaya perintah dan konfigurasi tim seragam.

### Langkah 5: Jalankan migration dan seeder
```bash
php artisan migrate --seed
```
- Seeder membuat 3 akun contoh, satu wilayah, satu unit posyandu, dan satu data kader (lihat bagian 3).
- Jangan menjalankan `php artisan db:seed` dua kali, karena email akun contoh harus unik dan akan error.
- Untuk mengulang dari nol (**menghapus semua data lokal**): `php artisan migrate:fresh --seed`.

### Langkah 6: Jalankan aplikasi
```bash
php artisan serve
```
Buka `http://localhost:8000`. Laragon harus dalam keadaan menyala.

---

## 3. Akun Demo (Lingkungan Lokal)

Semua akun contoh memakai kata sandi `password`.

| Peran | Email | Kata sandi |
|-------|-------|------------|
| **Admin** | `admin@posyanduku.test` | `password` |
| **Kader** | `kader@posyanduku.test` | `password` |
| **Orang Tua** | `ortu@posyanduku.test` | `password` |

Data contoh lain dari seeder: wilayah **Kelurahan Contoh** dan unit **Posyandu Melati (RW 01)**.

> [!WARNING]
> Seeder ini hanya untuk pengembangan. Jangan dipakai di server produksi dengan kata sandi `password`.

> Catatan: halaman login dan hak akses per peran masih dalam pengerjaan. Saat ini halaman CRUD yang sudah jadi masih bisa dibuka tanpa login.

---

## 4. Alur Kerja Git

### Jenis branch
| Branch | Fungsi |
|--------|--------|
| `main` | Versi stabil yang siap dikumpulkan. Hanya diisi dari `develop` di akhir sprint. Jangan push langsung. |
| `develop` | Branch integrasi, tempat semua fitur digabung. Jangan push langsung. |
| `feature/pbi-XX-<nama>` | Satu branch untuk **satu PBI**. |
| `feature/<nama>` | Pekerjaan di luar PBI, yaitu `setup-merge`, `login-logout`, dan `dashboard`. |
| `fix/pbi-XX-<nama>` | Perbaikan bug pada PBI yang sudah digabung. |
| `docs/<nama>` | Perubahan dokumentasi saja. |

**Aturan nama branch:** huruf kecil semua, kata dipisah tanda hubung, tanpa spasi, nomor PBI selalu **dua digit** (`pbi-01`, bukan `pbi-1`). Nama branch memakai **nomor PBI**, bukan nomor kartu Jira, karena nomor kartu Jira tidak sama dengan nomor PBI.

### Daftar branch kerja
Total **18 branch kerja** selama proyek (15 PBI dan 3 non-PBI), ditambah 2 branch permanen. Yang aktif bersamaan hanya sekitar 5 (satu per orang), karena branch yang sudah digabung dihapus.

| PIC | Sprint 1 | Sprint 2 |
|-----|----------|----------|
| Fadly | `feature/setup-merge`, `feature/pbi-01-data-balita`, `feature/pbi-03-wilayah-unit-posyandu` | `feature/pbi-02-data-ibu-hamil`, `feature/login-logout`, `feature/dashboard` |
| Rafa | `feature/pbi-04-pengumuman`, `feature/pbi-05-stok-vitamin-obat` | `feature/pbi-06-artikel-edukasi` |
| Rifqi | `feature/pbi-07-data-kader`, `feature/pbi-08-jadwal-absensi` | `feature/pbi-09-laporan-bulanan` |
| Fathin | `feature/pbi-10-master-vaksin`, `feature/pbi-11-imunisasi-vitamin` | `feature/pbi-12-kipi` |
| Haisel | `feature/pbi-13-pengukuran-status-gizi`, `feature/pbi-15-menu-mpasi` | `feature/pbi-14-perkembangan-anak` |

### Cara mengerjakan satu PBI
1. Perbarui branch integrasi:
   ```bash
   git checkout develop
   git pull
   ```
   Kalau ada migration baru dari anggota lain, jalankan `php artisan migrate`.
2. Buat branch kerja:
   ```bash
   git checkout -b feature/pbi-01-data-balita
   ```
3. Kerjakan, lalu commit kecil dan sering (lihat konvensi commit di bawah).
4. **Sebelum membuka Pull Request**, ambil perubahan terbaru dari `develop`:
   ```bash
   git pull origin develop
   ```
   Kalau ada konflik, selesaikan di **branch sendiri**. Di VS Code pilih **Accept Both Changes** bila dua-duanya memang perlu, simpan, lalu `git add .` dan `git commit`.
5. Kirim ke GitHub:
   ```bash
   git push -u origin feature/pbi-01-data-balita
   ```
6. Buka **Pull Request** ke `develop`. Judulnya memakai format commit, misalnya `PBI-01 FEAT: CRUD data balita dan orang tua/wali`.
7. PM meninjau memakai checklist di bawah, lalu menggabungkan dengan **Create a merge commit** (jangan *squash*, supaya semua commit tetap terlihat di riwayat). Hapus branch setelah digabung.
8. Perbarui kartu Jira: To Do, In Progress, In Review, Done.

### Konvensi commit
```
<SCOPE> <TIPE>: <pesan singkat>
```

**SCOPE** menunjukkan bagian yang dikerjakan:
- Untuk PBI: nomor PBI dua digit, misalnya `PBI-01`.
- Di luar PBI: `LOGIN`, `DASHBOARD`, `SETUP`, atau `DOCS`.

**TIPE** commit:
| Tipe | Dipakai untuk |
|------|---------------|
| `FEAT` | Fitur atau halaman baru |
| `FIX` | Memperbaiki bug |
| `REFACTOR` | Merapikan kode tanpa mengubah perilaku |
| `DOCS` | Dokumentasi |
| `TEST` | Menambah atau mengubah tes |
| `CHORE` | Pekerjaan pendukung (konfigurasi, dependensi, seeder) |

**Aturan pesan:**
1. Bahasa Indonesia, diawali kata kerja (`Tambah`, `Ubah`, `Perbaiki`, `Hapus`, `Rapikan`).
2. Singkat (maksimal sekitar 70 karakter) dan tanpa titik di akhir.
3. Satu commit untuk satu langkah yang jelas.

**Contoh yang benar:**
```
PBI-01 FEAT: Tambah migration dan model balita
PBI-01 FEAT: Tambah halaman daftar dan pencarian balita
PBI-01 FEAT: Tambah form tambah dan ubah balita
PBI-01 FIX: Perbaiki validasi NIK yang duplikat
PBI-04 FEAT: Tambah CRUD pengumuman
LOGIN FEAT: Tambah middleware peran
SETUP CHORE: Tambah seeder akun awal
DOCS DOCS: Perbarui README
```

**Contoh yang salah:** `update`, `fix bug`, `coba lagi`, `final`, `asdf`.

### Aturan
1. **Jangan** commit file `.env` dan folder `vendor` (sudah diabaikan oleh `.gitignore`).
2. **Jangan** mengubah file migration yang sudah di-push. Buat migration baru untuk perubahan tabel.
3. **Jangan** push langsung ke `main` atau `develop`. Gabungkan lewat Pull Request.
4. Pastikan `git config user.email` sama dengan email akun GitHub-mu, supaya commit tercatat atas namamu.
5. `git pull` dulu sebelum mulai bekerja, dan sebelum push.
6. Satu branch hanya untuk satu PBI. Jangan mencampur dua PBI dalam satu branch.
7. Kerjakan di branch milikmu sendiri, dan jangan commit atas nama orang lain.
8. Jangan mengubah file bersama (lihat bagian berikut) tanpa izin PM.

### Aturan merge dan file bersama

Konflik hampir selalu muncul di file yang diubah banyak orang. Karena itu route dan menu navbar dipisah **satu file per fitur**, supaya tiap anggota bekerja di file berbeda dan tidak saling bentrok.

| Hal | Aturan |
|-----|--------|
| Route | Satu file per fitur: `routes/fitur/pbi-XX-<nama>.php`. **Jangan mengubah** `routes/web.php`, karena file itu otomatis memuat semua file di folder `routes/fitur`. |
| Menu navbar | Satu file per fitur: `resources/views/layouts/menu/pbi-XX-<nama>.blade.php`. **Jangan mengubah** `layouts/app.blade.php`. |
| Seeder | Tambahkan satu baris `$this->call(...)` di `DatabaseSeeder.php`. Isi seeder ditulis di file seeder sendiri. |
| File dasar | `User.php`, `AppServiceProvider.php`, `config/*`, `composer.json`, dan `composer.lock` hanya diubah lewat PR kecil terpisah dengan scope `SETUP`, setelah memberi tahu PM. |
| Migration | Yang sudah digabung tidak diubah, buat migration baru. Tabel yang punya foreign key ke tabel milik orang lain menunggu tabel itu digabung dulu. |
| Ukuran PR | Satu PBI per PR, kecil, dan sering. Jangan menumpuk berhari-hari. |

Contoh file milik satu fitur (PBI-04 Pengumuman):

```php
<?php
// File: routes/fitur/pbi-04-pengumuman.php

use App\Http\Controllers\PengumumanController;
use Illuminate\Support\Facades\Route;

Route::resource('pengumuman', PengumumanController::class);
```

```blade
{{-- resources/views/layouts/menu/pbi-04-pengumuman.blade.php --}}
<a class="nav-link" href="{{ route('pengumuman.index') }}">Pengumuman</a>
```

**Pengaturan awal:** struktur ini aktif setelah PM menggabungkan branch `feature/setup-merge` (PR `SETUP CHORE: Pisahkan route dan menu per fitur`). Anggota lain mulai mengerjakan PBI setelah PR itu digabung dan mereka sudah menjalankan `git pull`.

**Urutan merge:** PR yang membawa tabel induk digabung lebih dulu, misalnya master vaksin sebelum imunisasi, dan data balita sebelum pengukuran.

### Checklist PM untuk Pull Request

Lakukan untuk setiap PR sebelum digabung:

1. Judul PR mengikuti format commit (`PBI-04 FEAT: CRUD pengumuman`), dan targetnya `develop`.
2. Buka tab **Files changed**: hanya file milik PBI itu yang berubah, tanpa `.env`, tanpa `vendor`, dan tanpa file bersama.
3. Ambil branch-nya, jalankan `php artisan migrate`, lalu tes fiturnya (tambah, lihat, ubah, hapus, dan validasi).
4. Tampilan sesuai panduan UI, dan label berbahasa Indonesia.
5. Gabungkan dengan **Create a merge commit**, lalu hapus branch.
6. Geser kartu Jira ke **Done**.
7. Kabari tim untuk `git pull` kalau PR itu membawa migration atau perubahan dasar.


---

## 5. Konvensi Kode dan Tampilan

| Hal | Aturan | Contoh |
|-----|--------|--------|
| Nama tabel | Tunggal, `snake_case`, sesuai ERD proposal | `balita`, `orang_tua`, `unit_posyandu` |
| Model | `PascalCase`, tulis `$table` secara eksplisit, isi `$fillable` dan relasinya | `Balita`, `protected $table = 'balita';` |
| Controller | `<Fitur>Controller`, resource controller, validasi di controller | `BalitaController` |
| Route | `Route::resource` di file sendiri `routes/fitur/pbi-XX-<nama>.php`, nama route memakai tanda hubung. Jangan mengubah `routes/web.php` | `orang-tua.index` |
| View | Folder `snake_case` per fitur di `resources/views`, form dipisah ke `_form.blade.php`. Menu navbar di file sendiri `layouts/menu/pbi-XX-<nama>.blade.php` | `balita/index.blade.php` |
| Layout | Semua halaman memakai `@extends('layouts.app')` | |
| Foreign key | Data master yang masih dipakai tidak boleh terhapus | `restrictOnDelete()` |
| Bahasa | Label, tombol, dan pesan di antarmuka memakai Bahasa Indonesia | |

Tabel inti yang sudah tersedia di `develop`: `users` (dengan kolom `role` dan `status`), `wilayah`, `unit_posyandu`, `orang_tua`, `balita`, dan `kader`. Model dan relasinya ada di `app/Models`. Tabel fitur baru dibuat oleh pemilik fiturnya, dengan migration yang berurutan (tabel induk lebih dulu daripada tabel yang punya foreign key).

### Panduan tampilan (UI)

Tampilan memakai **Bootstrap 5.3.3 lewat CDN** dan satu layout bersama, `resources/views/layouts/app.blade.php`. Aturan berikut dibuat supaya semua halaman dari lima orang terlihat seragam.

| Elemen | Aturan |
|--------|--------|
| Struktur halaman | `@extends('layouts.app')`, lalu `@section('title', ...)` dan `@section('content')` |
| Judul halaman | `<h4>`. Di halaman daftar, judul di kiri dan tombol tambah di kanan (`d-flex justify-content-between align-items-center mb-3`) |
| Daftar data | Tabel di dalam `card`, kelas `table table-hover mb-0`, dibungkus `table-responsive`, dengan paginasi 10 baris dan `{{ $data->links() }}` |
| Pencarian | `input-group` di atas tabel, memakai parameter `q` |
| Form | Di dalam `card` dan `card-body`, grid `row g-3` dengan `col-md-6`, label `form-label`, nilai lama lewat `old()` |
| Validasi | Kelas `is-invalid` pada input dan pesan `invalid-feedback` di bawahnya |
| Tombol | Tambah dan Simpan: `btn-success`. Ubah: `btn-outline-primary`. Perbarui: `btn-primary`. Detail: `btn-outline-secondary`. Hapus: `btn-outline-danger`. Batal: `btn-secondary` |
| Hapus | Selalu lewat form `DELETE` dengan `onsubmit="return confirm('Hapus data ini?')"`. Data yang masih dipakai data lain tidak boleh dihapus, tampilkan pesan error |
| Pesan | Sukses lewat `session('success')` dengan `alert-success`, error lewat `session('error')` dengan `alert-danger`. Kalimatnya: `Data ... berhasil ditambahkan/diperbarui/dihapus.` |
| Data kosong | Satu baris tabel berisi `Belum ada data ...` dengan `text-center text-muted py-4` |
| Isi teks | Bahasa Indonesia. Tanggal ditulis `d-m-Y`. Kolom yang kosong ditampilkan sebagai `-` |
| Warna | Tema hijau (navbar `bg-success`) dan latar `bg-light`. Jangan menambah warna sendiri |
| CSS dan JS | Jangan menambah file CSS atau JS sendiri. Pakai kelas utilitas Bootstrap |

**Menu navbar:** jangan mengubah `layouts/app.blade.php`. Buat satu file menu sendiri di `resources/views/layouts/menu/pbi-XX-<nama>.blade.php` (lihat bagian aturan merge), dan menu akan muncul otomatis.

**Contoh acuan:** setelah PBI-01 digabung, view di `resources/views/balita/` dan `resources/views/orang_tua/` menjadi contoh untuk semua fitur CRUD. Salin strukturnya (`index`, `_form`, `create`, `edit`, dan `show` kalau perlu), lalu ganti isinya.


---

## 6. Pembagian Tugas dan Sprint

### Anggota
| Nama | Peran |
|------|-------|
| Fadly Ramadhan Al Hafizh | Full Stack Developer, Project Manager |
| Muhammad Rafa Aimar | Full Stack Developer |
| Muhammad Rifqi Rizqillah | Full Stack Developer |
| Akhmad Fathinnajilah | Full Stack Developer |
| Haisel Muhamad Gunawan | Full Stack Developer |

### Daftar PBI
| PBI | Fitur | PIC | Sprint |
|-----|-------|-----|--------|
| 1 | Data balita dan orang tua/wali | Fadly | 1 |
| 2 | Data ibu hamil dan riwayat pemeriksaan | Fadly | 2 |
| 3 | Data wilayah dan unit posyandu | Fadly | 1 |
| 4 | Pengumuman kegiatan | Rafa | 1 |
| 5 | Stok vitamin dan obat | Rafa | 1 |
| 6 | Artikel edukasi | Rafa | 2 |
| 7 | Data kader | Rifqi | 1 |
| 8 | Jadwal kegiatan dan absensi kader | Rifqi | 1 |
| 9 | Laporan bulanan | Rifqi | 2 |
| 10 | Master vaksin | Fathin | 1 |
| 11 | Imunisasi, vitamin, dan jadwal imunisasi | Fathin | 1 |
| 12 | Data KIPI | Fathin | 2 |
| 13 | Pengukuran pertumbuhan dan status gizi otomatis | Haisel | 1 |
| 14 | Pemantauan perkembangan anak | Haisel | 2 |
| 15 | Menu MPASI | Haisel | 1 |

Login/logout dan dashboard wajib ada tetapi tidak dihitung sebagai PBI (Fadly).
Admin memiliki akses penuh pada seluruh fitur.

### Pembagian sprint
Proyek dikerjakan dalam **2 sprint**, setelah tahap persiapan.

| Tahap | Periode | PBI dan kegiatan |
|-------|---------|------------------|
| Persiapan | 19-30 Okt 2026 | Perancangan, setup repository, pembagian tugas, dan persiapan environment |
| Sprint 1 | 2-27 Nov 2026 | PBI-1, 3, 4, 5, 7, 8, 10, 11, 13, dan 15 (2 PBI per orang). Fokus ke fitur dasar yang dipakai fitur lain: balita, wilayah, vaksin, dan imunisasi |
| Sprint 2 | 30 Nov-18 Des 2026 | PBI-2, 6, 9, 12, dan 14 (1 PBI per orang), dashboard, login/logout, lalu pengujian, UAT, perbaikan bug, dokumentasi, dan deployment oleh seluruh tim |

### Kartu Jira yang sudah ada
| Kartu | PBI | Fitur | PIC |
|-------|-----|-------|-----|
| POSYANDUKU-1 | PBI-04 | Pengumuman | Rafa |
| POSYANDUKU-2 | PBI-01 | Data balita | Fadly |
| POSYANDUKU-3 | PBI-07 | Data kader | Rifqi |
| POSYANDUKU-4 | PBI-10 | Master vaksin | Fathin |
| POSYANDUKU-5 | PBI-15 | Menu MPASI | Haisel |

Nomor kartu Jira tidak sama dengan nomor PBI. Supaya tidak membingungkan, ubah judul kartu menjadi `PBI-XX <fitur>` (misalnya `PBI-01 Data balita`), dan gunakan **nomor PBI** untuk nama branch dan pesan commit.

Kartu untuk PBI lainnya dibuat di Jira oleh PIC masing-masing atau oleh PM.

---

## 7. Arsitektur Singkat

- **Framework**: Laravel 13 (pola MVC)
- **Tampilan**: Blade dengan Bootstrap 5 (CDN)
- **Basis data**: MySQL 8.4 lewat Eloquent ORM
- **Pengguna**: Admin, Kader, Orang Tua/Wali (peran disimpan di kolom `users.role`)
- **Struktur**:
  ```
  app/Models/                 model dan relasi
  app/Http/Controllers/       controller per fitur
  database/migrations/        struktur tabel
  database/seeders/           data awal
  resources/views/            tampilan Blade (layouts, lalu folder per fitur)
  routes/web.php              memuat semua route fitur (jangan diubah)
  routes/fitur/               satu file route per fitur
  resources/views/layouts/    layout bersama dan menu/ (satu file menu per fitur)
  ```

---

## 8. Masalah Umum

| Pesan atau gejala | Penyebab dan solusi |
|-------------------|---------------------|
| `'php' is not recognized` | Pakai terminal Laragon, atau di Laragon klik kanan **Tools → Path → Add Laragon to Path**, lalu tutup dan buka lagi VS Code. |
| `SQLSTATE[HY000] [2002] ... actively refused it` | MySQL belum menyala. Buka Laragon dan klik **Start All**. |
| `SQLSTATE[HY000] [1049] Unknown database 'posyanduku'` | Database belum dibuat. Jalankan perintah di Langkah 4, lalu `php artisan migrate --seed`. |
| `Base table or view not found` | Migration belum dijalankan. Jalankan `php artisan migrate`. |
| Error `Duplicate entry` saat seeding | Seeder dijalankan dua kali. Pakai `php artisan migrate:fresh --seed` (menghapus data lokal). |
| Laravel tidak jalan setelah ganti versi PHP | Cek `php -v`, harus 8.3 atau lebih tinggi, lalu `composer install` ulang. |
| Konflik saat `git pull` atau merge | Buka file yang konflik di VS Code, pilih **Accept Both Changes** bila dua-duanya diperlukan, simpan, lalu `git add .` dan `git commit`. Kalau konfliknya di `routes/web.php` atau `layouts/app.blade.php`, berarti ada yang mengubah file bersama: pakai versi dari `develop` dan pindahkan perubahanmu ke file fitur sendiri. |
| Git atau VS Code terasa lambat | Repo berada di dalam OneDrive. Pindahkan ke folder di luar OneDrive. |

Kalau ada masalah lain, tanyakan di grup tim dan sertakan pesan error lengkap atau tangkapan layar.