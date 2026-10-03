# Frontend Flow Documentation — SIATAMA

> **Versi Dokumen:** `1.0.0`  
> **Terakhir Diperbarui:** 2026-09-21  
> **Stack Frontend:** PHP Template · Tailwind CSS (CDN) · Material Symbols Icons · Vanilla JavaScript  
> **Layout Engine:** PHP `require $contentView` di dalam layout file

---

## Daftar Isi

1. [Desain Sistem UI](#1-desain-sistem-ui)
2. [Portal Publik](#2-portal-publik)
3. [Autentikasi](#3-autentikasi)
4. [Portal Admin](#4-portal-admin)
   - [Beranda Admin](#41-beranda-admin)
   - [Manajemen Pengguna](#42-manajemen-pengguna)
   - [Master Jenjang](#43-master-jenjang)
   - [Master Kelas](#44-master-kelas)
   - [Profil Tentor](#45-profil-tentor)
   - [Data Siswa](#46-data-siswa)
   - [Pendaftaran Siswa](#47-pendaftaran-siswa)
   - [Jadwal Mengajar](#48-jadwal-mengajar)
   - [Pertemuan & Presensi (Admin)](#49-pertemuan--presensi-admin)
   - [Pengumuman](#410-pengumuman)
   - [Berita Publik](#411-berita-publik)
   - [Laporan & Rekap](#412-laporan--rekap)
5. [Portal Tentor](#5-portal-tentor)
   - [Beranda Tentor](#51-beranda-tentor)
   - [Jadwal Saya](#52-jadwal-saya)
   - [Dashboard Presensi Tentor](#53-dashboard-presensi-tentor)
   - [Sesi & Presensi (Tentor)](#55-sesi--presensi-tentor)
   - [Laporan Mengajar Tentor](#56-laporan-mengajar-tentor)
   - [Profil Tentor](#57-profil-tentor)
6. [Komponen UI Global](#6-komponen-ui-global)
7. [JavaScript Behaviors](#7-javascript-behaviors)
8. [Changelog](#8-changelog)

---

## 1. Desain Sistem UI

### Tech Stack
| Layer | Teknologi |
|-------|-----------|
| CSS Framework | **Tailwind CSS** via CDN (`plugins=forms,container-queries`) |
| Icons | **Google Material Symbols Outlined** via Google Fonts CDN |
| Font | **Inter** (body), **Montserrat** (heading) |
| JS | **Vanilla JavaScript** (tidak ada library/framework eksternal) |
| Theme Color | Primary `#324f47` (dark green), surface `#FAF9F7` (warm white) |

### Layout Pattern
Setiap halaman menggunakan **layout + content view** pattern:
- Layout di-include oleh PHP (`require $layoutFile`)
- Layout memanggil `require $contentView` di dalam `<main>`
- Flash message ditampilkan di atas konten jika ada `$_SESSION['flash_success']` atau `$_SESSION['flash_error']`

### Layout Files
| Layout | Digunakan oleh | Fitur |
|--------|---------------|-------|
| `admin/layout.php` | Semua halaman admin | Top header + Bottom Nav (6 item) |
| `tentor/layout.php` | Semua halaman tentor | Top header + Bottom Nav (3 item) |
| `auth/layout.php` | Login | Centered card layout |
| `public/layout.php` | Landing, Berita, Cek Presensi | Top nav publik |

---

## 2. Portal Publik

### Halaman: Landing Page (`/`)
**View:** `public/home.php`

#### Tombol & Link
| Elemen | Aksi | Tujuan |
|--------|------|--------|
| Tombol **"Cek Presensi Siswa"** (hero section) | Klik | → `/cek-presensi` |
| Link **"Lihat Semua"** (section berita) | Klik | → `/berita` |
| Card berita (tiap item) | Klik | → `/berita/{slug}` |

#### Konten Dinamis
- **Section Program:** Loop `$programList`, tampilkan nama + tipe program (tidak ada interaksi lanjut)
- **Section Tentor:** Loop `$tentorList`, tampilkan foto (jika ada) atau inisial huruf pertama nama
- **Section Berita:** 3 berita terbit terbaru (`status_terbit = 1`)

---

### Halaman: Daftar Berita (`/berita`)
**View:** `public/berita/index.php`

#### Tombol & Link
| Elemen | Aksi | Tujuan |
|--------|------|--------|
| Card berita | Klik | → `/berita/{slug}` |

---

### Halaman: Detail Berita (`/berita/{slug}`)
**View:** `public/berita/detail.php`

- Menampilkan konten berita lengkap (judul, gambar, isi, tanggal, penulis)
- Jika slug tidak ditemukan → `http_response_code(404)` + render `404.php`

---

### Halaman: Cek Presensi Siswa (`/cek-presensi`)
**View:** `public/cek_presensi.php`

#### Flow Pencarian (GET Form)
```
User isi form → klik "Cari Data Siswa"
    → GET /cek-presensi?q={keyword}&bulan={n}&tahun={yyyy}
    → Tampil hasil pencarian siswa (chip selector jika > 1 hasil)
    → Klik chip siswa → GET /cek-presensi?q=...&siswa_id={id}&bulan={n}&tahun={yyyy}
    → Tampil ringkasan kehadiran + riwayat presensi
```

#### Elemen Interaktif
| Elemen | Aksi | Hasil |
|--------|------|-------|
| Input cari (NIS/nama/sekolah) | Submit form | Reload halaman dengan query param |
| Dropdown **Bulan** | Pilih + Submit | Filter periode bulan |
| Input **Tahun** | Isi + Submit | Filter periode tahun |
| Chip nama siswa (multi-result) | Klik | Reload dengan `siswa_id` terpilih aktif (highlight chip) |
| Tombol **"Cari Data Siswa"** | Submit GET | Trigger pencarian |

#### Tampilan Hasil
- **Tidak ditemukan:** Banner pesan "tidak ditemukan"
- **1 hasil:** Langsung tampil kartu identitas siswa + ringkasan + riwayat
- **> 1 hasil:** Chip selector di atas, klik chip untuk memilih siswa

#### Ringkasan Kehadiran
- Progress bar warna berdasarkan persentase: ≥75% → hijau, ≥50% → amber, <50% → merah
- Grid 6 kolom: Total, Hadir, Sakit, Izin, Alfa, Belum Diisi

#### List Presensi Per Pertemuan
Setiap item menampilkan:
- Nomor pertemuan, nama kelas, jenjang
- Badge status kehadiran (warna sesuai status)
- Tanggal, jam, nama tentor
- Nilai sikap & akademik (jika ada) dengan label `(Sangat Baik/Baik/Cukup/Kurang)`
- Catatan tentor (jika ada) dalam box italic amber

---

## 3. Autentikasi

### Halaman: Login (`/login`)
**View:** `auth/login.php` · Layout: `auth/layout.php`

#### Flow Login
```
GET /login
  └─ Jika sudah login → redirect ke dashboard (skip form)
  └─ Belum login → tampil form

POST /login (submit form)
  ├─ Validasi kosong → re-render form dengan $errors per field
  ├─ User tidak ada / password salah → $errors['general'] (banner merah)
  ├─ User nonaktif → $errors['general'] (banner merah)
  └─ Login berhasil:
      ├─ Role admin/owner → redirect /admin/beranda
      └─ Role tentor → redirect /tentor/beranda
```

#### Elemen Form
| Field | Name | Tipe | Validasi Client |
|-------|------|------|----------------|
| Username | `username` | `text` | `required` |
| Password | `password` | `password` | `required` |

| Tombol | Aksi |
|--------|------|
| **"Masuk ke Portal"** (submit) | POST `/login` |

#### Error Display
- Error per-field (`username`, `password`): `<small>` merah di bawah field
- Error umum (`general`): Banner merah di atas form (username/password salah, akun nonaktif)

---

### Logout (`/logout`)
Klik icon **logout** (pojok kanan header) → GET `/logout` → session destroyed → redirect `/login` + flash success

---

## 4. Portal Admin

### Layout Admin
**File:** `admin/layout.php`

#### Header (Sticky Top)
- Avatar inisial nama (dari `$_SESSION['user_nama']`)
- Badge peran (`admin`, `owner`, dll)
- Tombol **logout** (icon) → `GET /logout`

#### Bottom Navigation Bar (Fixed)
| Tab | Icon | Route | `activeNav` |
|-----|------|-------|-------------|
| Beranda | `home` | `/admin/beranda` | `beranda` |
| Jadwal | `calendar_month` | `/admin/jadwal` | `jadwal` |
| Siswa | `school` | `/admin/siswa` | `siswa` |
| Tentor | `badge` | `/admin/tentor` | `tentor` |
| Pertemuan | `assessment` | `/admin/pertemuan` | `pertemuan` |
| Laporan | `summarize` | `/admin/laporan` | `laporan` |

Tab aktif ditandai dengan background `#e8f0ec` dan warna teks `#2d5a4c`.

---

### 4.1 Beranda Admin

**Route:** `GET /admin/beranda`  
**View:** `admin/beranda.php`

#### Seksi Halaman
```
[Statistik Realtime]     ← 4 kartu: Siswa Aktif | Tentor Aktif | Kelas Aktif | Sesi Bulan Ini
[Aksi Cepat]             ← 5 ikon shortcut
[Jadwal Hari Ini]        ← List jadwal + status realtime
[Pengumuman Internal]    ← List max 3 pengumuman aktif
```

#### Quick Actions (5 Ikon)
| Ikon | Label | Tujuan |
|------|-------|--------|
| `school` | Data Siswa | `/admin/siswa` |
| `calendar_add_on` | Jadwal Baru | `/admin/jadwal` |
| `campaign` | Pengumuman | `/admin/pengumuman` |
| `badge` | Data Tentor | `/admin/tentor` |
| `newspaper` | Berita | `/admin/berita` |

#### Jadwal Hari Ini
- "Lihat Semua" → `/admin/jadwal`
- Tiap kartu jadwal menampilkan: kelas, tentor, program, jam, ruangan, badge status realtime
- Status badge:
  - `belum_mulai` → amber
  - `sedang_berlangsung` → emerald
  - `selesai` → abu-abu

> **Fallback:** Jika DB down, ditampilkan banner amber "Menampilkan data contoh"

---

### 4.2 Manajemen Pengguna

**Base Route:** `/admin/pengguna`

#### Halaman Index
| Elemen | Aksi | Tujuan |
|--------|------|--------|
| Tombol **← (back)** | Klik | → `/admin/beranda` |
| Tombol **"+ Tambah Pengguna"** | Klik | → `/admin/pengguna/tambah` (GET) |
| Tombol **Edit** (per row) | Klik | → `/admin/pengguna/{id}/edit` (GET) |
| Tombol **Hapus** (per row) | Klik → `confirm()` | POST `/admin/pengguna/{id}/hapus` |

#### Form Tambah/Edit (`/admin/pengguna/tambah` atau `/{id}/edit`)
| Field | Name | Tipe | Validasi |
|-------|------|------|----------|
| Username | `username` | text | wajib, 3-50 kar. |
| Password | `password` | password | wajib (tambah), opsional (edit, min 6) |
| Peran | `peran` | select | owner / admin / tentor |
| Status Aktif | `status_aktif` | checkbox | — |

| Tombol | Aksi |
|--------|------|
| **"Simpan"** / **"Simpan Perubahan"** | POST → `/admin/pengguna/simpan` atau `/{id}/update` |
| **"Batal"** | Link → `/admin/pengguna` |

**Flow Sukses:** redirect `/admin/pengguna` + flash success  
**Flow Error:** re-render form dengan pesan error per field

---

### 4.3 Master Jenjang

**Base Route:** `/admin/jenjang`

#### Flow CRUD
```
Index → tombol "Tambah" → form → simpan → redirect index (flash success)
Index → tombol "Edit" → form (prefilled) → update → redirect index
Index → tombol "Hapus" → confirm() dialog → POST hapus → redirect index
```

Form hanya punya 1 field: **Nama Jenjang** (wajib, max 50 karakter)

> **Error Hapus:** Jika ada kelas yang terhubung (FK RESTRICT), muncul flash error tanpa penghapusan

---

### 4.4 Master Kelas

**Base Route:** `/admin/kelas`

#### Form Tambah/Edit
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Nama Kelas | `nama` | text | Wajib, max 20 karakter |
| Jenjang | `jenjang_id` | select | Dropdown dari `jenjangList` |
| Program | `program_id` | select | Dropdown dari `programList` |
| Status Aktif | `status_aktif` | checkbox | Default checked |

**Back button** → `/admin/kelas`  
**Error Hapus:** Jika ada jadwal/pendaftaran terikat → flash error

---

### 4.5 Profil Tentor

**Base Route:** `/admin/tentor`

#### Halaman Index
| Elemen | Aksi | Tujuan |
|--------|------|--------|
| Tombol **← (back)** | Klik | → `/admin/beranda` |
| Tombol **"+ Tambah Profil Tentor"** | Klik | → `/admin/tentor/tambah` |
| Tombol **Edit** | Klik | → `/admin/tentor/{id}/edit` |
| Tombol **Hapus** | Klik → confirm | POST `/admin/tentor/{id}/hapus` |

#### Form Tambah/Edit
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Akun Pengguna | `pengguna_id` | select | Hanya akun ber-peran `tentor` yang belum punya profil |
| Nama Lengkap | `nama_lengkap` | text | Wajib |
| Asal Universitas | `asal_universitas` | text | Wajib |
| Nomor Telepon | `nomor_telepon` | text | Opsional |
| Bio | `bio` | textarea | Opsional |
| Foto Profil | `foto` | file | JPEG/PNG/WEBP, max 2MB |
| Rate Gaji/Jam | `rate_gaji_per_jam` | number | Default 0 |
| Tarif/Sesi | `tarif_per_sesi` | number | Default 0, prioritas jika > 0 |
| Status Aktif | `status_aktif` | checkbox | — |

**Upload foto:** Disimpan ke `public/uploads/tentor/`

---

### 4.6 Data Siswa

**Base Route:** `/admin/siswa`

#### Halaman Index
| Elemen | Aksi | Tujuan/Efek |
|--------|------|-------------|
| Tombol **← (back)** | Klik | → `/admin/beranda` |
| Tombol **"+ Tambah Siswa Baru"** | Klik | → `/admin/siswa/tambah` |
| Input **Cari** | Ketik (live) | Filter kartu siswa (JavaScript client-side) |
| Badge kelas aktif | Display only | Nama kelas aktif (jika ada) |
| Dot status | Display only | Hijau = aktif, merah = nonaktif |
| Tombol **Edit** | Klik | → `/admin/siswa/{id}/edit` |
| Tombol **Hapus** | Klik → `confirm()` | POST `/admin/siswa/{id}/hapus` |

#### Live Search (JavaScript)
Event listener `input` pada `#search-siswa`:
- Ambil semua `.siswa-card`
- Bandingkan `card.textContent.toLowerCase()` dengan query
- Toggle `display: flex` / `display: none`

#### Form Tambah/Edit Siswa
**Bagian 1 — Identitas Siswa:**
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Nama Lengkap | `nama_lengkap` | text | Wajib |
| NIS | `nis` | text | Opsional, UNIQUE |
| Asal Sekolah | `asal_sekolah` | text | Wajib |
| Status Aktif | `status_aktif` | checkbox | Default checked |

**Bagian 2 — Data Orang Tua/Wali (Dynamic):**

| Elemen | Aksi | Efek |
|--------|------|------|
| Tombol **"+ Tambah Wali"** (`#btn-add-parent`) | Klik | JavaScript inject `.parent-row` baru ke `#parent-container` |
| Tombol **"Hapus"** (per baris wali) | Klik | JavaScript remove `.parent-row` + renumber label |

Setiap baris wali memiliki 3 field:
- `parents[{n}][nama_lengkap]` — text
- `parents[{n}][nomor_telepon]` — text  
- `parents[{n}][hubungan]` — select (Ayah / Ibu / Wali)

> **Aturan:** Baris wali pertama tidak bisa dihapus (no "Hapus" button), mulai baris ke-2 bisa dihapus

**Tombol Submit:**
| Tombol | Aksi |
|--------|------|
| **"Simpan Data Siswa"** / **"Simpan Perubahan"** | POST → `/admin/siswa/simpan` atau `/{id}/update` |
| **"Batal"** | Link → `/admin/siswa` |

---

### 4.7 Pendaftaran Siswa

**Base Route:** `/admin/pendaftaran`

#### Form Tambah/Edit
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Siswa | `siswa_id` | select | Dropdown semua siswa |
| Kelas | `kelas_id` | select | Dropdown semua kelas + relasi |
| Paket | `paket_id` | select | Opsional |
| Tanggal Mulai | `tanggal_mulai` | date | Wajib |
| Tanggal Selesai | `tanggal_selesai` | date | Opsional (muncul di edit) |
| Status | `status` | select | aktif / selesai |

**Logika saat store:** Jika status = `aktif` → semua pendaftaran aktif siswa tersebut di-selesaikan dulu (riwayat pindah kelas)

---

### 4.8 Jadwal Mengajar

**Base Route:** `/admin/jadwal`

#### Halaman Index
| Elemen | Aksi | Tujuan |
|--------|------|--------|
| Tombol **← (back)** | Klik | → `/admin/beranda` |
| Tombol **"+ Tambah Jadwal"** | Klik | → `/admin/jadwal/tambah` |
| Tombol **Edit** | Klik | → `/admin/jadwal/{id}/edit` |
| Tombol **Hapus** | Klik → confirm | POST `/admin/jadwal/{id}/hapus` |

#### Form Tambah/Edit
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Kelas | `kelas_id` | select | Tampil: `Nama - Jenjang (Program)` |
| Tentor | `tentor_id` | select | Tampil: `Nama (Univ: ...)` |
| Hari | `hari` | select | Senin(1) s.d. Minggu(7) |
| Jam Mulai | `jam_mulai` | time | Default 15:30 |
| Jam Selesai | `jam_selesai` | time | Default 17:00 |
| Ruangan | `ruangan` | text | Opsional |
| Status Aktif | `status_aktif` | checkbox | Default checked |

**Tombol Submit:**
- **"Tambah Jadwal"** / **"Simpan Perubahan"** → POST ke `/admin/jadwal/simpan` atau `/{id}/update`
- **"Batal"** → `/admin/jadwal`

**Error Hapus:** Jika ada histori pertemuan terkait → flash error (FK RESTRICT pada cascade-nya belum full delete)

---

### 4.9 Pertemuan & Presensi (Admin)

**Base Route:** `/admin/pertemuan`

#### Halaman Index (Daftar Pertemuan)
| Elemen | Aksi | Tujuan |
|--------|------|--------|
| Tombol **← (back)** | Klik | → `/admin/beranda` |
| Tombol **"+ Catat Sesi Pertemuan Baru"** | Klik | → `/admin/pertemuan/tambah` |
| Input **Cari** | Ketik (live) | Filter kartu pertemuan (JavaScript) |
| Badge **X/Y Hadir** | Display only | Jumlah hadir / total presensi |
| Tombol **"Kelola Presensi"** | Klik | → `/admin/pertemuan/{id}/presensi` |
| Tombol **Hapus** | Klik → confirm | POST `/admin/pertemuan/{id}/hapus` (cascade hapus presensi) |

#### Form Catat Pertemuan Baru (`/admin/pertemuan/tambah`)
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Jadwal Kelas | `jadwal_id` | select | Tampil: `Kelas X (Hari HH:MM-HH:MM) - Tentor: ...` |
| Tentor Aktual | `tentor_id` | select | Bisa berbeda dari tentor jadwal (pengganti) |
| Tanggal | `tanggal` | date | Default hari ini |
| Jam Mulai | `jam_mulai` | time | Default 15:30 |
| Jam Selesai | `jam_selesai` | time | Default 17:00 |

**Tombol Submit:**
- **"Simpan & Masuk ke Presensi Siswa"** → POST `/admin/pertemuan/simpan`  
  → Setelah simpan: **otomatis redirect ke halaman presensi** (`/admin/pertemuan/{newId}/presensi`)

**Tombol Batal** → `/admin/pertemuan`

#### Halaman Input Presensi (`/admin/pertemuan/{id}/presensi`)

**Auto-populate:** Saat halaman dibuka, `populateInitialPresensi()` dipanggil → semua siswa aktif di kelas tersebut yang belum ada record presensi → di-insert dengan status `none`

**Struktur per siswa (1 card):**
```
[Avatar inisial] Nama Siswa — Asal Sekolah
  [Kehadiran*]  [Nilai Sikap]  [Nilai Akademik]
  [Catatan Perkembangan Tentor]
```

| Field per Siswa | Name | Tipe | Pilihan |
|-----------------|------|------|---------|
| Kehadiran | `presensi[{siswa_id}][status_kehadiran]` | select | Belum Diisi / Hadir / Sakit / Izin / Alfa |
| Nilai Sikap | `presensi[{siswa_id}][nilai_sikap]` | select | - / A(SB) / B(Baik) / C(Cukup) / D(Kurang) |
| Nilai Akademik | `presensi[{siswa_id}][nilai_akademik]` | select | - / A / B / C / D |
| Catatan | `presensi[{siswa_id}][catatan]` | text | — |

**Tombol Submit:**
| Tombol | Aksi |
|--------|------|
| **"Simpan Presensi & Nilai"** | POST `/admin/pertemuan/{id}/presensi/update` → redirect `/admin/pertemuan` |
| **"Batal"** | Link → `/admin/pertemuan` |

> **Catatan:** Tombol submit hanya muncul jika `$presensiList` tidak kosong

---

### 4.10 Pengumuman

**Base Route:** `/admin/pengumuman`

#### Form Tambah/Edit
| Field | Name | Tipe | Pilihan/Keterangan |
|-------|------|------|-------------------|
| Judul | `judul` | text | Wajib |
| Isi | `isi` | textarea | Wajib |
| Target Peran | `target_peran` | select | Semua / Tentor / Admin |
| Tipe Broadcast | `tipe_broadcast` | select | Banner / Popup / Push |
| Kategori | `kategori` | text | Default: "Umum" |
| Status Aktif | `status_aktif` | checkbox | — |

> `diterbitkan_pada` otomatis diisi saat create (tidak ada input date)

---

### 4.11 Berita Publik

**Base Route:** `/admin/berita`

#### Form Tambah/Edit
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Judul | `judul` | text | Wajib, slug auto-generate |
| Isi | `isi` | textarea | Wajib |
| Gambar | `gambar` | file | JPEG/PNG/WEBP, max 3MB |
| Status Terbit | `status_terbit` | checkbox | Jika aktif → set `diterbitkan_pada` |

**Logika Slug:** Dibuat otomatis dari judul saat simpan. Saat edit, slug hanya di-regenerate jika judul berubah.  
**Upload gambar:** Disimpan ke `public/uploads/berita/`

---

### 4.12 Laporan & Rekap

**Route:** `/admin/laporan`

#### Halaman Pilihan Laporan
Berisi form **Filter Periode** (bulan + tahun) dan **2 kartu laporan:**

| Kartu | Klik | Tujuan |
|-------|------|--------|
| **Rekap Presensi & Nilai Siswa** | Klik | → `/admin/laporan/siswa-bulanan?bulan=&tahun=` |
| **Rekap Log Mengajar & Honorarium Tentor** | Klik | → `/admin/laporan/tentor-bulanan?bulan=&tahun=` |

> Parameter `bulan` & `tahun` dari form filter di-pass sebagai query string

---

#### Halaman Rekap Siswa Bulanan (`/admin/laporan/siswa-bulanan`)

**Flow:**
```
Pilih Kelas + Bulan + Tahun → klik "Tampilkan Rekap"
    → GET /admin/laporan/siswa-bulanan?kelas_id=&bulan=&tahun=
    → Tampil rekap per siswa + progress bar kehadiran
    → Klik "Export CSV" → download file rekap (stream)
```

| Elemen | Aksi | Hasil |
|--------|------|-------|
| Tombol **← (back)** | Klik | → `/admin/laporan` |
| Dropdown **Kelas** | Pilih | — |
| Dropdown **Bulan** | Pilih | — |
| Input **Tahun** | Isi | — |
| Tombol **"Tampilkan Rekap"** | Submit GET | Reload dengan filter |
| Tombol **"Export CSV"** | Klik | Download `rekap-siswa-{kelas}-{bulan}-{tahun}.csv` |

**Progress bar warna:**
- `≥75%` → `bg-emerald-500`
- `≥50%` → `bg-amber-400`
- `<50%` → `bg-red-400`

---

#### Halaman Rekap Tentor Bulanan (`/admin/laporan/tentor-bulanan`)

| Elemen | Aksi | Hasil |
|--------|------|-------|
| Dropdown **Bulan** + Input **Tahun** + Submit | Filter | Reload data payroll |
| Tombol **"Export CSV"** | Klik | Download `rekap-honorarium-tentor-{bulan}-{tahun}.csv` |

**Kalkulasi honorarium per tentor:**
- Jika `tarif_per_sesi > 0` → total = `tarif × jumlah_sesi`
- Jika tidak → total = `rate_per_jam × total_jam`

---

## 5. Portal Tentor

### Layout Tentor
**File:** `tentor/layout.php`

#### Header (Sticky Top)
- Avatar inisial nama (dari `$_SESSION['user_nama']`)
- Tombol **logout** → `/logout`

#### Bottom Navigation Bar (3 Tab)
| Tab | Icon | Route | `activeNav` |
|-----|------|-------|-------------|
| Beranda | `home` | `/tentor/beranda` | `beranda` |
| Jadwal Saya | `calendar_month` | `/tentor/jadwal` | `jadwal` |
| Sesi & Presensi | `fact_check` | `/tentor/pertemuan` | `pertemuan` |

---

### 5.1 Beranda Tentor

**Route:** `GET /tentor/beranda`  
**View:** `tentor/beranda.php`

#### Seksi Halaman
```
[Banner Pengumuman]   ← Pengumuman aktif pertama (target: tentor/semua)
[4 Kartu Statistik]  ← Jadwal Ditugaskan | Sesi Bulan Ini | Total Jam | % Kehadiran Siswa
[Jadwal Hari Ini]    ← Jadwal aktif tentor hari ini
```

#### Kartu Statistik
| Kartu | Sumber Data | Satuan |
|-------|------------|--------|
| Jadwal Ditugaskan | COUNT jadwal aktif milik tentor | Kelas |
| Sesi Bulan Ini | COUNT pertemuan bulan ini | Sesi |
| Total Jam | SUM jam mengajar bulan ini | Jam |
| Kehadiran Siswa | % hadir dari total presensi | % |

#### Jadwal Hari Ini
- "Lihat Semua" → `/tentor/jadwal`
- Tiap kartu jadwal menampilkan: nama kelas + jenjang, ruangan, jam
- Tombol **"Catat / Isi Presensi Sesi"** → `/tentor/presensi` (ke halaman pemilihan kelas presensi)

> Detail alur presensi baru dijelaskan pada bagian [Dashboard Presensi Tentor](#53-dashboard-presensi-tentor).

---

### 5.2 Jadwal Saya

**Route:** `GET /tentor/jadwal`  
**View:** `tentor/jadwal/index.php`

- Menampilkan jadwal aktif milik tentor yang login.
- Parameter `bulan=YYYY-MM` mengganti bulan kalender; parameter `tanggal=YYYY-MM-DD` memilih tanggal aktif.
- Date strip menampilkan satu minggu di sekitar tanggal aktif dan menandai hari yang memiliki jadwal.
- Bagian **Jadwal Hari Ini** menampilkan jadwal pada tanggal aktif, termasuk jam, kelas, mata pelajaran, jenjang, dan ruangan.
- Tombol **← (back)** → `/tentor/beranda`.
- Tombol **Lihat Detail Kelas** → `/tentor/jadwal/kelas/{kelas_id}`.

#### Detail Kelas dan Riwayat (`/tentor/jadwal/kelas/{kelas_id}`)

**View:** `tentor/jadwal/detail-kelas.php`

- Menampilkan identitas kelas dan daftar pertemuan milik tentor tersebut.
- Tombol **Tambah Presensi Baru** → `/tentor/presensi`.
- Tombol **Lihat Detail** pada pertemuan → `/tentor/jadwal/pertemuan/{pertemuan_id}`.

#### Detail Kehadiran (`/tentor/jadwal/pertemuan/{pertemuan_id}`)

**View:** `tentor/jadwal/detail-kehadiran.php`

- Menampilkan tanggal, waktu, kelas, mata pelajaran, status kehadiran, serta nilai kemampuan dan sikap tiap siswa.
- Tombol **Edit Data** → `/tentor/presensi` sesuai alur draft.
- Akses dibatasi dengan `pertemuan.tentor_id` agar data tentor lain tidak dapat dibuka.

---

### 5.3 Dashboard Presensi Tentor

**Route:** `GET /tentor/presensi`
**View:** `tentor/presensi/index.php`

Halaman ini merupakan pintu masuk alur presensi dari dashboard tentor.

#### Filter Kelas
| Filter | Pilihan | Perilaku |
|--------|---------|----------|
| Tipe Kelas | Semua, Reguler, Privat | Menyaring kartu berdasarkan `program.tipe` |
| Jenjang | Semua dan jenjang tersedia | Menyaring kartu berdasarkan nama jenjang |

Filter berjalan di client-side menggunakan Vanilla JavaScript tanpa request tambahan.

#### Kelas Aktif dan Riwayat
- Kelas aktif hanya berasal dari jadwal aktif tentor yang sedang login.
- Kartu menampilkan program, kelas, jenjang, jumlah siswa aktif, dan tombol **Isi Presensi**.
- Tombol kelas → `/tentor/presensi/isi?kelas_id={kelas_id}`.
- Riwayat menampilkan lima pertemuan terakhir milik tentor.
- Klik riwayat → `/tentor/presensi/isi?pertemuan_id={pertemuan_id}`.

### 5.4 Isi Kehadiran Siswa dan Panel Penilaian

**Route:** `GET /tentor/presensi/isi?kelas_id={id}` atau `?pertemuan_id={id}`
**View:** `tentor/presensi/form.php`

Setiap siswa memiliki tombol status berikut:

| Tombol | Nilai database | Arti |
|--------|----------------|------|
| `H` | `hadir` | Hadir |
| `S` | `sakit` | Sakit |
| `I` | `izin` | Izin |
| `A` | `alfa` | Alfa |
| `N` | `none` | Belum diisi |

- Klik `H` memilih status hadir sekaligus membuka panel penilaian siswa.
- Panel berisi nilai kemampuan/akademik dan nilai sikap dengan pilihan A/B/C/D.
- Tombol **Batal** menutup panel tanpa memperbarui nilai.
- Tombol **Simpan Penilaian** menyalin nilai ke hidden field siswa terkait.
- Tombol utama **Simpan Presensi & Nilai** → `POST /tentor/presensi/simpan`.
- Setelah berhasil, data disinkronkan melalui `Pertemuan::syncPresensi()` lalu redirect ke `/tentor/presensi`.

### 5.5 Sesi & Presensi (Tentor)

**Base Route:** `/tentor/pertemuan`

#### Halaman Daftar Pertemuan
| Elemen | Aksi | Tujuan |
|--------|------|--------|
| Tombol **← (back)** | Klik | → `/tentor/beranda` |
| Tombol **"+ Catat Sesi Pertemuan Baru"** | Klik | → `/tentor/pertemuan/tambah` |
| Input **Cari** | Ketik (live) | Filter kartu pertemuan (JavaScript) |
| Badge **X/Y Hadir** | Display only | — |
| Tombol **"Isi / Edit Presensi Siswa"** | Klik | → `/tentor/pertemuan/{id}/presensi` |

> Tentor hanya melihat pertemuan yang **miliknya** (`tentor_id = $tentorId`)

#### Form Catat Pertemuan Baru (`/tentor/pertemuan/tambah`)
| Field | Name | Tipe | Keterangan |
|-------|------|------|------------|
| Jadwal Kelas | `jadwal_id` | select | Semua jadwal aktif |
| Tanggal | `tanggal` | date | Default hari ini |
| Jam Mulai | `jam_mulai` | time | — |
| Jam Selesai | `jam_selesai` | time | — |

> `tentor_id` otomatis dari `$this->tentorId` (session) — tidak ada input tentor

**Flow Submit:**
```
POST /tentor/pertemuan/simpan
  → Validasi → create pertemuan dengan tentor_id = session tentor
  → populateInitialPresensi() → redirect /tentor/pertemuan/{id}/presensi
```

#### Halaman Input Presensi Tentor (`/tentor/pertemuan/{id}/presensi`)
- Sama persis dengan presensi admin, tapi dengan **guard keamanan**: cek `pertemuan.tentor_id === $this->tentorId`
- Jika pertemuan bukan miliknya → flash error + redirect ke daftar
- Form submit → POST `/tentor/pertemuan/{id}/presensi/update` → redirect `/tentor/pertemuan`

> Route daftar pertemuan dan route lama edit presensi tetap tersedia sebagai kompatibilitas. Alur baru dari dashboard menggunakan `/tentor/presensi` dan `/tentor/presensi/isi`.

### 5.6 Laporan Mengajar Tentor

**Route:** `GET /tentor/laporan`
**View:** `tentor/laporan.php`

- Filter bulan menggunakan parameter `bulan=YYYY-MM`.
- Ringkasan menampilkan total hadir siswa, total izin/sakit, total jam mengajar, dan persentase kehadiran.
- Riwayat sesi menampilkan nomor pertemuan, kelas, mata pelajaran/program, tanggal, dan jam mengajar.
- Data hanya berasal dari pertemuan milik tentor yang sedang login.

### 5.7 Profil Tentor

**Route:** `GET /tentor/profil`
**View:** `tentor/profil.php`

#### Update Profil

| Field | Name | Keterangan |
|---|---|---|
| Nama Lengkap | `nama_lengkap` | Wajib |
| Asal Universitas | `asal_universitas` | Wajib |
| Username Akun | `username` | Wajib dan unik |
| Password Baru | `password_baru` | Opsional, minimal 6 karakter |
| Konfirmasi Password | `konfirmasi_password` | Harus sama jika password diisi |

Submit form → `POST /tentor/profil/update` → validasi → update tabel `tentor`/`pengguna` → redirect `/tentor/profil` dengan flash message.

---

## 6. Komponen UI Global

### Flash Message
Ditampilkan di atas `$contentView` di dalam `<main>`:

| Tipe | Kondisi | Warna |
|------|---------|-------|
| Success | `$_SESSION['flash_success']` ada | Emerald (hijau) dengan icon `check_circle` |
| Error | `$_SESSION['flash_error']` ada | Red (merah) dengan icon `error` |

> Flash message di-`unset` setelah ditampilkan (single-display pattern)

---

### Konfirmasi Hapus (Native Browser)
Semua tombol hapus menggunakan `onsubmit="return confirm('...');"` pada form POST:
```html
<form action="/admin/{resource}/{id}/hapus" method="POST"
      onsubmit="return confirm('Yakin ingin menghapus...?');">
  <button type="submit">Hapus</button>
</form>
```
- Jika user klik **OK** → form di-submit → POST ke server
- Jika user klik **Cancel** → form tidak di-submit

---

### Back Button
Setiap halaman form/detail memiliki tombol panah kiri (`arrow_back`) yang me-link ke halaman index resource tersebut:
```
← [icon] → href="/admin/{resource}"
```
Tombol ini adalah `<a>` tag biasa (bukan `history.back()`), sehingga navigasi konsisten.

---

### Status Dot / Badge
| Kondisi | Visual |
|---------|--------|
| Aktif | Dot hijau `bg-emerald-500` |
| Nonaktif | Dot merah `bg-red-400` |
| Kelas aktif | Badge `#e8f0ec` (green chip) |
| Belum ada kelas | Badge abu-abu |

---

### Kartu List (Pattern Umum)
Semua halaman index mengikuti pola:
```
[Header + Count Badge]
[Tombol "+ Tambah"]
[Search Bar (live filter)]
[List Card - foreach data]
  [Info kiri]  [Tombol Edit | Hapus]
```

---

## 7. JavaScript Behaviors

### Live Search (Client-Side)
Diimplementasi di halaman-halaman index yang memiliki banyak data:

| Halaman | Input ID | Card Selector |
|---------|----------|---------------|
| Siswa | `#search-siswa` | `.siswa-card` |
| Pertemuan (Admin) | `#search-pertemuan` | `.pertemuan-card` |
| Pertemuan (Tentor) | `#search-pertemuan-tentor` | `.pertemuan-item` |

**Implementasi:**
```javascript
document.getElementById('search-X')?.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase().trim();
    const cards = document.querySelectorAll('#list-id .card-class');
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(query) ? 'flex' : 'none';
    });
});
```

> Pencarian dilakukan terhadap **seluruh text content** kartu, bukan field tertentu — mudah tapi tidak strict.

---

### Dynamic Parent Rows (Form Siswa)
Di `admin/siswa/form.php`:

#### Tambah Baris Wali
```javascript
// Tombol #btn-add-parent
btnAdd.addEventListener('click', function() {
    const rows = container.querySelectorAll('.parent-row');
    const nextIndex = rows.length;
    // Inject HTML baru dengan index incremental
    container.appendChild(newRow); // template literal
});
```

#### Hapus Baris Wali (Event Delegation)
```javascript
container.addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-parent')) {
        const row = e.target.closest('.parent-row');
        row.remove();
        // Renumber label "Wali #n"
        container.querySelectorAll('.parent-row').forEach((r, idx) => {
            r.querySelector('.parent-number').textContent = idx + 1;
        });
    }
});
```

> Name attribute input digenerate dengan indeks: `parents[0][nama_lengkap]`, `parents[1][nama_lengkap]`, dst.

---

### Status Realtime Jadwal (PHP + Helper)
Di `admin/beranda.php`, status realtime jadwal dihitung di PHP menggunakan helper `status_sesi_mengajar()`:
```php
$status = $schedule['status_realtime'] 
    ?? status_sesi_mengajar($schedule['jam_mulai'], $schedule['jam_selesai']);
```
Tidak ada polling/AJAX — status dikalkulasi saat page load.

---

## 8. Changelog

### Alur Menu Publik (2026-10-03)

- Berita: `/berita` menyediakan filter tombol Semua, Bakti Sosial, dan Rekap Bulanan.
- Absensi Siswa: `/cek-presensi` menyediakan filter tipe belajar dan jenjang, pencarian siswa, serta filter bulan/tahun.
- Profil Tentor: `/profil-tentor` menampilkan daftar tentor aktif dengan pencarian nama atau universitas.
- Login: `/login` menggunakan navigasi publik yang sama dan tetap meneruskan proses autentikasi yang sudah ada.
- Beranda: `/` menampilkan informasi Siatama, program, tentor, dan berita terbaru.
- Beranda mengikuti draft dengan hero logo, review pelajar, lokasi, dan kontak.
- Kartu tentor pada `/profil-tentor` memiliki tautan ke `/profil-tentor/{id}` untuk detail profil.

| Versi | Tanggal | Perubahan |
|-------|---------|-----------|
| `1.0.0` | 2026-09-21 | Dokumentasi awal — semua alur UI portal publik, admin, dan tentor |
| `1.1.0` | 2026-10-03 | Menambahkan dokumentasi alur dashboard presensi tentor, filter kelas, status H/S/I/A/N, dan panel penilaian siswa |
| `1.2.0` | 2026-10-03 | Menambahkan alur Jadwal Saya dengan filter bulan/tanggal, detail kelas, riwayat pertemuan, dan detail kehadiran |
| `1.3.0` | 2026-10-03 | Menambahkan alur menu publik Berita, Absensi Siswa, Profil Tentor, dan Login sesuai UI draft |
| `1.4.0` | 2026-10-03 | Menyelaraskan beranda/berita dengan draft, memasang logo resmi, dan menambahkan detail profil tentor |
