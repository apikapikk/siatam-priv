# TODO & Gaps Analysis Backend vs UI Draft (Detail Spesifikasi Teknis)

Dokumen ini mencatat analisis kekurangan teknis backend (Database Schema, Models, Logic, Controllers, & Support Helpers) setelah mempelajari seluruh UI/UX di `@ui-draft`. Dokumen ini menjadi acuan spesifik untuk pengerjaan iterasi backend berikutnya.

---

## 🗄️ 1. Database & Schema Gaps (`database/schema.sql`)

- [ ] **Modul Rekap Gaji / Payroll Tentor**:
  - `tentor`: Belum ada atribut `rate_gaji_per_jam` atau `tarif_per_sesi` (DECIMAL/INT). Perlu penambahan kolom untuk menghitung estimasi honorarium mengajar tentor.
- [ ] **Modul Broadcast Pengumuman**:
  - `pengumuman`: Perlu penambahan kolom `tipe_broadcast` ENUM('banner', 'popup', 'push') dan `kategori` VARCHAR(50) untuk mendukung filtering UI Draft pengumuman ("Penting", "Akademik", "Umum").
- [ ] **Pemberitahuan / Notifications System**:
  - Belum ada tabel `notifikasi` (`id`, `pengguna_id`, `judul`, `pesan`, `sudah_dibaca`, `dibuat_pada`) untuk mendukung lonceng notifikasi di TopBar Header Admin/Tentor UI draft.

---

## 🧠 2. Models & Data Aggregation Gaps (`app/Models/`)

- [ ] **`App\Models\Tentor`**:
  - Belum ada method `getMonthlyPayrollSummary(int $month, int $year)` untuk menghitung total jam mengajar, total sesi hadir, dan total estimasi honorarium per tentor.
  - Belum ada method `getTeachingPerformance(int $tentorId, int $month, int $year)` untuk mendukung widget kinerja di UI Draft Tentor (Total Jam, Kehadiran %, Total Sesi).
- [ ] **`App\Models\Jadwal`**:
  - Belum ada method `getTodayRealtimeScheduleWithStatus()` yang membandingkan `TIME(NOW())` dengan `jam_mulai` & `jam_selesai` untuk mengembalikan status realtime: `'selesai'`, `'sedang_berlangsung'`, atau `'belum_mulai'` (untuk widget Pantauan Mengajar Hari Ini di Admin Dashboard).
- [ ] **`App\Models\Pertemuan`**:
  - Belum ada method `getMonthlyReportByClass(int $kelasId, int $month, int $year)` untuk menghasilkan rekap bulanan kelas (Tabel Absensi Harian + Konversi Nilai Sikap/Akademik untuk Laporan Siswa Bulanan di `admin-part-2`).
- [ ] **`App\Models\Pengumuman`**:
  - Belum ada scope / filter method `getLatestActiveAnnouncements(string $peran)` untuk mengambil pengumuman berdasarkan target peran pengajar/semua.

---

## ⚙️ 3. Controllers & Business Logic Gaps (`app/Controllers/`)

- [ ] **`App\Controllers\Admin\DashboardController`**:
  - Integrasi data `getTodayRealtimeScheduleWithStatus()` agar status pantauan mengajar (Selesai/Sedang Berlangsung/Belum Mulai) dinamis sesuai jam berjalan.
- [ ] **`App\Controllers\Admin\LaporanController` (Controller Baru Needed)**:
  - Perlu dibuat `LaporanController.php` untuk menangani route `/admin/laporan`:
    - `index()`: Pusat Laporan & Rekap.
    - `siswaBulanan()`: Rekap bulanan presensi & nilai per kelas.
    - `tentorBulanan()`: Rekap log mengajar & verifikasi honorarium tentor.
    - `exportPdf()` / `exportExcel()`: Handler download laporan (CSV/Excel).
- [ ] **`App\Controllers\Tentor\DashboardController`**:
  - Menyuplai data ringkasan kinerja bulan berjalan (`stats['total_jam']`, `stats['persentase_kehadiran']`) ke view `tentor/beranda.php`.
- [ ] **`App\Controllers\PublicController`**:
  - `cekPresensi()`: Perlu menambahkan pencarian berdasarkan NIS / Nama Siswa & Bulan untuk menampilkan riwayat presensi & grafik kehadiran siswa tanpa login.

---

## 🛠️ 4. Support Helpers & Utils (`app/Support/`)

- [ ] **`app/Support/helpers.php`**:
  - Tambahkan helper `status_sesi_mengajar(string $jamMulai, string $jamSelesai)` untuk menentukan status waktu mengajar secara presisi di level View.
  - Tambahkan helper `format_rupiah(int $nominal)` untuk format nominal honorarium tentor.
  - Tambahkan helper `konversi_nilai_huruf(string $nilai)` untuk formatting indikator huruf (A=Sangat Baik, B=Baik, C=Cukup, D=Kurang).
