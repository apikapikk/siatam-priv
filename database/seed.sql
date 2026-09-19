-- ====================================================================
-- Seed Data Initial — Sistem Informasi Bimbingan Belajar
-- ====================================================================

-- 1. Data Pengguna (Owner, Admin, Tentor)
-- Password defaults:
-- owner   : owner123
-- admin   : admin123
-- tentor1 : tentor123
-- tentor2 : tentor123
INSERT INTO `pengguna` (`id`, `username`, `password`, `peran`, `status_aktif`) VALUES
(1, 'owner', '$2y$12$dyk9UUwJX/aYglwksKwvnu75qSJkYwalobgdzj4PycYjzWtLhQa8u', 'owner', 1),
(2, 'admin', '$2y$12$1hYMbFiU8tqoOcaHA6ChlOxEs8ZA5Ewkqso07E.myePphhuTyhDdO', 'admin', 1),
(3, 'tentor1', '$2y$12$zeUoXpLs2rComQAnsTsAbOy07hHB/VPNaOogTKFZpmVOxvaXG4SuC', 'tentor', 1),
(4, 'tentor2', '$2y$12$zeUoXpLs2rComQAnsTsAbOy07hHB/VPNaOogTKFZpmVOxvaXG4SuC', 'tentor', 1);

-- 2. Data Tentor (Profil)
INSERT INTO `tentor` (`id`, `pengguna_id`, `nama_lengkap`, `asal_universitas`, `nomor_telepon`, `bio`, `status_aktif`) VALUES
(1, 3, 'Budi Santoso, S.Pd.', 'Universitas Negeri Jakarta', '081234567890', 'Tentor pengajar Matematika & IPA dengan pengalaman > 5 tahun.', 1),
(2, 4, 'Andi Wijaya, M.Si.', 'Universitas Indonesia', '081987654321', 'Tentor Spesialis Fisika & Kimia SMA.', 1);

-- 3. Data Master Jenjang
INSERT INTO `jenjang` (`id`, `nama`) VALUES
(1, 'SD'),
(2, 'SMP/MTs'),
(3, 'SMA/MA');

-- 4. Data Master Program
INSERT INTO `program` (`id`, `nama`, `tipe`) VALUES
(1, 'Reguler', 'reguler'),
(2, 'Private', 'private');

-- 5. Data Master Paket
INSERT INTO `paket` (`id`, `nama`) VALUES
(1, 'Paket A'),
(2, 'Paket B'),
(3, 'Paket C');

-- 6. Data Kelas
INSERT INTO `kelas` (`id`, `jenjang_id`, `program_id`, `nama`, `status_aktif`) VALUES
(1, 2, 1, '7A', 1),
(2, 2, 1, '8A', 1),
(3, 2, 1, '9B', 1),
(4, 3, 2, '12 IPA Private', 1);

-- 7. Data Orang Tua
INSERT INTO `orang_tua` (`id`, `nama_lengkap`, `nomor_telepon`) VALUES
(1, 'Rudi Pratama (Ayah)', '082111222333'),
(2, 'Siti Rahma (Ibu)', '082111222334');

-- 8. Data Siswa
INSERT INTO `siswa` (`id`, `nama_lengkap`, `asal_sekolah`, `status_aktif`) VALUES
(1, 'Citra Lestari', 'SMP Negeri 1 Jakarta', 1),
(2, 'Deni Kurniawan', 'SMP Negeri 1 Jakarta', 1);

-- 9. Relasi Siswa - Orang Tua
INSERT INTO `siswa_orang_tua` (`id`, `siswa_id`, `orang_tua_id`, `hubungan`) VALUES
(1, 1, 1, 'Ayah'),
(2, 1, 2, 'Ibu'),
(3, 2, 1, 'Ayah');

-- 10. Pendaftaran Siswa (Histori/Penempatan)
INSERT INTO `pendaftaran_siswa` (`id`, `siswa_id`, `kelas_id`, `paket_id`, `tanggal_mulai`, `status`) VALUES
(1, 1, 3, 2, '2026-01-10', 'aktif'),
(2, 2, 3, 2, '2026-01-10', 'aktif');

-- 11. Jadwal Mengajar
INSERT INTO `jadwal` (`id`, `kelas_id`, `tentor_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `status_aktif`) VALUES
(1, 3, 1, 1, '19:00:00', '20:30:00', 'Ruang A', 1), -- Kelas 9B, Tentor Budi, Senin
(2, 3, 2, 3, '19:00:00', '20:30:00', 'Ruang B', 1); -- Kelas 9B, Tentor Andi, Rabu

-- 12. Pertemuan Mengajar Realisasi
INSERT INTO `pertemuan` (`id`, `jadwal_id`, `tentor_id`, `nomor_pertemuan`, `tanggal`, `jam_mulai`, `jam_selesai`) VALUES
(1, 1, 1, 1, '2026-09-07', '19:00:00', '20:30:00'),
(2, 1, 2, 2, '2026-09-14', '19:00:00', '20:30:00'); -- Budi izin, Andi menggantikan

-- 13. Presensi & Nilai Siswa
INSERT INTO `presensi` (`id`, `pertemuan_id`, `siswa_id`, `status_kehadiran`, `nilai_sikap`, `nilai_akademik`, `catatan`) VALUES
(1, 1, 1, 'hadir', 'A', 'A', 'Sangat aktif bertanya.'),
(2, 1, 2, 'hadir', 'B', 'B', 'Memahami materi dengan baik.'),
(3, 2, 1, 'hadir', 'A', 'A', 'Tugas latihan selesai tepat waktu.'),
(4, 2, 2, 'izin', NULL, NULL, 'Izin sakit dengan surat dokter.');

-- 14. Pengumuman Internal
INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `dibuat_oleh`, `target_peran`, `status_aktif`) VALUES
(1, 'Batas Pengisian Presensi', 'Dimohon kepada seluruh tentor untuk menginputkan presensi & nilai paling lambat 24 jam setelah sesi berakhir.', 1, 'tentor', 1);

-- 15. Berita Publik Landing Page
INSERT INTO `berita` (`id`, `judul`, `slug`, `isi`, `dibuat_oleh`, `diterbitkan_pada`, `status_terbit`) VALUES
(1, 'Pembukaan Pendaftaran Siswa Baru TA 2026/2027', 'pembukaan-pendaftaran-siswa-baru-ta-2026-2027', 'Bimbingan Belajar kami kembali membuka pendaftaran untuk jenjang SD, SMP, dan SMA. Dapatkan diskon khusus pendaftaran awal.', 2, CURRENT_TIMESTAMP, 1);
