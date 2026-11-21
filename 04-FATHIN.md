# Sprint 1 — Fathin

**PBI:** PBI-10 Master Vaksin (FR-12), PBI-11 Pencatatan Imunisasi, Pemberian Vitamin & Jadwal Imunisasi (FR-13)
**Bergantung pada:** Autentikasi (Fadly), Data Balita & Orang Tua (Fadly)
**Urutan pengerjaan:** Master Vaksin → Imunisasi & Vitamin

---

## A. Master Vaksin (FR-12)

### Tabel `vaksin`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | PK | |
| nama_vaksin | varchar | wajib, unik |
| usia_pemberian_bulan | int | usia anjuran dalam bulan, ≥ 0 |
| dosis_ke | int | ≥ 1 |
| jumlah_dosis | varchar | mis. 0,5 ml |
| keterangan | text | |

> Bila satu vaksin punya beberapa dosis dengan usia berbeda, simpan satu baris per dosis (mis. DPT-HB-Hib 1, 2, 3).

### Halaman
- Daftar vaksin (urut berdasarkan usia pemberian)
- Form tambah/ubah vaksin

### Fitur
- [ ] **F-12.1** Kader menambah, melihat, mengubah, menghapus jenis vaksin
  - Validasi: nama, usia pemberian, dosis wajib
  - Vaksin yang sudah dipakai di catatan imunisasi tidak boleh dihapus
- [ ] **F-12.2** Data vaksin menjadi dropdown di form imunisasi dan acuan jadwal
- [ ] Seeder: jenis vaksin imunisasi dasar beserta usia pemberiannya
- [ ] Akses: Kader

### Kriteria Penerimaan
- Daftar vaksin muncul terurut sesuai usia pemberian
- Vaksin yang sudah dipakai tidak bisa dihapus

---

## B. Imunisasi, Vitamin & Jadwal Imunisasi (FR-13)

### Tabel `imunisasi`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | PK | |
| id_balita | FK → balita | wajib |
| id_vaksin | FK → vaksin | wajib |
| tanggal_pemberian | date | tidak boleh sebelum tanggal lahir atau di masa depan |
| dosis_ke | int | |
| keterangan | text | |
| id_user | FK → users | kader pencatat |

### Tabel `pemberian_vitamin`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | PK | |
| id_balita | FK → balita | wajib |
| nama_vitamin | varchar | mis. Vitamin A biru / merah |
| tanggal_pemberian | date | wajib |
| dosis | varchar | |
| keterangan | text | |
| id_user | FK → users | |

### Tabel `jadwal_imunisasi`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | PK | |
| id_balita | FK → balita | |
| id_vaksin | FK → vaksin | |
| tanggal_jadwal | date | |
| status | enum | `terjadwal`, `selesai`, `terlewat` |

### Halaman
- **Kader:**
  - Pilih balita, lalu tampil riwayat imunisasi dan vitamin
  - Form catat imunisasi
  - Form catat vitamin
  - Jadwal imunisasi per balita
- **Orang Tua:**
  - Halaman "Imunisasi Anak": jadwal berikutnya, riwayat imunisasi, riwayat vitamin

### Fitur
- [ ] **F-13.1** Kader mencatat pemberian imunisasi
- [ ] **F-13.2** Kader mencatat pemberian vitamin
- [ ] **F-13.3** Lihat, ubah, hapus riwayat imunisasi dan vitamin (dengan konfirmasi)
- [ ] **F-13.4** Kelola jadwal imunisasi per balita
  - Opsi: buat jadwal otomatis dari `tanggal_lahir + usia_pemberian_bulan` di master vaksin
  - Saat imunisasi dicatat, status jadwal terkait menjadi `selesai`
  - Jadwal lewat tanggal dan belum dicatat menjadi `terlewat`
- [ ] **F-13.5** Orang Tua melihat jadwal imunisasi anaknya
- [ ] **F-13.6** Orang Tua melihat riwayat imunisasi dan vitamin anaknya
- [ ] Akses: Kader kelola; Orang Tua hanya lihat balita yang terhubung ke akunnya

### Kriteria Penerimaan
- Tanggal pemberian sebelum tanggal lahir ditolak
- Status jadwal berubah otomatis setelah imunisasi dicatat
- Orang Tua A tidak bisa melihat data anak Orang Tua B (termasuk lewat URL)

### Koordinasi
- Sepakati dengan **Rafa** nama item vitamin yang sama dengan modul stok, untuk integrasi pengurangan stok di Sprint 2.
- Catatan imunisasi akan dirujuk oleh modul KIPI (Sprint 2). Pastikan setiap catatan punya ID yang bisa dirujuk.
