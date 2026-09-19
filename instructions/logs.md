# Log Perkembangan Backend — Siatama Privat

Dokumen ini mencatat histori perubahan backend, modul yang telah selesai dibangun, serta rencana langkah selanjutnya (*next steps*).

> **Note (Metode Agile):** Seluruh arsitektur & modul backend ini dikembangkan dengan pendekatan **Agile (Iteratif & Inkremental)**. Kode dibuat sangat modular dan reusable sehingga sewaktu-waktu dapat dengan mudah disesuaikan jika ada perubahan kebutuhan dari tim UI/UX maupun perkembangan bisnis bimbel di masa mendatang.

---

## 📌 Status Terakhir (2026-09-19)

### 🟢 1. Modul Admin (100% Selesai)
- **Arsitektur Utama**:
  - Custom HTTP Router & Autoloader PSR-4 (`/public/index.php`, `App\Core\Router`).
  - Base Model PDO Prepared Statement (`App\Core\Model`).
  - Base Controller & Flash Session Notification (`App\Core\Controller`, `App\Views\admin\layout.php`).
  - Reusable CSS Utilities Design System (`/public/assets/admin.css`) berbasis `ui-guide.md`.
- **Modul CRUD Admin**:
  - **Manajemen Pengguna (`pengguna`)**: Login credentials & peran (`owner`, `admin`, `tentor`).
  - **Master Data**: `jenjang`, `program`, `paket`, `kelas`.
  - **Master Entitas**: `tentor` (Profil 1:1), `siswa`, `orang_tua`, `siswa_orang_tua` (Multi-Wali dengan Dynamic JS Form).
  - **Akademik & Transaksi**: `pendaftaran_siswa` (Penempatan kelas & histori perpindahan), `jadwal` (Penjadwalan hari/jam mengajar).
  - **Sesi Pertemuan & Presensi**: `pertemuan` (Pertemuan mengajar aktual tentor) & `presensi` (Kehadiran `hadir`/`sakit`/`izin`/`alfa`, nilai sikap & akademik A-D, catatan tentor).
  - **Informasi & Publikasi**: `pengumuman` (Broadcast internal) & `berita` (Artikel landing page & slug generator).

---

### 🟢 2. Modul Tentor (100% Selesai)
- **🟢 Auth & Portal Login (`/login`, `/logout`)**:
  - `AuthController.php`, view `auth/login.php` & `auth/layout.php`.
  - Verifikasi hash password aman (`password_verify()`), pencatatan `terakhir_login`, penanganan session akun & pengarahan sesuai peran (`tentor` vs `admin/owner`).
- **🟢 Dashboard Tentor (`/tentor/beranda`)**:
  - `Tentor\DashboardController.php`, view `tentor/beranda.php` & `tentor/layout.php`.
  - Proteksi middleware session khusus peran `tentor`.
  - Menampilkan ringkasan statistik mengajar tentor login, jadwal mengajar hari ini, & pengumuman.
- **🟢 Jadwal Saya (`/tentor/jadwal`)**:
  - `Tentor\AkademikController::jadwal()`, view `tentor/jadwal/index.php`.
  - Menampilkan jadwal aktif yang ditugaskan khusus untuk tentor yang sedang login.
- **🟢 Sesi Pertemuan & Presensi (`/tentor/pertemuan`)**:
  - `Tentor\AkademikController::pertemuan()`, `createPertemuan()`, `presensi()`, & `updatePresensi()`.
  - Tentor dapat mencatat sesi mengajar aktual, memilih kelas jadwal, serta mengisi kehadiran (`hadir`/`sakit`/`izin`/`alfa`), nilai sikap & akademik (A-D), dan catatan perkembangan siswa.

### 🟢 3. Modul Sisi Publik / Landing Page (100% Selesai)
- **🟢 Beranda Publik (`/`)**:
  - `PublicController::index()`, view `public/home.php` & `public/layout.php`.
  - Menampilkan hero banner, pilihan program pembelajaran, profil tentor/pengajar, serta berita terbaru.
- **🟢 Berita Publik & Detail Artikel (`/berita`, `/berita/{slug}`)**:
  - `PublicController::beritaList()` & `beritaDetail()`, view `public/berita/index.php` & `public/berita/detail.php`.
  - Menampilkan berita publik dengan pencarian berbasis slug unik.
- **🟢 Cek Presensi Siswa Publik (`/cek-presensi`)**:
  - `PublicController::cekPresensi()`, view `public/cek_presensi.php`.
  - Memungkinkan orang tua/publik mencari riwayat kehadiran, nilai sikap, nilai akademik, dan catatan perkembangan siswa tanpa perlu proses login (sesuai spesifikasi `2.3`).

### 🟢 4. Verification & Validation (V&V) (100% Selesai)
- **🟢 Automated Test Suite (`/tests/run_all_tests.php`)**:
  - Dibuat suite pengujian otomatis untuk Unit Testing & Whitebox Testing.
  - Memverifikasi pattern matching HTTP Router, sanitasi slug artikel berita, enkripsi hash password akun, relasi 1:1 profil tentor, serta sinkronisasi otomatis presensi siswa saat sesi pertemuan dibuat.
- **🟢 Laporan Formal V&V IEEE/ISO/IEC 15288:2023 (`/instructions/vv-log.md`)**:
  - Dokumentasi formal pengujian V&V sesuai standar internasional IEEE/ISO/IEC 15288:2023 (Clause 6.4.9 Verification Process & Clause 6.4.11 Validation Process).
- **🟢 Dokumen Ringkasan Unit & Whitebox Test (`/instructions/unit-and-whitebox-test-summary.md`)**:
  - Dokumentasi khusus teknis pengujian Unit Test (Router parameter extraction, Berita Slug generator) dan Whitebox Test (Branch Coverage 404, Enkripsi Hash Password, Relasi Data 1:1, Rule No-Login Siswa/Wali, Auto-sync Presensi).
- **🟢 Panduan Blackbox Testing (`/instructions/blackbox-testing-guide.md`)**:
  - Panduan langkah demi langkah pengujian UI/UX untuk Windows & Linux/macOS, perintah `php -S`, kredensial akun uji coba, serta daftar checklist test cases.




---

## ✅ Ringkasan Status Proyek Backend

- **Modul Admin**: 100% Selesai
- **Modul Tentor**: 100% Selesai
- **Modul Publik**: 100% Selesai
- **Verification & Validation (V&V)**: 100% Selesai (9/9 Test Passed)

Backend aplikasi Sistem Informasi Bimbingan Belajar Siatama Privat telah teruji secara penuh, modular, maintainable, dan sesuai dengan dokumen spesifikasi database & UI guide!




