# Database Specification — Sistem Informasi Bimbingan Belajar

> **Versi:** 1.0  
> **Status:** Draft Final untuk tahap perancangan database  
> **Database:** MySQL  
> **Backend:** PHP Native  
> **Bahasa penamaan:** Bahasa Indonesia  
> **Tanggal:** 2026

---

## 1. Gambaran Umum

Database ini digunakan untuk mendukung sistem informasi bimbingan belajar yang memiliki tiga kelompok pengguna utama:

1. **Owner**
2. **Admin**
3. **Tentor**

Selain pengguna yang dapat login, sistem juga menyimpan data:

- Siswa
- Orang tua
- Jenjang pendidikan
- Program pembelajaran
- Paket pembelajaran
- Kelas
- Pendaftaran siswa
- Jadwal mengajar
- Pertemuan
- Presensi
- Pengumuman
- Berita

Siswa dan orang tua **tidak memiliki akun/login**. Data mereka digunakan sebagai informasi yang ditampilkan melalui sisi publik/landing page.

---

# 2. Prinsip Perancangan

## 2.1 Pengguna Login

Hanya:

- Owner
- Admin
- Tentor

yang memiliki akun.

Ketiganya disimpan dalam tabel `pengguna`.

Perbedaan akses ditentukan berdasarkan field:

```text
peran
```

dengan nilai:

```text
owner
admin
tentor
```

---

## 2.2 Profil Tentor Dipisahkan dari Akun

Data login disimpan pada:

```text
pengguna
```

sedangkan data profil tentor disimpan pada:

```text
tentor
```

Relasi:

```text
pengguna 1 : 1 tentor
```

Hal ini dilakukan karena informasi seperti nama lengkap, asal universitas, foto, dan bio merupakan data profil, bukan data autentikasi.

---

## 2.3 Siswa dan Orang Tua Tidak Memiliki Akun

Siswa dan orang tua hanya menjadi data di dalam sistem.

Mereka tidak disimpan dalam tabel `pengguna`.

Sisi publik dapat menampilkan informasi siswa berdasarkan data yang tersedia tanpa proses login.

---

## 2.4 Laporan Tidak Disimpan sebagai Tabel Khusus

Sistem tidak membuat tabel seperti:

```text
laporan_siswa
laporan_tentor
rekap_presensi
rekap_mengajar
statistik_dashboard
```

karena laporan dapat dihasilkan dari data transaksi yang sudah ada menggunakan query SQL.

Contoh:

```text
Jumlah sesi bulan ini
→ dihitung dari tabel pertemuan

Jumlah hadir siswa
→ dihitung dari tabel presensi

Riwayat mengajar tentor
→ berdasarkan tabel pertemuan

Persentase capaian mengajar
→ dihitung dari jadwal dan pertemuan
```

---

## 2.5 Jadwal Ketersediaan Tentor Tidak Disimpan

Tentor sebenarnya memiliki proses pengisian ketersediaan jadwal untuk bulan berikutnya.

Contoh:

> Tentor mengirim ketersediaan Senin–Sabtu untuk bulan September.

Namun proses tersebut **tidak dimasukkan ke sistem**.

Ketersediaan tentor tetap dikirim melalui WhatsApp dan admin melakukan penjadwalan secara manual.

Database hanya menyimpan **jadwal mengajar yang sudah ditetapkan oleh admin/owner**.

---

# 3. Daftar Tabel

Database terdiri dari 15 tabel utama:

| No | Tabel | Fungsi |
|---:|---|---|
| 1 | `pengguna` | Akun owner, admin, dan tentor |
| 2 | `tentor` | Profil tentor |
| 3 | `orang_tua` | Data orang tua siswa |
| 4 | `siswa` | Data siswa |
| 5 | `siswa_orang_tua` | Relasi siswa dengan orang tua |
| 6 | `jenjang` | Jenjang pendidikan |
| 7 | `program` | Program reguler/private |
| 8 | `paket` | Paket pembelajaran |
| 9 | `kelas` | Data kelas |
| 10 | `pendaftaran_siswa` | Penempatan siswa ke kelas/program/paket |
| 11 | `jadwal` | Jadwal mengajar |
| 12 | `pertemuan` | Pertemuan mengajar yang benar-benar berlangsung |
| 13 | `presensi` | Kehadiran dan nilai siswa |
| 14 | `pengumuman` | Broadcast internal |
| 15 | `berita` | Berita untuk halaman publik |

---

# 4. Detail Tabel

## 4.1 Tabel `pengguna`

Menyimpan akun yang dapat login ke sistem.

Pengguna terdiri dari:

- Owner
- Admin
- Tentor

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID pengguna |
| `username` | VARCHAR(50) | NO | UNIQUE | Username login |
| `password` | VARCHAR(255) | NO | - | Password yang telah di-hash |
| `peran` | ENUM | NO | - | `owner`, `admin`, `tentor` |
| `status_aktif` | BOOLEAN | NO | - | Status akun |
| `terakhir_login` | DATETIME | YES | - | Waktu login terakhir |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Catatan

Password tidak boleh disimpan dalam bentuk plaintext.

Saat pengguna mengganti password:

1. Password lama diverifikasi.
2. Password baru di-hash.
3. Hash lama diganti dengan hash baru.

Sistem tidak menyimpan password lama.

`terakhir_login` digunakan terutama untuk monitoring aktivitas login tentor oleh admin.

---

# 5. Tabel `tentor`

Menyimpan informasi profil tentor.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID tentor |
| `pengguna_id` | BIGINT | NO | FK | Referensi akun pengguna |
| `nama_lengkap` | VARCHAR(100) | NO | - | Nama lengkap |
| `asal_universitas` | VARCHAR(150) | NO | - | Universitas |
| `nomor_telepon` | VARCHAR(20) | YES | - | Nomor telepon |
| `bio` | TEXT | YES | - | Informasi publik tentor |
| `foto` | VARCHAR(255) | YES | - | Path/nama file foto |
| `status_aktif` | BOOLEAN | NO | - | Tentor aktif/nonaktif |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Relasi

```text
tentor.pengguna_id
        ↓
pengguna.id
```

Satu akun tentor memiliki satu profil tentor.

---

# 6. Tabel `orang_tua`

Menyimpan data orang tua/wali siswa.

Orang tua tidak memiliki akun.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID orang tua |
| `nama_lengkap` | VARCHAR(100) | NO | - | Nama orang tua |
| `nomor_telepon` | VARCHAR(20) | NO | - | Nomor wali |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

---

# 7. Tabel `siswa`

Menyimpan identitas siswa.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID siswa |
| `nama_lengkap` | VARCHAR(100) | NO | - | Nama siswa |
| `asal_sekolah` | VARCHAR(150) | NO | - | Asal sekolah |
| `status_aktif` | BOOLEAN | NO | - | Status siswa |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

---

# 8. Tabel `siswa_orang_tua`

Tabel penghubung antara siswa dan orang tua.

Digunakan karena:

- Satu siswa dapat memiliki lebih dari satu orang tua/wali.
- Satu orang tua dapat memiliki lebih dari satu anak.

Relasi:

```text
siswa M : N orang_tua
```

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID relasi |
| `siswa_id` | BIGINT | NO | FK | ID siswa |
| `orang_tua_id` | BIGINT | NO | FK | ID orang tua |
| `hubungan` | VARCHAR(30) | NO | - | Ayah, Ibu, Wali, dll. |

### Contoh

```text
Andi
├── Ayah Andi
└── Ibu Andi
```

---

# 9. Tabel `jenjang`

Menyimpan jenjang pendidikan.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID jenjang |
| `nama` | VARCHAR(50) | NO | - | Nama jenjang |

### Contoh data

```text
1 | SD
2 | SMP/MTs
3 | SMA/MA
```

Tidak menggunakan field kode.

Tidak menggunakan tahun ajaran.

---

# 10. Tabel `program`

Menyimpan jenis program pembelajaran.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID program |
| `nama` | VARCHAR(50) | NO | - | Nama program |
| `tipe` | VARCHAR(30) | NO | - | Jenis program |

### Contoh

```text
1 | Reguler | reguler
2 | Private | private
```

---

# 11. Tabel `paket`

Menyimpan paket pembelajaran.

Paket hanya digunakan sebagai penanda/formalitas.

Paket tidak memiliki relasi langsung ke `program`.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID paket |
| `nama` | VARCHAR(50) | NO | - | Nama paket |

### Contoh

```text
1 | Paket A
2 | Paket B
3 | Paket C
```

---

# 12. Tabel `kelas`

Menyimpan data kelas yang tersedia.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID kelas |
| `jenjang_id` | BIGINT | NO | FK | Jenjang pendidikan |
| `program_id` | BIGINT | NO | FK | Program kelas |
| `nama` | VARCHAR(20) | NO | - | Nama kelas |
| `status_aktif` | BOOLEAN | NO | - | Status kelas |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Contoh

```text
1 | SMP/MTs | Reguler | 7A
2 | SMP/MTs | Reguler | 7B
3 | SMP/MTs | Reguler | 8A
4 | SMP/MTs | Reguler | 9B
```

### Relasi

```text
kelas
├── jenjang
└── program
```

---

# 13. Tabel `pendaftaran_siswa`

Menentukan penempatan siswa pada kelas dan paket.

Tabel ini juga menyimpan histori perpindahan siswa.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID pendaftaran |
| `siswa_id` | BIGINT | NO | FK | Siswa |
| `kelas_id` | BIGINT | NO | FK | Kelas |
| `paket_id` | BIGINT | YES | FK | Paket |
| `tanggal_mulai` | DATE | NO | - | Mulai mengikuti |
| `tanggal_selesai` | DATE | YES | - | Selesai mengikuti |
| `status` | ENUM | NO | - | `aktif`, `selesai` |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Catatan

Program tidak disimpan kembali di tabel ini karena sudah dapat diketahui melalui:

```text
pendaftaran_siswa
        ↓
kelas
        ↓
program
```

Dengan demikian tidak terjadi penyimpanan data program secara berulang.

### Contoh

```text
Andi
↓
Kelas 8A
↓
Reguler
↓
Paket B
↓
Aktif
```

Jika Andi pindah kelas:

```text
Pendaftaran #1
8A
Selesai

Pendaftaran #2
8B
Aktif
```

Histori tetap tersimpan.

---

# 14. Tabel `jadwal`

Menyimpan jadwal mengajar yang telah ditentukan admin/owner.

Jadwal ketersediaan tentor bulanan tidak disimpan.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID jadwal |
| `kelas_id` | BIGINT | NO | FK | Kelas yang diajar |
| `tentor_id` | BIGINT | NO | FK | Tentor yang ditugaskan |
| `hari` | TINYINT | NO | - | Hari dalam minggu |
| `jam_mulai` | TIME | NO | - | Jam mulai |
| `jam_selesai` | TIME | NO | - | Jam selesai |
| `ruangan` | VARCHAR(50) | YES | - | Ruangan |
| `status_aktif` | BOOLEAN | NO | - | Status jadwal |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Nilai `hari`

Disarankan:

```text
1 = Senin
2 = Selasa
3 = Rabu
4 = Kamis
5 = Jumat
6 = Sabtu
7 = Minggu
```

Karena bimbel beroperasi Senin–Sabtu, Minggu dapat tetap disediakan untuk fleksibilitas.

### Contoh

```text
Kelas      : 9B
Tentor     : Budi
Hari       : Senin
Jam        : 19:00 - 20:30
Ruangan    : Ruang A
```

---

# 15. Tabel `pertemuan`

Menyimpan kejadian mengajar yang benar-benar berlangsung berdasarkan suatu jadwal.

Perbedaan:

### Jadwal

> Setiap Senin kelas 9B belajar pukul 19:00–20:30.

### Pertemuan

> Pada Senin, 14 September 2026, kelas 9B benar-benar melaksanakan pertemuan ke-20.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID pertemuan |
| `jadwal_id` | BIGINT | NO | FK | Jadwal asal |
| `tentor_id` | BIGINT | NO | FK | Tentor yang benar-benar mengajar |
| `nomor_pertemuan` | INT | NO | - | Nomor pertemuan |
| `tanggal` | DATE | NO | - | Tanggal pelaksanaan |
| `jam_mulai` | TIME | NO | - | Waktu mulai aktual |
| `jam_selesai` | TIME | NO | - | Waktu selesai aktual |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Mengapa `tentor_id` ada di sini?

Karena tentor pada jadwal belum tentu tentor yang benar-benar mengajar.

Contoh:

```text
Jadwal
Kelas 9B
Senin
19:00–20:30
Tentor Budi
```

Pada hari H:

```text
Budi izin.
Andi menggantikan.
```

Maka:

```text
jadwal.tentor_id = Budi
pertemuan.tentor_id = Andi
```

Dengan demikian histori pengajar tetap akurat.

### `nomor_pertemuan`

Digunakan untuk menampilkan:

```text
Pertemuan 20
Pertemuan 19
Pertemuan 18
```

di halaman riwayat kelas/tentor.

---

# 16. Tabel `presensi`

Menyimpan kehadiran setiap siswa pada sebuah pertemuan.

Satu pertemuan memiliki banyak data presensi.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID presensi |
| `pertemuan_id` | BIGINT | NO | FK | Pertemuan |
| `siswa_id` | BIGINT | NO | FK | Siswa |
| `status_kehadiran` | ENUM | NO | - | Status kehadiran |
| `nilai_sikap` | ENUM | YES | - | Nilai A–D |
| `nilai_akademik` | ENUM | YES | - | Nilai A–D |
| `catatan` | TEXT | YES | - | Catatan tentor |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Status kehadiran

```text
hadir
sakit
izin
alfa
none
```

### Nilai

```text
A
B
C
D
```

Nilai sikap dan akademik dapat bernilai `NULL` jika belum diisi.

### Contoh

```text
Pertemuan 20
├── Andi  → Hadir → Sikap A → Akademik B
├── Budi  → Izin  → -
├── Citra → Sakit → Sikap B → Akademik A
└── Deni  → Alfa  → -
```

---

# 17. Tabel `pengumuman`

Menyimpan broadcast dari admin/owner.

Contoh:

> Pengumuman penting: batas pengisian nilai bulan ini tanggal 30.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID pengumuman |
| `judul` | VARCHAR(150) | NO | - | Judul |
| `isi` | TEXT | NO | - | Isi pengumuman |
| `dibuat_oleh` | BIGINT | NO | FK | Pengguna pembuat |
| `target_peran` | ENUM | NO | - | Target penerima |
| `diterbitkan_pada` | DATETIME | NO | - | Waktu publikasi |
| `status_aktif` | BOOLEAN | NO | - | Status |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

### Target

Contoh nilai:

```text
semua
tentor
admin
```

Jika kebutuhan broadcast berkembang, daftar target dapat diperluas.

---

# 18. Tabel `berita`

Digunakan untuk konten publik pada landing page.

### Struktur

| Field | Tipe | Null | Key | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT | NO | PK | ID berita |
| `judul` | VARCHAR(150) | NO | - | Judul berita |
| `slug` | VARCHAR(180) | NO | UNIQUE | URL-friendly identifier |
| `isi` | TEXT | NO | - | Isi berita |
| `gambar` | VARCHAR(255) | YES | - | Gambar berita |
| `dibuat_oleh` | BIGINT | NO | FK | Pembuat berita |
| `diterbitkan_pada` | DATETIME | YES | - | Waktu publikasi |
| `status_terbit` | BOOLEAN | NO | - | Status publikasi |
| `dibuat_pada` | DATETIME | NO | - | Waktu pembuatan |
| `diubah_pada` | DATETIME | NO | - | Waktu perubahan |

---

# 19. Relasi Antar Tabel

## 19.1 Pengguna dan Tentor

```text
pengguna 1 ───── 1 tentor
```

```text
pengguna.id
     │
     └── tentor.pengguna_id
```

Hanya pengguna dengan `peran = tentor` yang memiliki data pada tabel `tentor`.

---

## 19.2 Siswa dan Orang Tua

```text
siswa M ───── N orang_tua
        │
        │
        └── siswa_orang_tua
```

---

## 19.3 Jenjang dan Kelas

```text
jenjang 1 ───── N kelas
```

Satu jenjang dapat memiliki banyak kelas.

Contoh:

```text
SMP/MTs
├── 7A
├── 7B
├── 8A
├── 8B
└── 9B
```

---

## 19.4 Program dan Kelas

```text
program 1 ───── N kelas
```

Contoh:

```text
Reguler
├── 7A
├── 7B
└── 9B
```

---

## 19.5 Siswa dan Pendaftaran

```text
siswa 1 ───── N pendaftaran_siswa
```

Digunakan untuk menyimpan histori penempatan siswa.

---

## 19.6 Kelas dan Pendaftaran

```text
kelas 1 ───── N pendaftaran_siswa
```

Satu kelas dapat memiliki banyak siswa.

---

## 19.7 Paket dan Pendaftaran

```text
paket 1 ───── N pendaftaran_siswa
```

Paket tidak berhubungan langsung dengan program.

---

## 19.8 Kelas dan Jadwal

```text
kelas 1 ───── N jadwal
```

Contoh:

```text
Kelas 9B
├── Senin 19:00–20:30
└── Rabu 19:00–20:30
```

---

## 19.9 Tentor dan Jadwal

```text
tentor 1 ───── N jadwal
```

Satu tentor dapat memiliki banyak jadwal mengajar.

---

## 19.10 Jadwal dan Pertemuan

```text
jadwal 1 ───── N pertemuan
```

Contoh:

```text
Jadwal Senin 9B
├── Pertemuan 18
├── Pertemuan 19
└── Pertemuan 20
```

---

## 19.11 Pertemuan dan Presensi

```text
pertemuan 1 ───── N presensi
```

Satu pertemuan memiliki presensi banyak siswa.

---

## 19.12 Siswa dan Presensi

```text
siswa 1 ───── N presensi
```

Satu siswa memiliki banyak histori presensi.

---

# 20. Gambaran ERD

```text
┌──────────────┐
│   pengguna   │
└──────┬───────┘
       │
       │ 1:1
       ▼
┌──────────────┐
│    tentor    │
└──────┬───────┘
       │
       │ 1:N
       ▼
┌──────────────┐
│    jadwal    │
└──────┬───────┘
       │
       │ 1:N
       ▼
┌──────────────┐
│  pertemuan   │
└──────┬───────┘
       │
       │ 1:N
       ▼
┌──────────────┐
│   presensi   │
└──────┬───────┘
       │
       │ N:1
       ▼
┌──────────────┐
│    siswa     │
└──────────────┘
       ▲
       │
       │ N:1
┌──────┴───────────────┐
│  pendaftaran_siswa   │
└──────┬───────────────┘
       │
       │ N:1
       ▼
┌──────────────┐
│    kelas     │
└──────┬───────┘
       │
   ┌───┴────┐
   ▼        ▼
┌────────┐ ┌────────────┐
│ jenjang│ │  program   │
└────────┘ └────────────┘

siswa
  │
  │ M:N
  ▼
siswa_orang_tua
  │
  ▼
orang_tua

pendaftaran_siswa
       │
       ▼
     paket
```

---

# 21. Alur Data Utama

## 21.1 Alur Siswa

```text
Siswa
  ↓
Pendaftaran Siswa
  ↓
Kelas
  ↓
Program
  ↓
Jadwal
```

---

## 21.2 Alur Jadwal Mengajar

```text
Admin menentukan jadwal
          ↓
       jadwal
          ↓
     Pertemuan dibuat
          ↓
     Tentor mengisi
        presensi
          ↓
       presensi
```

---

## 21.3 Alur Penggantian Tentor

Contoh:

```text
Jadwal:
Kelas 9B
Senin 19:00
Tentor Budi
```

Budi izin.

Admin menunjuk Andi sebagai pengganti.

Data:

```text
jadwal.tentor_id = Budi
pertemuan.tentor_id = Andi
```

Dengan demikian:

- Jadwal normal tetap menunjukkan Budi.
- Histori pertemuan menunjukkan Andi.
- Laporan mengajar dapat mengetahui siapa yang benar-benar mengajar.

---

# 22. Fitur yang Didukung Database

## Tentor

Database mendukung:

- Profil tentor
- Username
- Ganti password
- Status akun aktif/nonaktif
- Monitoring login terakhir
- Dashboard
- Jadwal hari ini
- Jumlah sesi bulan ini
- Riwayat mengajar
- Input presensi
- Nilai sikap
- Nilai akademik
- Riwayat presensi
- Laporan mengajar

---

## Admin / Owner

Database mendukung:

- Login
- Dashboard statistik
- Data siswa
- Data orang tua
- Data tentor
- Status tentor aktif/nonaktif
- Status akun tentor
- Reset password
- Manajemen kelas
- Manajemen jadwal
- Penggantian tentor
- Broadcast
- Laporan siswa
- Laporan tentor
- Berita

---

## Siswa / Orang Tua

Database mendukung informasi publik:

- Informasi bimbel
- Berita
- Data tentor
- Informasi kelas
- Riwayat absensi siswa
- Rekap hadir
- Rekap sakit
- Rekap izin
- Rekap alfa

Siswa dan orang tua tidak memiliki akun.

---

# 23. Data yang Tidak Disimpan

Beberapa data sengaja tidak dibuatkan tabel khusus.

### 23.1 Jadwal Ketersediaan Tentor

Tidak disimpan.

Proses:

```text
Tentor mengirim ketersediaan
        ↓
WhatsApp
        ↓
Admin menentukan jadwal
        ↓
Admin memasukkan jadwal final
        ↓
tabel jadwal
```

---

### 23.2 Laporan

Tidak ada tabel `laporan`.

Laporan dihasilkan menggunakan query dari:

```text
jadwal
pertemuan
presensi
siswa
tentor
kelas
```

---

### 23.3 Statistik Dashboard

Tidak ada tabel statistik.

Statistik dihitung saat dibutuhkan.

Contoh:

```text
Siswa aktif
→ COUNT siswa aktif

Tentor aktif
→ COUNT tentor aktif

Sesi bulan ini
→ COUNT pertemuan bulan berjalan
```

---

### 23.4 Password Lama

Tidak disimpan.

Password lama hanya digunakan untuk verifikasi saat pengguna mengganti password.

---

# 24. Catatan Keamanan

## Password

Password wajib disimpan menggunakan hashing yang aman.

PHP dapat menggunakan:

```php
password_hash()
```

dan verifikasi:

```php
password_verify()
```

Tidak boleh menyimpan password plaintext.

---

## Username

`username` harus unik.

```text
UNIQUE(username)
```

---

## Foreign Key

Foreign key digunakan untuk menjaga integritas hubungan antar tabel.

Contoh:

```text
jadwal.kelas_id
    ↓
kelas.id
```

Jika suatu kelas tidak ada, jadwal tidak boleh menunjuk ke kelas tersebut.

---

# 25. Aturan Bisnis Utama

### Aturan 1 — Akun

Hanya:

```text
owner
admin
tentor
```

yang dapat login.

---

### Aturan 2 — Tentor

Setiap tentor memiliki satu akun pengguna.

---

### Aturan 3 — Siswa

Siswa tidak memiliki akun.

---

### Aturan 4 — Orang Tua

Orang tua tidak memiliki akun.

---

### Aturan 5 — Jadwal

Jadwal dibuat dan dikelola oleh admin/owner.

---

### Aturan 6 — Ketersediaan Tentor

Ketersediaan bulanan tentor tidak dikelola melalui sistem.

---

### Aturan 7 — Penggantian Tentor

Tentor pada `jadwal` dapat berbeda dengan tentor pada `pertemuan`.

---

### Aturan 8 — Presensi

Presensi dibuat untuk setiap siswa dalam satu pertemuan.

---

### Aturan 9 — Nilai

Nilai terdiri dari:

```text
Nilai Sikap
Nilai Akademik
```

dengan kategori:

```text
A
B
C
D
```

---

### Aturan 10 — Histori Siswa

Perubahan kelas siswa tidak menghapus histori.

Histori disimpan melalui `pendaftaran_siswa`.

---

### Aturan 11 — Laporan

Laporan dihasilkan dari data transaksi, bukan disimpan sebagai tabel laporan.

---

# 26. Struktur Modul Sistem

Database mendukung modul:

```text
AUTENTIKASI
├── pengguna
└── tentor

MASTER DATA
├── siswa
├── orang_tua
├── jenjang
├── program
├── paket
└── kelas

AKADEMIK
├── pendaftaran_siswa
├── jadwal
├── pertemuan
└── presensi

KOMUNIKASI
├── pengumuman
└── berita

PELAPORAN
└── Query dari data akademik
```

---

# 27. Status Draft

Struktur ini merupakan **database specification versi 1** berdasarkan business case yang telah dibahas.

Sebelum dibuat menjadi SQL final, beberapa hal yang perlu divalidasi kembali pada tahap implementasi adalah:

1. Apakah kelas private benar-benar menggunakan entitas `kelas` seperti kelas reguler. ya
2. Apakah satu siswa dapat memiliki lebih dari satu pendaftaran aktif secara bersamaan. 
3. Aturan pasti perhitungan rekap gaji tentor. belum
4. Aturan detail broadcast. tidak ada
5. Apakah akses owner dan admin benar-benar sama atau memiliki hak akses berbeda. sama
6. Apakah halaman publik memerlukan verifikasi tambahan ketika melihat data absensi siswa. tidak

Hal-hal tersebut tidak mengubah fondasi utama database, tetapi dapat memengaruhi constraint dan implementasi aplikasi.