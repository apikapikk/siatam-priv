# Laporan Ringkasan Pengujian Unit Test & Whitebox Test — Siatama Privat

Dokumen ini berisi ringkasan teknis hasil pengujian **Unit Test** dan **Whitebox Test** pada backend aplikasi **Sistem Informasi Bimbingan Belajar Siatama Privat**.

---

## 📌 Ringkasan Status Pengujian

> **Status Akhir:** 🟢 **100% PASSED (9/9 Test Passed)**  
> **Tanggal Pengujian:** 19 September 2026  
> **Lingkungan Pengujian:** PHP 8.5.9 (CLI) + MySQL / MariaDB (`bimbel_db`)  
> **File Test Runner:** `tests/run_all_tests.php`

---

## 🧪 1. Pengujian Unit Test (Unit Testing)

Unit Testing berfokus pada pengujian unit-unit fungsi dan metode terkecil secara terisolasi tanpa bergantung pada antarmuka pengguna (UI).

### Target Modul & Hasil Pengujian:

#### A. HTTP Router Unit (`App\Core\Router`)
- **Fungsi `get()` & `dispatch()` (Dynamic URI Parameter)**
  - **Skenario:** Menguji ekstraksi variabel dinamis dari URI seperti `/berita/{slug}` dan `/admin/pengguna/{id}/update`.
  - **Input:** Request `GET /berita/kegiatan-bimbel-2026` dan `POST /admin/pengguna/42/update`.
  - **Hasil:** Parameter `slug = 'kegiatan-bimbel-2026'` dan `id = '42'` berhasil diekstrak dan diteruskan ke *handler* dengan tepat.
  - **Status:** 🟢 **PASS**

#### B. Helper Model Berita (`App\Models\Berita`)
- **Fungsi `generateSlug()` (Sanitasi String & Fallback)**
  - **Skenario 1:** Mengubah judul dengan karakter khusus & kapitalisasi (`Kegiatan Belajar Bimbel 2026!`).
  - **Hasil 1:** Menghasilkan slug bersih `kegiatan-belajar-bimbel-2026`.
  - **Status 1:** 🟢 **PASS**
  - **Skenario 2:** Input judul yang hanya berisi simbol tanpa huruf/angka (`!@#$%^&*()`).
  - **Hasil 2:** Otomatis menghasilkan fallback slug dengan prefix timestamp (`berita-{timestamp}`).
  - **Status 2:** 🟢 **PASS**

---

## 🔍 2. Pengujian Whitebox Test (Whitebox / Logic & Branch Coverage)

Whitebox Testing berfokus pada pengujian struktur internal kode, alur keputusan (*branch coverage*), keamanan data, serta kepatuhan aturan bisnis (*business logic*) sesuai spesifikasi `instructions/database.md`.

### Target Alur Kode & Hasil Pengujian:

#### A. Branch Coverage 404 Fallback (`App\Core\Router`)
- **Alur Kode:** Pengujian cabang kondisi `foreach ($this->routes)` ketika tidak ada *route* yang cocok dengan `REQUEST_URI`.
- **Input:** `GET /route-yang-tidak-ada`.
- **Eksekusi Alur:** Router melewati seluruh koleksi route -> memicu `http_response_code(404)` -> memuat file tampilan `app/Views/404.php`.
- **Hasil:** Response HTTP 404 & HTML 404 rendered.
- **Status:** 🟢 **PASS**

#### B. Relasi Data & Profil Tentor 1:1 (Spesifikasi DB Aturan 2.1 & 2.2)
- **Alur Kode:** Memverifikasi bahwa akun pada tabel `pengguna` yang berperan `tentor` memiliki data profil 1:1 pada tabel `tentor` via FK `pengguna_id`.
- **Hasil Query:** Setiap pengguna `peran = 'tentor'` terhubung secara presisi ke 1 entri profil di tabel `tentor`.
- **Status:** 🟢 **PASS**

#### C. Pembatasan Login Entitas Siswa & Orang Tua (Spesifikasi DB Aturan 2.3)
- **Alur Kode:** Memverifikasi aturan bisnis bahwa Siswa & Orang Tua **TIDAK MEMILIKI AKUN LOGIN**.
- **Hasil Query:** `SELECT COUNT(*) FROM pengguna WHERE peran IN ('siswa', 'orang_tua')` mengembalikan nilai **0**.
- **Status:** 🟢 **PASS**

#### D. Keamanan Enkripsi Password (Spesifikasi DB Aturan 4.1)
- **Alur Kode:** Memverifikasi bahwa tidak ada password dalam bentuk *plaintext* di tabel `pengguna`.
- **Hasil Inspeksi:** Seluruh password terenkripsi menggunakan algoritma `password_hash()` (Bcrypt) dan lulus verifikasi `password_get_info()`.
- **Status:** 🟢 **PASS**

#### E. Automatic Presensi Sync Workflow (Spesifikasi DB Aturan 15 & 16)
- **Alur Kode:** Memverifikasi alur otomatisasi `PertemuanController::populateInitialPresensi()` saat sesi pertemuan baru dibuat.
- **Hasil:** Jumlah entri di tabel `presensi` untuk suatu `pertemuan_id` secara otomatis sama atau lebih besar dari jumlah siswa aktif terdaftar pada kelas tersebut.
- **Status:** 🟢 **PASS**

---

## 📊 Matriks Hasil Pengujian (Summary Table)

| No | Kategori | Komponen / Modul | Skenario Test | Hasil | Status |
|:---:|:---|:---|:---|:---|:---:|
| 1 | **Unit Test** | `App\Core\Router` | Dynamic Parameter Matching `{slug}` | Slug diekstrak akurat | 🟢 PASS |
| 2 | **Unit Test** | `App\Core\Router` | Dynamic Parameter Matching `{id}` | ID diekstrak akurat | 🟢 PASS |
| 3 | **Unit Test** | `App\Models\Berita` | Sanitasi judul ke URL Slug | Output slug huruf kecil & tanda hubung | 🟢 PASS |
| 4 | **Unit Test** | `App\Models\Berita` | Fallback slug untuk judul simbolik | Prefix `berita-{timestamp}` terbentuk | 🟢 PASS |
| 5 | **Whitebox** | `App\Core\Router` | Branch Coverage 404 Route Unmatched | Trigger status code 404 & view 404 | 🟢 PASS |
| 6 | **Whitebox** | Database Relasi | Rule 2.2 Relasi 1:1 `pengguna` -> `tentor` | Terverifikasi via FK `pengguna_id` | 🟢 PASS |
| 7 | **Whitebox** | Logic Access | Rule 2.3 Non-login untuk Siswa & Wali | Akun login siswa & orang tua = 0 | 🟢 PASS |
| 8 | **Whitebox** | Security Audit | Rule 4.1 Enkripsi Hash Password | 100% password ter-hash Bcrypt | 🟢 PASS |
| 9 | **Whitebox** | Data Workflow | Rule 16 Auto-sync Presensi Pertemuan | Sesi pertemuan otomatis memiliki data presensi siswa | 🟢 PASS |

---

## 💻 Cara Menjalankan Ulang Test Suite

Untuk menjalankan kembali seluruh pengujian Unit & Whitebox Test secara otomatis:

```bash
php tests/run_all_tests.php
```
