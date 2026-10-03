# Log Perkembangan Frontend & Integration — Siatama Privat

## 2026-10-03 — Pembuatan Akun Tentor Otomatis dan Reset Password

- Status: **DONE**.
- Form `/admin/tentor/tambah` tidak lagi memilih akun pengguna dari dropdown; admin sekarang mengisi username login tentor secara langsung.
- Backend: saat profil tentor dibuat, sistem otomatis membuat akun `pengguna` dengan peran `tentor` dalam transaksi yang sama.
- Backend: password dibuat acak, disimpan hanya dalam bentuk hash, dan tidak pernah disimpan sebagai plaintext.
- Frontend: kredensial awal ditampilkan setelah pembuatan selama maksimal 10 menit, dilengkapi countdown dan tombol salin.
- Backend/frontend: ditambahkan reset password untuk akun tentor yang sudah ada melalui `POST /admin/tentor/{id}/reset-password`.
- Reset password membatalkan password lama dan menampilkan password baru sementara dengan mekanisme 10 menit yang sama.
- Form edit tentor sekarang mengelola username langsung dan menyinkronkan status aktif ke akun pengguna.
- Validasi: seluruh PHP terkait lolos `php -l`; tidak ada lagi dropdown `Akun Pengguna` pada form tentor.

## 2026-10-03 — Menghapus Input Gaji Tentor dan Filter Status Laporan Siswa

- Status: **DONE**.
- Admin tentor: field `Rate Gaji per Jam` dan `Tarif per Sesi` dihapus dari form tambah/edit.
- Backend tentor: proses create/update tidak lagi membaca atau menyimpan nilai gaji dari request; kolom database lama dibiarkan untuk kompatibilitas data.
- Laporan siswa harian: ditambahkan filter `Semua`, `Sakit`, `Izin`, dan `Alfa`.
- Backend laporan: `Pertemuan::getDailyReportByClass()` menerima filter status dan menerapkannya langsung pada query presensi.
- Frontend laporan: filter status mempertahankan tanggal dan kelas aktif ketika digunakan atau saat berpindah tanggal.
- Validasi: seluruh file PHP terkait lolos `php -l` dan tidak ada input gaji tersisa pada form admin tentor.

## 2026-10-03 — Normalisasi Filter Tipe dan Jenjang Presensi

- Status: **DONE**.
- Penyebab data Citra Lestari tidak muncul: URL memakai `Reguler` dan `SMP`, sedangkan database memakai `reguler` dan `SMP/MTs`.
- Backend: filter presensi sekarang menormalisasi label UI ke nilai database (`Reguler` → `reguler`, `SMP` → `SMP/MTs`, `SMA` → `SMA/MA`, serta Privat → `private`).
- Validasi: `PublicController.php` lolos `php -l`.

## 2026-10-03 — Perbaikan Lanjutan Error Filter Presensi

- Status: **DONE**.
- Pencarian `Bintang` masih menghasilkan `SQLSTATE[HY093]` karena placeholder filter `:tipe`, `:jenjang`, dan `:kelas` juga digunakan dua kali dalam query.
- Backend: seluruh placeholder kondisi filter dibuat unik (`*_empty` dan `*_value`) dengan nilai parameter yang sama.
- Validasi: query untuk URL `/cek-presensi?tipe=&jenjang=&q=Bintang&bulan=10&tahun=2026` tidak lagi memiliki named parameter berulang; file PHP lolos syntax check.

## 2026-10-03 — Perbaikan Error Pencarian Presensi Publik

- Status: **DONE**.
- Ditemukan error `SQLSTATE[HY093]` saat pencarian siswa karena placeholder `:q` digunakan berulang pada query PDO.
- Backend: placeholder pencarian dipisah menjadi `:q_nama`, `:q_sekolah`, dan `:q_nis` dengan parameter masing-masing.
- Validasi: `PublicController.php` lolos `php -l` dan query tidak lagi menggunakan nama placeholder berulang.

## 2026-10-03 — Revisi Beranda, Berita, Logo, dan Detail Profil Tentor

- Status: **DONE**.
- Membaca ulang draft `ui-draft/public/index-public.html` dan `ui-draft/public/index-menu-berita.html` sebelum implementasi.
- Frontend: beranda `/` diselaraskan dengan draft melalui hero berlogo, review pelajar, lokasi, dan kontak.
- Frontend: halaman `/berita` diselaraskan dengan draft melalui header, filter pill, kartu berita bergambar, dan responsivitas mobile.
- Asset: logo dari `ui-draft/logo.png` dipasang sebagai `public/assets/logo.png` dan digunakan pada header publik, beranda, serta login.
- Backend: ditambahkan route `GET /profil-tentor/{id}` dan method `PublicController::tentorDetail()`.
- Frontend: tombol **Lihat Profil** pada `/profil-tentor` kini mengarah ke halaman detail profil tentor dinamis.
- Frontend: halaman detail menampilkan foto/avatar, nama, universitas, bio, dan status tentor terverifikasi.
- Validasi: seluruh file PHP terkait lolos `php -l`; tidak ada perubahan skema database.

## 2026-10-03 — Penambahan Menu Laporan dan Profil Tentor

- Bottom navigation tentor diselaraskan menjadi empat menu utama: Beranda, Jadwal, Laporan, dan Profil; akses presensi tetap tersedia melalui dashboard dan halaman kelas.
- Menu **Laporan** mengikuti draft `ui-draft/tentor/index-laporan.html`, dengan filter bulan, ringkasan kehadiran/izin-sakit/durasi, progress kehadiran, dan riwayat sesi mengajar.
- Menu **Profil** mengikuti draft `ui-draft/tentor/index-profil.html`, dengan data nama, universitas, username, perubahan password, dan tampilan foto/inisial profil.
- Backend baru: `Tentor::getMonthlyTeachingReport()` untuk mengambil riwayat sesi serta rekap jumlah siswa hadir dan izin/sakit per bulan.
- Backend baru: `Tentor\PortalController` dengan endpoint `/tentor/laporan`, `/tentor/profil`, dan `POST /tentor/profil/update`.
- Update profil memvalidasi username unik, password minimal 6 karakter, konfirmasi password, menyimpan password menggunakan hash, dan memperbarui session nama tentor.
- `AuthController` sekarang mengisi `$_SESSION['user_nama']` saat login tentor agar header layout konsisten.
- File utama: `app/Controllers/Tentor/PortalController.php`, `app/Models/Tentor.php`, `app/Views/tentor/laporan.php`, `app/Views/tentor/profil.php`, `app/Views/tentor/layout.php`, `app/Controllers/AuthController.php`, dan `public/index.php`.

## 2026-10-03 — Perapihan Indentasi View Tentor

- Markup view jadwal, detail kelas, detail kehadiran, dan presensi dirapikan agar struktur HTML/PHP serta JavaScript lebih mudah dibaca dan dipelihara.
- Perapihan hanya menyentuh formatting/indentasi; route, field form, class Tailwind, dan perilaku interaktif tetap dipertahankan.

## 2026-10-03 — Revisi Responsivitas dan Fix Route Detail Jadwal

- Date strip pada `/tentor/jadwal` diubah dari horizontal overflow menjadi grid tujuh kolom agar lebih responsif di layar HP.
- Pemilih bulan diubah dari input month menjadi dropdown bulan.
- Error `Unknown named parameter $id` pada tombol detail kelas diperbaiki dengan menyamakan signature `detailKelas(string $id)` dengan route `/tentor/jadwal/kelas/{id}`.

## 2026-10-03 — Penyelarasan Jadwal Saya Tentor dengan Draft Jadwal

- Halaman `/tentor/jadwal` diubah menjadi **Jadwal Saya** dengan pemilih bulan dan date strip mingguan yang dapat digunakan untuk memfilter jadwal berdasarkan tanggal.
- Ditambahkan tampilan jadwal pada tanggal terpilih, indikator tanggal yang memiliki jadwal, informasi kelas/mapel/ruangan, dan tombol **Lihat Detail Kelas**.
- Ditambahkan halaman detail kelas `/tentor/jadwal/kelas/{id}` yang menampilkan riwayat pertemuan, tombol **Tambah Presensi Baru** ke `/tentor/presensi`, serta tombol **Lihat Detail** per pertemuan.
- Ditambahkan halaman detail kehadiran `/tentor/jadwal/pertemuan/{id}` yang menampilkan metadata pertemuan, data kehadiran/nilai siswa, dan tombol **Edit Data** ke `/tentor/presensi`.
- Backend menambahkan query filter tanggal, endpoint detail kelas, endpoint detail kehadiran, dan guard `tentor_id` agar tentor hanya dapat melihat kelas/pertemuannya sendiri.
- File utama: `app/Views/tentor/jadwal/index.php`, `app/Views/tentor/jadwal/detail-kelas.php`, `app/Views/tentor/jadwal/detail-kehadiran.php`, `app/Controllers/Tentor/AkademikController.php`, dan `public/index.php`.

## 2026-10-03 — Implementasi Alur Dashboard Presensi Tentor

- Dashboard tentor: tombol **Catat / Isi Presensi Sesi** sekarang menuju `/tentor/presensi` untuk memulai alur presensi dari pemilihan kelas.
- Halaman pemilihan presensi mengikuti draft: filter tipe kelas dan jenjang, daftar kelas aktif milik tentor, jumlah siswa aktif, serta riwayat presensi.
- Halaman isi kehadiran menampilkan metadata pertemuan, daftar siswa, dan tombol status `H/S/I/A/N`.
- Tombol `H` membuka panel penilaian siswa; nilai kemampuan dan nilai sikap disimpan sebagai bagian dari data presensi.
- Navigasi bawah tentor pada menu **Sesi & Presensi** diarahkan ke `/tentor/presensi`; route lama pertemuan tetap dipertahankan untuk kompatibilitas.
- File utama: `app/Views/tentor/beranda.php`, `app/Views/tentor/presensi/index.php`, `app/Views/tentor/presensi/form.php`, `app/Views/tentor/layout.php`, `app/Controllers/Tentor/AkademikController.php`, dan `public/index.php`.
- Verifikasi: view/controller terkait lolos `php -l` dan `git diff --check`; suite terintegrasi belum selesai karena koneksi MySQL lokal tidak tersedia.

## 2026-09-29 — Penyelarasan Tambah Jadwal dengan Form Kelas

- Frontend: /admin/jadwal/tambah sekarang memakai pola "Informasi Dasar Kelas" lalu "Atur Sesi & Tentor", dengan sesi yang dapat ditambah atau dihapus secara dinamis.
- Backend: alur simpan tambah jadwal menerima identitas kelas dan array sesi[], memvalidasi setiap hari/jam/tentor, lalu membuat kelas beserta seluruh sesinya dalam satu transaksi.
- Validasi: minimal satu sesi lengkap wajib pada alur tambah jadwal; alur tambah kelas dari menu Master Data tetap dapat menyimpan kelas tanpa sesi.
- File terkait: app/Controllers/Admin/JadwalController.php dan app/Views/admin/kelas/form.php.

## 2026-09-29 — Penyesuaian Direktori Data Siswa

- Index Data Siswa diubah menjadi direktori folder kelas dengan jumlah siswa aktif dan pencarian kelas.
- Ditambahkan detail kelas yang menampilkan seluruh siswa aktif; klik siswa membuka Edit Data Siswa dan tombol tambah membuka Register Siswa dengan kelas sudah dipilih.
- Backend menambahkan query jumlah siswa per kelas, daftar siswa per kelas, validasi penempatan kelas, dan sinkronisasi pendaftaran aktif ketika siswa dibuat atau dipindahkan.
- File terkait: app/Controllers/Admin/SiswaController.php, app/Models/Siswa.php, app/Models/Kelas.php, app/Models/PendaftaranSiswa.php, app/Views/admin/siswa/index.php, app/Views/admin/siswa/detail_kelas.php, app/Views/admin/siswa/form.php, dan public/index.php.

## 2026-09-29 — Penyesuaian Direktori Data Tentor

- Index Data Tentor diselaraskan dengan draft: pencarian, filter status Semua/Aktif/Nonaktif, daftar profil ringkas, dan tombol aksi Kelola.
- Tombol Kelola menuju form kelola profil tentor; tombol tambah menuju form registrasi tentor yang sudah tersedia.
- Tidak ada perubahan skema atau logika backend baru karena CRUD tentor dan upload foto sudah tersedia.
- File terkait: app/Views/admin/tentor/index.php.

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
## 2026-10-03 — Penyederhanaan Navigasi Admin dan Laporan

- Frontend: menu admin Pertemuan dihapus; menu Manajemen Kelas diganti menjadi Kelas dan diarahkan ke `/admin/kelas`.
- Frontend: pusat laporan kini hanya menyediakan Laporan Siswa dan Laporan Tentor, dengan tab filter Harian dan Bulanan.
- Backend: ditambahkan endpoint serta query laporan harian siswa/tentor; rekap tentor tidak lagi menampilkan honor/gaji.
- File terkait: `app/Views/admin/layout.php`, `app/Views/admin/laporan/*`, `app/Controllers/Admin/LaporanController.php`, `app/Models/Pertemuan.php`, `app/Models/Tentor.php`, dan `public/index.php`.
## 2026-10-03 — Penyatuan Filter Laporan Harian dan Bulanan

- Backend: ditambahkan endpoint terpadu `/admin/laporan/siswa` dan `/admin/laporan/tentor` dengan parameter `mode=harian|bulanan`.
- Frontend: tab Harian/Bulanan pada laporan siswa dan tentor sekarang berpindah dalam kategori yang sama, sesuai pola draft UI.
- Frontend: tampilan laporan siswa harian dan tentor harian disesuaikan dengan pola header, tab periode, dan mobile frame pada draft.
## 2026-10-03 — View Laporan Disatukan Per Kategori

- Frontend: laporan siswa sekarang memakai satu view `app/Views/admin/laporan/siswa.php` dengan tab Harian/Bulanan.
- Frontend: laporan tentor sekarang memakai satu view `app/Views/admin/laporan/tentor.php` dengan tab Harian/Bulanan.
- Backend: controller terpadu memilih data dan filter berdasarkan parameter `mode`, sehingga perpindahan tab tetap berada di halaman kategori yang sama.
## 2026-10-03 — Renaming Route Manajemen Kelas

- Route admin kelas diubah dari `/admin/kelas` menjadi `/admin/manajemen-kelas` beserta route tambah, edit, hapus, dan pengaturan jadwal.
- Seluruh link internal, redirect controller, dan navigasi admin diperbarui mengikuti route baru.
## 2026-10-03 — Menu Manajemen Kelas Mengarah ke Tampilan Jadwal Kelas

- Menu navigasi admin **Manajemen Kelas** sekarang membuka `/admin/jadwal?mode=kelas`, sama seperti tombol Manajemen Kelas pada Beranda Admin.
- Route CRUD master kelas tetap menggunakan `/admin/kelas`; perubahan nama menu tidak mengubah route master data tersebut.
## 2026-10-03 — Penambahan Dummy Data Laporan

- Seed menambahkan 4 tentor, 12 siswa, 4 kelas, 8 jadwal, dan 27 pertemuan dummy.
- Data pertemuan mencakup September dan Oktober 2026 untuk pengujian filter laporan bulanan serta tanggal harian.
- Presensi siswa dibuat otomatis untuk seluruh siswa aktif di kelas masing-masing dengan variasi hadir, sakit, izin, dan alfa.
- File terkait: `database/seed.sql`.
## 2026-10-03 — Pemerataan Dummy Siswa di Semua Kelas

- Menambahkan 24 siswa tambahan sehingga seluruh 8 kelas memiliki siswa aktif.
- Menambahkan pendaftaran siswa ke setiap kelas.
- Menambahkan jadwal dan pertemuan untuk kelas 7A, 8A, dan 12 IPA Private yang sebelumnya belum memiliki sesi.
- Menambahkan presensi otomatis untuk semua siswa pada seluruh pertemuan dummy.
## 2026-10-03 — Penyelarasan Laporan dengan Draft Terbaru

- Laporan siswa bulanan kini menampilkan daftar folder kelas terlebih dahulu; klik kelas membuka rekap siswa per kelas.
- Laporan siswa harian kini menampilkan kartu ringkasan dan daftar presensi dengan nilai sikap/akademik.
- Laporan tentor harian kini menampilkan log pengajar per tanggal beserta sesi, kelas, dan jam.
- Laporan tentor bulanan kini menampilkan akumulasi sesi dan total jam per tentor tanpa honor/gaji.
- Backend menambahkan hitungan jumlah pertemuan bulanan per kelas untuk kebutuhan kartu folder.
## 2026-10-03 — Penyelarasan Menu Publik dengan UI Draft

- Status: **DONE**.
- Membaca draft publik: beranda, berita, absensi, profil tentor, dan login sebelum implementasi.
- Frontend: navigasi publik diselaraskan menjadi Berita, Absensi Siswa, Profil Tentor, dan Login.
- Frontend: halaman berita mendapat filter tombol Semua, Bakti Sosial, dan Rekap Bulanan.
- Frontend: halaman absensi mendapat filter tombol tipe belajar dan jenjang, tetap mempertahankan pencarian siswa serta filter bulan/tahun.
- Frontend: halaman profil tentor baru dibuat dengan kartu tentor, foto/avatar, universitas, dan pencarian nama/universitas.
- Frontend: layout login diberi bottom navigation publik sesuai draft login.
- Backend: route baru `GET /profil-tentor` dan method `PublicController::tentorList()` ditambahkan untuk menyajikan data tentor publik.
- Backend: `PublicController::beritaList()` mendukung parameter filter berita; `cekPresensi()` mendukung parameter tipe, jenjang, dan kelas saat pencarian.
- Validasi: seluruh file PHP terkait lolos `php -l`; tidak ada perubahan skema database.
