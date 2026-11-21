# Sprint 1 — Rifqi

**PBI:** PBI-7 Data Kader (FR-09), PBI-8 Jadwal Kegiatan & Absensi Kader (FR-10)
**Bergantung pada:** Autentikasi dan Unit Posyandu (Fadly)
**Urutan pengerjaan:** Data Kader → Jadwal & Absensi

---

## A. Data Kader (FR-09)

### Tabel `kader`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | PK | |
| id_user | FK → users | akun login peran Kader |
| id_unit | FK → unit_posyandu | wajib |
| nama | varchar | wajib |
| nik | char(16) | wajib, unik |
| jenis_kelamin | enum | L, P |
| tanggal_lahir | date | |
| no_hp | varchar | wajib |
| alamat | text | |
| status | enum | `aktif`, `nonaktif` |
| tanggal_bergabung | date | |

### Halaman
- Daftar kader (tabel, filter unit posyandu dan status)
- Form tambah/ubah kader
- Detail kader

### Fitur
- [ ] **F-09.1** Admin menambah, melihat, mengubah, menghapus data kader
  - Validasi: nama, NIK 16 digit unik, no HP, unit wajib
  - Konfirmasi sebelum hapus
- [ ] **F-09.2** Ubah status aktif/nonaktif (toggle atau dropdown)
- [ ] **F-09.3** Pilih unit posyandu dari dropdown (data dari modul Fadly)
- [ ] Saat menambah kader, akun `users` dengan role `kader` dibuat atau dihubungkan
- [ ] Akses: hanya Admin

### Kriteria Penerimaan
- Kader nonaktif tidak bisa login, atau tidak muncul di daftar absensi kegiatan baru
- Filter per unit dan status bekerja
- NIK ganda ditolak

---

## B. Jadwal Kegiatan & Absensi Kader (FR-10)

### Tabel `jadwal_kegiatan`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | PK | |
| id_unit | FK → unit_posyandu | lokasi |
| nama_kegiatan | varchar | wajib |
| jenis_kegiatan | enum | penimbangan, imunisasi, penyuluhan, lainnya |
| tanggal | date | wajib |
| waktu_mulai | time | wajib |
| waktu_selesai | time | lebih besar dari waktu mulai |
| keterangan | text | |

### Tabel `absensi_kader`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | PK | |
| id_jadwal | FK → jadwal_kegiatan | |
| id_kader | FK → kader | |
| status | enum | `hadir`, `izin`, `sakit`, `alpa` |
| keterangan | text | |
| dicatat_pada | timestamp | |
| | | **unik:** (id_jadwal, id_kader) |

### Halaman
- **Admin:** daftar jadwal (kalender atau tabel), form tambah/ubah jadwal, rekap kehadiran
- **Kader:** daftar jadwal kegiatan di unitnya, tombol catat kehadiran, riwayat kehadiran sendiri

### Fitur
- [ ] **F-10.1** Admin menambah, melihat, mengubah, menghapus jadwal kegiatan
  - Validasi: tanggal, waktu, unit, jenis wajib; waktu selesai > waktu mulai
- [ ] **F-10.2** Kader mencatat kehadiran (hadir/izin/sakit/alpa) per kegiatan
  - Satu kader hanya satu catatan per kegiatan (boleh diubah)
- [ ] **F-10.3** Kader melihat riwayat kehadirannya sendiri
- [ ] **F-10.4** Admin melihat rekap kehadiran
  - Per kegiatan: daftar kader dan statusnya
  - Per kader: jumlah hadir/izin/sakit/alpa dalam periode
- [ ] Akses: jadwal dikelola Admin; Kader hanya melihat jadwal dan mencatat kehadirannya sendiri

### Kriteria Penerimaan
- Kader tidak bisa mencatat kehadiran atas nama kader lain
- Catatan ganda untuk kegiatan yang sama ditolak (atau menjadi ubah data)
- Rekap Admin menampilkan jumlah yang sesuai data absensi

### Koordinasi
- Jadwal kegiatan akan dipakai Dashboard (Sprint 2) dan Laporan Bulanan (Sprint 2). Pastikan tanggal dan unit mudah di-query per bulan.
