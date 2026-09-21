# Log Perkembangan Frontend & Integration — Siatama Privat

Dokumen ini mencatat histori perubahan frontend, penyelarasan tampilan views PHP dengan `@ui-draft`, serta pencatatan todo/gap analisis backend.

> **Note (Metode Agile):** Seluruh arsitektur & modul backend ini dikembangkan dengan pendekatan **Agile (Iteratif & Inkremental)**. Kode dibuat sangat modular dan reusable sehingga sewaktu-waktu dapat dengan mudah disesuaikan jika ada perubahan kebutuhan dari tim UI/UX maupun perkembangan bisnis bimbel di masa mendatang.

---

## 📌 Status Terakhir (2026-09-21)

### 🔵 1. Modernisasi Styling Input Form & UI Components (100% Selesai)
Seluruh elemen form `input`, `select`, `textarea`, dan `button` pada seluruh halaman views telah di-refactor penuh dari styling HTML native lama menjadi UI komponen modern Tailwind CSS berbasis `@ui-draft`:
- **Auth Login (`app/Views/auth/login.php`)**: Form login dengan input group icon, rounded-xl border, dan fokus ring emerald.
- **Form Admin Siswa (`app/Views/admin/siswa/form.php`)**: Form identitas siswa & dynamic row wali murid menggunakan rounded-xl inputs dengan icon label.
- **Form Admin Tentor (`app/Views/admin/tentor/form.php`)**: Form profil tentor dengan select dropdown modern, file uploader, dan textarea bio.
- **Form Admin Jadwal (`app/Views/admin/jadwal/form.php`)**: Form penugasan jadwal dengan time picker & select dropdown modern.
- **Form Presensi Admin (`app/Views/admin/pertemuan/presensi.php`)**: Grid input presensi siswa, status kehadiran, nilai sikap & akademik.
- **Form Pertemuan Tentor (`app/Views/tentor/pertemuan/form.php`)**: Form catat sesi pertemuan mengajar aktual.
- **Form Cek Presensi Publik (`app/Views/public/cek_presensi.php`)**: Search card presensi publik dengan rounded-xl input & badge status.

---

### 🔵 2. Penyesuaian UI Draft - Modul Admin (100% Selesai)
- ✅ Layout & Design System (`app/Views/admin/layout.php`): Tailwind CSS CDN, Montserrat/Inter, Material Symbols, TopBar & Bottom Nav.
- ✅ Beranda Admin (`app/Views/admin/beranda.php`): Ringkasan Statistik Card, Quick Action Menu, Pantauan Jadwal Mengajar.
- ✅ Direktori Siswa (`app/Views/admin/siswa/index.php`): Card Siswa, Sekolah, Wali Murid, Live Search.
- ✅ Master Data Tentor (`app/Views/admin/tentor/index.php`): Titik status aktif, Avatar, Univ, Telp, Live Search.
- ✅ Manajemen Jadwal (`app/Views/admin/jadwal/index.php`): Card Sesi Mengajar, Jenjang, Program, Ruangan.
- ✅ Pusat Pertemuan & Presensi (`app/Views/admin/pertemuan/index.php`): Sesi Pertemuan, Badge Presensi.
- ✅ Manajemen Berita (`app/Views/admin/berita/index.php`): Thumbnail Foto, Status Terbit/Draft.

---

### 🔵 3. Penyesuaian UI Draft - Modul Tentor (100% Selesai)
- ✅ Layout Tentor (`app/Views/tentor/layout.php`): TopBar profil tentor & Bottom Nav 3 Menu.
- ✅ Beranda Tentor (`app/Views/tentor/beranda.php`): Banner Pengumuman, Widget Statistik Sesi, Card Jadwal Hari Ini.
- ✅ Jadwal Saya Tentor (`app/Views/tentor/jadwal/index.php`): Header Jadwal, Live Search, Jam & Ruangan.
- ✅ Laporan & Pertemuan Tentor (`app/Views/tentor/pertemuan/index.php`): Card Sesi Pertemuan, Catat Presensi Siswa.

---

### 🔵 4. Penyesuaian UI Draft - Modul Publik / User Landing Page (100% Selesai)
- ✅ Layout Publik (`app/Views/public/layout.php`): Navbar Brand Siatama Privat & Bottom Nav Publik.
- ✅ Beranda Publik (`app/Views/public/home.php`): Hero Section "Apa itu Siatama Privat?", Program Belajar, Tentor Kami, Berita.
- ✅ Cek Presensi Publik (`app/Views/public/cek_presensi.php`): Pencarian NIS/Nama/Sekolah, filter Bulan & Tahun, card identitas siswa, ringkasan kehadiran dengan progress bar % hadir, badge status semantik per kehadiran, dan nilai sikap/akademik dengan keterangan huruf.

---

### 🔵 5. Backend Gaps — Database, Models, Controllers & Helpers (100% Selesai)

#### Database Schema (`database/schema.sql`)
- ✅ `tentor`: Penambahan kolom `rate_gaji_per_jam` & `tarif_per_sesi` (DECIMAL).
- ✅ `pengumuman`: Penambahan kolom `tipe_broadcast` ENUM & `kategori` VARCHAR.
- ✅ Tabel `notifikasi` baru: `id`, `pengguna_id`, `judul`, `pesan`, `sudah_dibaca`, `dibuat_pada`.

#### Models (`app/Models/`)
- ✅ `Tentor.php`: `getMonthlyPayrollSummary(month, year)` & `getTeachingPerformance(tentorId, month, year)`.
- ✅ `Jadwal.php`: `getTodayRealtimeScheduleWithStatus()` — status realtime via `TIME(NOW())`.
- ✅ `Pertemuan.php`: `getMonthlyReportByClass(kelasId, month, year)` — rekap bulanan + summary per siswa.
- ✅ `Pengumuman.php`: `getLatestActiveAnnouncements(peran, limit)` — filter `target_peran`.

#### Controllers (`app/Controllers/`)
- ✅ `Admin\DashboardController.php`: Integrasi `getTodayRealtimeScheduleWithStatus()` via `AdminDashboardData.php`.
- ✅ `Admin\LaporanController.php` (Baru): `index()`, `siswaBulanan()`, `tentorBulanan()`, `exportSiswaCsv()`, `exportTentorCsv()` — routes `/admin/laporan/*` terdaftar, nav item "Laporan" ditambah ke bottom nav admin.
- ✅ `Tentor\DashboardController.php`: Agregasi `total_jam` & `persentase_kehadiran` bulan berjalan via `getTeachingPerformance()`.
- ✅ `PublicController.php` — `cekPresensi()`: Pencarian NIS/Nama + filter Bulan/Tahun, resolve `$selectedSiswa`, pass `activeNav`.

#### Helpers (`app/Support/helpers.php`)
- ✅ `status_sesi_mengajar(jamMulai, jamSelesai)`.
- ✅ `format_rupiah(nominal)`.
- ✅ `konversi_nilai_huruf(nilai)`.

---

> ✅ **Semua item dari `todo.md` telah selesai dikerjakan.**
