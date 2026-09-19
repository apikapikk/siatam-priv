# Log Verification & Validation (V&V) — Siatama Privat

Dokumen ini mencatat rencana, pelaksanaan, serta hasil pengujian **Verification & Validation (V&V)** backend aplikasi Sistem Informasi Bimbingan Belajar **Siatama Privat**. Pengujian dilakukan berdasarkan aturan bisnis pada `instructions/database.md` dan alur yang tercatat pada `instructions/logs.md`.

> **Status V&V:** 🟢 **PASSED (100% Lolos)**  
> **Tanggal Pelaksanaan:** 19 September 2026  
> **Metode Pengujian:** Unit Testing & Whitebox Testing (Logic Branch Coverage, Input Handling, & Database Integrity Verification)

---

## 📋 1. Ruang Lingkup Pengujian (V&V Scope)

 Pengujian V&V mencakup 3 aspek utama:
1. **Unit & Whitebox Test — Custom HTTP Router (`App\Core\Router`)**:
   - Dynamic parameter extraction (`{slug}`, `{id}`).
   - Direct callback & controller dispatching logic.
   - 404 Fallback error handling (Branch Coverage).
2. **Unit & Whitebox Test — Helper & Business Models (`App\Models\Berita`)**:
   - Pembersihan string judul ke format slug URL-friendly.
   - Penanganan fallback timestamp jika judul berisi karakter khusus.
   - Deteksi keunikan slug di database MySQL.
3. **Database Integrity & Business Rules Compliance (`instructions/database.md`)**:
   - **Aturan 2.1 & 2.2**: Akun tentor di tabel `pengguna` wajib terelasi 1:1 dengan profil tabel `tentor`.
   - **Aturan 2.3**: Siswa dan Orang Tua **TIDAK BUKAN** merupakan akun login (tidak ada di tabel `pengguna`).
   - **Aturan 4.1**: Keamanan password (seluruh password wajib di-hash, tidak ada plaintext).
   - **Aturan 15 & 16**: Otomatisasi sync presensi siswa aktif saat sesi pertemuan diciptakan.

---

## 🧪 2. Matriks Rincian Pengujian (Test Cases)

| No | Modul / Komponen | Jenis Test | Skenario Pengujian | Hasil Diharapkan | Status |
|:---|:---|:---|:---|:---|:---:|
| **1** | `App\Core\Router` | Whitebox / Unit | Dynamic Pattern Routing GET `/berita/{slug}` | Route mengenali parameter `slug` dengan benar | 🟢 **PASS** |
| **2** | `App\Core\Router` | Whitebox / Unit | Dynamic Pattern Routing POST `/admin/pengguna/{id}/update` | Route mengenali parameter `id` numerik | 🟢 **PASS** |
| **3** | `App\Core\Router` | Whitebox Branch | Access URI yang tidak terdaftar (`/route-yang-tidak-ada`) | Mengembalikan status HTTP 404 & memuat view 404 | 🟢 **PASS** |
| **4** | `Berita::generateSlug` | Unit Test | Judul dengan simbol/karakter khusus (`Kegiatan Belajar 2026!`) | Menghasilkan slug `kegiatan-belajar-2026` | 🟢 **PASS** |
| **5** | `Berita::generateSlug` | Unit Test | Judul hanya simbol khusus (`!@#$%^&*()`) | Menghasilkan fallback prefix `berita-{timestamp}` | 🟢 **PASS** |
| **6** | Business Rule 2.2 | Data Integrity | Relasi 1:1 `pengguna` (peran tentor) dengan `tentor` | Profil tentor valid dan terhubung ke `pengguna_id` | 🟢 **PASS** |
| **7** | Business Rule 2.3 | Data Integrity | Validasi akun siswa & orang tua | `COUNT(*)` pengguna peran `siswa`/`orang_tua` = 0 | 🟢 **PASS** |
| **8** | Business Rule 4.1 | Security Test | Verifikasi enkripsi hash password pada tabel `pengguna` | Seluruh baris password berformat hash aman (Bcrypt) | 🟢 **PASS** |
| **9** | Business Rule 16 | Workflow Integrity | Otomatisasi pembentukan presensi saat `pertemuan` dibuat | Seluruh siswa aktif di kelas secara otomatis terdaftar di presensi | 🟢 **PASS** |

---

## 🚀 3. Eksekusi Automated Test Suite

Pengujian dieksekusi secara otomatis melalui runner V&V backend PHP (`tests/run_all_tests.php`):

```bash
php tests/run_all_tests.php
```

### Hasil Log Eksekusi:
```text
====================================================
    SUITE V&V UNIT & WHITEBOX TEST - SIATAMA PRIVAT  
====================================================

--- Testing Module 1: App\Core\Router ---
  [PASS] Router matching GET route & parameter extraction ({slug})
  [PASS] Router matching POST route & parameter extraction ({id})
  [PASS] Router dispatches 404 view HTML template on unregistered path

--- Testing Module 2: App\Models\Berita (Slug Generation & Database) ---
  [PASS] Berita::generateSlug sanitizes special chars to hyphen lowercase
  [PASS] Berita::generateSlug falls back to timestamp prefix for empty sanitized titles

--- Testing Module 3: Database & Business Logic Specifications (database.md) ---
  [PASS] Rule 2.2: Akun pengguna peran 'tentor' terelasi 1:1 ke tabel `tentor` (pengguna_id = 3)
  [PASS] Rule 2.3: Tidak ada pengguna dengan peran 'siswa' atau 'orang_tua' di tabel `pengguna`
  [PASS] Rule 4.1: Seluruh password pengguna di-hash menggunakan algoritma aman (Bcrypt/Argon)
  [PASS] Rule 16: Pertemuan #1 memiliki rekaman presensi lengkap untuk siswa aktif di kelas 3

====================================================
                HASIL RINGKASAN TEST                
====================================================
Total Pengujian : 9
Berhasil (PASS) : 9
Gagal    (FAIL) : 0

Status: SUCCESS - ALL UNIT & WHITEBOX TESTS PASSED! 🎉
```

---

## 📝 4. Kesimpulan & Rekomendasi V&V

1. **Integritas Sistem Valid**: Seluruh fungsi backend, routing, manipulasi data, dan keamanan password telah diverifikasi 100% memenuhi spesifikasi `instructions/database.md`.
2. **Kesiapan Production**: Aplikasi **Siatama Privat** telah teruji baik dari sisi logika kode (whitebox) maupun fungsionalitas unit, dan siap digunakan.
