# Panduan Testing Blackbox & Cara Menjalankan Aplikasi — Siatama Privat

Dokumen ini berisi panduan langkah demi langkah untuk menjalankan aplikasi **Siatama Privat** dan melakukan **Blackbox Testing** (pengujian tampilan & fungsionalitas dari sudut pandang pengguna/penguji).

Panduan ini dirancang agar mudah diikuti baik oleh pengguna **Windows**, **Linux**, maupun **macOS**.

---

## 🚀 1. Cara Menjalankan Server Aplikasi (Built-in PHP Development Server)

Untuk menjalankan aplikasi ini di komputer lokal, ikuti langkah-langkah berikut:

### 📌 A. Persiapan Awal
1. Pastikan **PHP** (versi 8.0 ke atas) dan **MySQL / MariaDB** (XAMPP / Laragon / Native) sudah aktif.
2. Pastikan database `bimbel_db` sudah di-import SQL schema dan seed data:
   - File schema: `database/schema.sql`
   - File seed data: `database/seed.sql`

---

### 📌 B. Perintah Menjalankan Server (`php -S`)

Buka terminal / Command Prompt / PowerShell di folder proyek ini (`siatama-private`), lalu jalankan perintah:

#### 🟢 Untuk Windows (Command Prompt / PowerShell / Git Bash):
```cmd
php -S localhost:8000 -t public public/router.php
```
*Atau:*
```powershell
php -S 127.0.0.1:8000 -t public public/router.php
```

#### 🐧 Untuk Linux / macOS:
```bash
php -S localhost:8000 -t public public/router.php
```

> ⚠️ **PENTING untuk Windows & Router PHP:**  
> Parameter `-t public public/router.php` **wajib disertakan** agar semua route (seperti `/login`, `/admin/beranda`, `/tentor/jadwal`, dll) dan file aset CSS/gambar dapat diarahkan dan dibaca dengan benar oleh PHP built-in server.

---

### 📌 C. Akses Aplikasi di Browser

Setelah server berjalan, buka browser (Chrome/Edge/Firefox) dan akses URL berikut:

- **Halaman Publik / Landing Page:**  
  `http://localhost:8000/`
- **Cek Presensi Siswa (Publik / Tanpa Login):**  
  `http://localhost:8000/cek-presensi`
- **Halaman Login:**  
  `http://localhost:8000/login`

---

## 🔑 2. Akun Uji Coba (Login Credentials)

Gunakan akun berikut untuk melakukan pengujian berdasarkan peran pengguna:

| Peran | Username | Password | Hak Akses & Fitur yang Diuji |
|:---|:---|:---|:---|
| **Admin / Owner** | `admin` | `admin123` | Akses penuh Dashboard Admin (`/admin/beranda`), CRUD Pengguna, Master Data (Jenjang, Kelas), Data Siswa & Wali Murid, Penjadwalan, Pertemuan & Presensi, Pengumuman, dan Berita |
| **Tentor / Pengajar** | `tentor1` | `tentor123` | Portal Tentor (`/tentor/beranda`), Menampilkan Jadwal Mengajar Pribadi, Mengisi Sesi Pertemuan & Presensi Kehadiran/Nilai Siswa |

---

## 🧪 3. Skenario Pengujian Blackbox (Test Cases Checklist)

Berikut adalah daftar fitur yang dapat diuji secara langsung melalui browser:

### 📋 A. Halaman Publik (Tanpa Login)
- [ ] **Beranda (`/`)**: Menampilkan Hero Banner, pilihan Program Pembelajaran, Profil Tentor, dan Berita Terbaru.
- [ ] **Berita & Detail (`/berita`)**: Membaca daftar berita dan membuka detail berita berbasis URL slug.
- [ ] **Cek Presensi (`/cek-presensi`)**: Memilih siswa untuk melihat riwayat kehadiran, nilai sikap, nilai akademik, dan catatan tentor tanpa login.

---

### 📋 B. Autentikasi & Hak Akses
- [ ] **Form Login (`/login`)**: 
  - Login dengan akun salah -> Tampil pesan error.
  - Login akun `admin` -> Masuk ke Dashboard Admin (`/admin/beranda`).
  - Login akun `tentor1` -> Masuk ke Portal Tentor (`/tentor/beranda`).
- [ ] **Logout (`/logout`)**: Berhasil keluar dan kembali ke halaman login.
- [ ] **Proteksi Middleware**: Mencoba akses `/admin/beranda` tanpa login -> Otomatis diarahkan kembali ke `/login`.

---

### 📋 C. Modul Portal Admin (`/admin/...`)
- [ ] **Manajemen Pengguna**: Tambah/Edit/Hapus akun owner, admin, dan tentor.
- [ ] **Master Data**: Kelola Jenjang Pendidikan dan Kelas.
- [ ] **Data Siswa & Wali Murid**: Input data siswa beserta multi-wali murid (Ayah/Ibu/Wali) menggunakan tombol dynamic input JS.
- [ ] **Pendaftaran Siswa**: Masukkan siswa ke kelas dan paket pembelajaran.
- [ ] **Penjadwalan Mengajar**: Tetapkan jadwal hari, jam, dan tentor untuk kelas.
- [ ] **Pertemuan & Presensi**: Buat sesi pertemuan baru dan isi presensi siswa (`hadir`/`sakit`/`izin`/`alfa`) serta nilai A–D.
- [ ] **Pengumuman & Berita**: Tulis pengumuman internal dan buat berita publik.

---

### 📋 D. Modul Portal Tentor (`/tentor/...`)
- [ ] **Dashboard Tentor**: Menampilkan statistik mengajar tentor login & pengumuman.
- [ ] **Jadwal Saya**: Menampilkan daftar jadwal mengajar khusus tentor yang sedang login.
- [ ] **Input Pertemuan & Presensi**: Tentor mencatat sesi mengajar aktual dan memberi penilaian/catatan pada siswa.

---

## ❓ Troubleshoot / FAQ (Khusus Windows)

1. **Muncul error `404 Not Found` saat mengklik menu di Windows?**  
   *Penyebab:* Server dijalankan tanpa menunjuk file `public/router.php`.  
   *Solusi:* Hentikan server (CTRL+C), lalu jalankan ulang dengan perintah:  
   `php -S localhost:8000 -t public public/router.php`

2. **Perubahan data tidak muncul di browser?**  
   *Solusi:* Tekan `CTRL + F5` di browser untuk melakukan Hard Refresh (membersihkan cache browser).
