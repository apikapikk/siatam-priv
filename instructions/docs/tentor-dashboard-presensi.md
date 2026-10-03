# Dokumentasi Teknis Dashboard Presensi Tentor

> **Tanggal:** 2026-10-03  
> **Status:** Implemented  
> **Referensi UI:** `ui-draft/tentor/dashboard/`

## Tujuan

Alur baru presensi tentor dimulai dari dashboard dan mengikuti draft UI:

```text
Dashboard → Pilih Kelas Aktif → Isi Kehadiran Siswa
         → Klik H untuk membuka Penilaian Siswa → Simpan Presensi & Nilai
```

## Route

| Method | Route | Fungsi |
|---|---|---|
| GET | `/tentor/presensi` | Kelas aktif dan riwayat presensi |
| GET | `/tentor/presensi/isi?kelas_id={id}` | Draft pertemuan baru untuk kelas |
| GET | `/tentor/presensi/isi?pertemuan_id={id}` | Membuka ulang pertemuan |
| POST | `/tentor/presensi/simpan` | Menyimpan metadata, presensi, dan nilai |

Route lama `/tentor/pertemuan` tetap tersedia untuk kompatibilitas.

## Halaman Pilih Presensi

View: `app/Views/tentor/presensi/index.php`

- `$kelasList` berisi kelas dengan jadwal aktif milik tentor login.
- `$riwayatList` berisi lima pertemuan terakhir milik tentor.
- Filter tipe kelas dan jenjang diproses client-side dengan Vanilla JavaScript.
- Kartu kelas mengarah ke `/tentor/presensi/isi?kelas_id={kelas_id}`.

## Halaman Isi Kehadiran

View: `app/Views/tentor/presensi/form.php`

Field per siswa:

```text
presensi[{siswa_id}][status_kehadiran]
presensi[{siswa_id}][nilai_sikap]
presensi[{siswa_id}][nilai_akademik]
presensi[{siswa_id}][catatan]
```

| UI | Nilai `status_kehadiran` | Efek |
|---|---|---|
| H | `hadir` | Memilih hadir dan membuka panel penilaian |
| S | `sakit` | Memilih sakit |
| I | `izin` | Memilih izin |
| A | `alfa` | Memilih alfa |
| N | `none` | Belum diisi |

Panel penilaian bersifat modal/bottom sheet responsif. Nilai kemampuan dan sikap disalin ke hidden field siswa, lalu dikirim saat form utama disubmit.

## Penyimpanan

`AkademikController::simpanPresensi()` membuat atau memperbarui pertemuan, kemudian memanggil `Pertemuan::syncPresensi()` untuk insert/update status kehadiran, nilai, dan catatan dalam transaksi database.

## Verifikasi

- `php -l` berhasil untuk view dan controller terkait.
- `git diff --check` tidak menemukan whitespace error.
- `tests/run_all_tests.php` belum selesai karena koneksi MySQL lokal tidak tersedia saat verifikasi; bagian Router tetap sempat berjalan.
