# TODO & Gaps Analysis Backend vs UI Draft (Detail Spesifikasi Teknis)

Dokumen ini mencatat analisis kekurangan teknis backend (Database Schema, Models, Logic, Controllers, & Support Helpers) setelah mempelajari seluruh UI/UX di `@ui-draft`. Dokumen ini menjadi acuan spesifik untuk pengerjaan iterasi backend berikutnya.

---

## 🗄️ 1. Database & Schema Gaps (`database/schema.sql`)

- [x] **Modul Rekap Gaji / Payroll Tentor**:
  - `tentor`: Penambahan kolom `rate_gaji_per_jam` & `tarif_per_sesi` (DECIMAL). ✅
- [x] **Modul Broadcast Pengumuman**:
  - `pengumuman`: Penambahan kolom `tipe_broadcast` ENUM('banner', 'popup', 'push') & `kategori` VARCHAR(50). ✅
- [x] **Pemberitahuan / Notifications System**:
  - Tabel `notifikasi` (`id`, `pengguna_id`, `judul`, `pesan`, `sudah_dibaca`, `dibuat_pada`). ✅

---

## 🧠 2. Models & Data Aggregation Gaps (`app/Models/`)

- [x] **`App\Models\Tentor`**:
  - `getMonthlyPayrollSummary(int $month, int $year)` — total jam, sesi, estimasi honorarium per tentor. ✅
  - `getTeachingPerformance(int $tentorId, int $month, int $year)` — widget kinerja tentor. ✅
- [x] **`App\Models\Jadwal`**:
  - `getTodayRealtimeScheduleWithStatus()` — status realtime `selesai`/`sedang_berlangsung`/`belum_mulai`. ✅
- [x] **`App\Models\Pertemuan`**:
  - `getMonthlyReportByClass(int $kelasId, int $month, int $year)` — rekap bulanan kelas + summary per siswa. ✅
- [x] **`App\Models\Pengumuman`**:
  - `getLatestActiveAnnouncements(string $peran)` — filter berdasarkan target peran. ✅

---

## ⚙️ 3. Controllers & Business Logic Gaps (`app/Controllers/`)

- [x] **`App\Controllers\Admin\DashboardController`**:
  - Integrasi `getTodayRealtimeScheduleWithStatus()` via `AdminDashboardData.php`. ✅
- [x] **`App\Controllers\Admin\LaporanController` (Controller Baru)**:
  - `LaporanController.php` dibuat untuk route `/admin/laporan`:
    - `index()`: Pusat Laporan & Rekap.
    - `siswaBulanan()`: Rekap bulanan presensi & nilai per kelas.
    - `tentorBulanan()`: Rekap log mengajar & verifikasi honorarium tentor.
    - `exportSiswaCsv()` / `exportTentorCsv()`: Handler download CSV. ✅
- [x] **`App\Controllers\Tentor\DashboardController`**:
  - Supply `stats['total_jam']` & `stats['persentase_kehadiran']` bulan berjalan via `getTeachingPerformance()`. ✅
- [x] **`App\Controllers\PublicController`**:
  - `cekPresensi()`: Pencarian NIS/Nama + filter Bulan/Tahun, card identitas siswa terpilih, `activeNav` pass. ✅

---

## 🛠️ 4. Support Helpers & Utils (`app/Support/`)

- [x] **`app/Support/helpers.php`**:
  - `status_sesi_mengajar(string $jamMulai, string $jamSelesai)` ✅
  - `format_rupiah(int $nominal)` ✅
  - `konversi_nilai_huruf(string $nilai)` ✅
