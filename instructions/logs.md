# Log Perkembangan Frontend & Integration — Siatama Privat

Dokumen ini mencatat histori perubahan frontend, penyelarasan tampilan views PHP dengan `@ui-draft`, serta pencatatan todo/gap analisis backend.

> **Note (Metode Agile):** Seluruh arsitektur & modul backend ini dikembangkan dengan pendekatan **Agile (Iteratif & Inkremental)**. Kode dibuat sangat modular dan reusable sehingga sewaktu-waktu dapat dengan mudah disesuaikan jika ada perubahan kebutuhan dari tim UI/UX maupun perkembangan bisnis bimbel di masa mendatang.

---

## 📌 Status Terakhir (2026-09-21)

### 🔵 1. Penyesuaian UI Draft - Modul Admin (100% Selesai)
- ** Layout & Design System (`app/Views/admin/layout.php`)**:
  - Diupdate menggunakan Tailwind CSS CDN, Google Fonts (Inter, Montserrat), dan Material Symbols Outlined sesuai spesifikasi `@ui-draft`.
  - TopBar Header disesuaikan dengan avatar inisial, nama pengguna session (`$_SESSION['user_nama']`), badge peran, serta tombol logout.
  - Bottom Navigation Bar disesuaikan dengan ikon modern (Beranda, Jadwal, Siswa, Tentor, Pertemuan).
- ** Beranda Admin (`app/Views/admin/beranda.php`)**:
  - Disesuaikan dengan mockup `admin_dashboard_siatama_privat_updated/code.html` (Statistik Card, Quick Action, Pantauan Jadwal Hari Ini, Pengumuman).
- ** Direktori Siswa (`app/Views/admin/siswa/index.php`)**:
  - Disesuaikan dengan mockup `direktori_siswa_siatama_privat/code.html` (Card Siswa, Sekolah, Wali Murid, Live Search).
- ** Master Data Tentor (`app/Views/admin/tentor/index.php`)**:
  - Disesuaikan dengan mockup `master_data_tentor_siatama_privat/code.html` (Indikator titik status aktif, Foto/Avatar, Univ, Telp, Live Search).
- ** Manajemen Jadwal (`app/Views/admin/jadwal/index.php`)**:
  - Disesuaikan dengan mockup `jadwal_harian_admin_siatama_privat/code.html` (Hari & Jam Mengajar, Jenjang, Program, Kelas, Tentor, Ruangan).
- ** Pusat Pertemuan & Presensi (`app/Views/admin/pertemuan/index.php`)**:
  - Disesuaikan dengan mockup `pusat_laporan_rekap_siatama_privat/code.html` (Sesi Pertemuan, Badge Presensi Hadir/Total, Tombol Kelola Presensi).
- ** Manajemen Berita (`app/Views/admin/berita/index.php`)**:
  - Disesuaikan dengan mockup `manajemen_berita_siatama_privat/code.html` (Thumbnail Foto/Placeholder, Status Terbit/Draft, Date).

---

### 🔵 2. Penyesuaian UI Draft - Modul Tentor (100% Selesai)
- ** Layout Tentor (`app/Views/tentor/layout.php`)**:
  - Di-refactor dengan Tailwind CSS CDN, Material Symbols Outlined, TopBar profil tentor, serta Bottom Nav 3 Menu (Beranda, Jadwal Saya, Sesi & Presensi).
- ** Beranda Tentor (`app/Views/tentor/beranda.php`)**:
  - Disesuaikan dengan mockup `tutor_dashboard_siatama_privat_modern/code.html` (Banner Pengumuman Penting, Widget Statistik Sesi Bulan Ini, Card Jadwal Mengajar Hari Ini).
- ** Jadwal Saya Tentor (`app/Views/tentor/jadwal/index.php`)**:
  - Disesuaikan dengan mockup `jadwal_mengajar_siatama_privat_scroll_lancar/code.html` (Header Jadwal Tentor, Live Search Bar, Detail Jam & Ruangan).
- ** Laporan & Pertemuan Tentor (`app/Views/tentor/pertemuan/index.php`)**:
  - Disesuaikan dengan mockup `laporan_mengajar_siatama_privat_baru/code.html` (Card Sesi Pertemuan, Badge Presensi Hadir/Total, Tombol Catat/Edit Presensi Siswa).

---

### 🔵 3. Penyesuaian UI Draft - Modul Publik / User Landing Page (100% Selesai)
- ** Layout Publik (`app/Views/public/layout.php`)**:
  - Di-refactor dengan Tailwind CSS CDN, Navbar Brand Siatama Privat, Top Login Button, serta Bottom Nav 3 Menu (Beranda, Berita, Cek Presensi).
- ** Beranda Publik (`app/Views/public/home.php`)**:
  - Disesuaikan dengan mockup `dashboard_siatama_privat_updated_contact/code.html` (Hero Section "Apa itu Siatama Privat?", Grid Program Pembelajaran, Profil Tentor Berkualitas, serta Berita Terbaru).

---

## 📌 Catatan Rencana Pengembangan Backend Spesifik Berdasarkan `@instructions/todo.md`

Berdasarkan hasil audit komprehensif antara `@ui-draft`, database, models, controllers, & helpers, berikut adalah perincian teknis backend yang dicatat di `instructions/todo.md`:

1. **Database Schema (`database/schema.sql`)**:
   - `tentor`: Penambahan atribut `rate_gaji_per_jam` / `tarif_per_sesi` untuk penghitungan honorarium.
   - `pengumuman`: Penambahan `tipe_broadcast` dan `kategori` untuk kategorisasi pengumuman.
   - `notifikasi`: Pembuatan tabel baru untuk mendukung lonceng notifikasi pengguna.
2. **Models & Logic (`app/Models/`)**:
   - `Tentor.php`: Method `getMonthlyPayrollSummary()` & `getTeachingPerformance()`.
   - `Jadwal.php`: Method `getTodayRealtimeScheduleWithStatus()` (Penentuan status Realtime Selesai/Sedang Berlangsung/Belum Mulai).
   - `Pertemuan.php`: Method `getMonthlyReportByClass()`.
   - `Pengumuman.php`: Method `getLatestActiveAnnouncements()`.
3. **Controllers & Business Logic (`app/Controllers/`)**:
   - `Admin\DashboardController.php`: Integrasi pantauan realtime mengajar berbasis jam server.
   - `Admin\LaporanController.php` (Baru): Controller khusus laporan rekap siswa & honorarium tentor.
   - `Tentor\DashboardController.php`: Agregasi kinerja bulan berjalan (jam ajar & % presensi).
   - `PublicController.php`: Filter program publik berbasis jenjang dinamis.
4. **Helpers (`app/Support/helpers.php`)**:
   - Helper `status_sesi_mengajar()`, `format_rupiah()`, dan `konversi_nilai_huruf()`.
