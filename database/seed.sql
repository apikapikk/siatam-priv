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
INSERT INTO `tentor` (`id`, `pengguna_id`, `nama_lengkap`, `asal_universitas`, `nomor_telepon`, `bio`, `rate_gaji_per_jam`, `tarif_per_sesi`, `status_aktif`) VALUES
(1, 3, 'Budi Santoso, S.Pd.', 'Universitas Negeri Jakarta', '081234567890', 'Tentor pengajar Matematika & IPA dengan pengalaman > 5 tahun.', 75000, 0, 1),
(2, 4, 'Andi Wijaya, M.Si.', 'Universitas Indonesia', '081987654321', 'Tentor Spesialis Fisika & Kimia SMA.', 90000, 0, 1);

-- 3. Data Master Jenjang
INSERT INTO `jenjang` (`id`, `nama`) VALUES
(1, 'SD'),
(2, 'SMP/MTs'),
(3, 'SMA/MA');

-- 4. Data Master Program
INSERT INTO `program` (`id`, `nama`, `tipe`) VALUES
(1, 'Reguler', 'reguler'),
(2, 'Private', 'private'),
(3, 'Intensif', 'intensif');

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
INSERT INTO `siswa` (`id`, `nis`, `nama_lengkap`, `asal_sekolah`, `status_aktif`) VALUES
(1, 'SIS-0001', 'Citra Lestari', 'SMP Negeri 1 Jakarta', 1),
(2, 'SIS-0002', 'Deni Kurniawan', 'SMP Negeri 1 Jakarta', 1);

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
INSERT INTO `jadwal` (`id`, `kelas_id`, `tentor_id`, `mata_pelajaran`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `status_aktif`) VALUES
(1, 3, 1, 'Matematika', 1, '19:00:00', '20:30:00', 'Ruang A', 1), -- Kelas 9B, Tentor Budi, Senin
(2, 3, 2, 'Fisika', 3, '19:00:00', '20:30:00', 'Ruang B', 1); -- Kelas 9B, Tentor Andi, Rabu

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
-- 13b. Dummy data laporan tambahan (siswa, tentor, sesi, dan presensi)
-- Data ini sengaja mencakup September dan Oktober 2026 agar filter harian
-- maupun bulanan pada halaman laporan langsung menampilkan banyak data.
INSERT INTO `pengguna` (`id`, `username`, `password`, `peran`, `status_aktif`) VALUES
(5, 'tentor3', '$2y$12$zeUoXpLs2rComQAnsTsAbOy07hHB/VPNaOogTKFZpmVOxvaXG4SuC', 'tentor', 1),
(6, 'tentor4', '$2y$12$zeUoXpLs2rComQAnsTsAbOy07hHB/VPNaOogTKFZpmVOxvaXG4SuC', 'tentor', 1),
(7, 'tentor5', '$2y$12$zeUoXpLs2rComQAnsTsAbOy07hHB/VPNaOogTKFZpmVOxvaXG4SuC', 'tentor', 1),
(8, 'tentor6', '$2y$12$zeUoXpLs2rComQAnsTsAbOy07hHB/VPNaOogTKFZpmVOxvaXG4SuC', 'tentor', 1);

INSERT INTO `tentor` (`id`, `pengguna_id`, `nama_lengkap`, `asal_universitas`, `nomor_telepon`, `bio`, `rate_gaji_per_jam`, `tarif_per_sesi`, `status_aktif`) VALUES
(3, 5, 'Brina Maharani, S.Pd.', 'Universitas Pendidikan Indonesia', '081200000003', 'Tentor Matematika dan Bahasa Inggris.', 0, 0, 1),
(4, 6, 'Zaini Ramadhan, S.Si.', 'Institut Teknologi Bandung', '081200000004', 'Tentor IPA dan Fisika.', 0, 0, 1),
(5, 7, 'Meylina Putri, S.Pd.', 'Universitas Negeri Yogyakarta', '081200000005', 'Tentor Bahasa Indonesia dan Tematik.', 0, 0, 1),
(6, 8, 'Wahyu Ramadhan, M.Pd.', 'Universitas Gadjah Mada', '081200000006', 'Tentor persiapan ujian SMA.', 0, 0, 1);

INSERT INTO `kelas` (`id`, `jenjang_id`, `program_id`, `nama`, `status_aktif`) VALUES
(5, 1, 1, '6A', 1), (6, 2, 1, '7B', 1), (7, 2, 2, '8B Privat', 1), (8, 3, 3, '11 IPA', 1);

INSERT INTO `siswa` (`id`, `nis`, `nama_lengkap`, `asal_sekolah`, `status_aktif`) VALUES
(3, 'SIS-0003', 'Aqilah Putri', 'SD Negeri  Menteng 01', 1),
(4, 'SIS-0004', 'Raka Pratama', 'SD Negeri Menteng 01', 1),
(5, 'SIS-0005', 'Nadia Safitri', 'SD Negeri Menteng 01', 1),
(6, 'SIS-0006', 'Fajar Hidayat', 'SMP Negeri 5 Jakarta', 1),
(7, 'SIS-0007', 'Salma Nabila', 'SMP Negeri 5 Jakarta', 1),
(8, 'SIS-0008', 'Rizky Maulana', 'SMP Negeri 5 Jakarta', 1),
(9, 'SIS-0009', 'Kevin Alvaro', 'SMP Negeri 8 Jakarta', 1),
(10, 'SIS-0010', 'Naurah Azzahra', 'SMP Negeri 8 Jakarta', 1),
(11, 'SIS-0011', 'Daffa Akbar', 'SMP Negeri 8 Jakarta', 1),
(12, 'SIS-0012', 'Bintang Prakoso', 'SMA Negeri 3 Jakarta', 1),
(13, 'SIS-0013', 'Celine Aurelia', 'SMA Negeri 3 Jakarta', 1),
(14, 'SIS-0014', 'Rafi Alamsyah', 'SMA Negeri 3 Jakarta', 1);

INSERT INTO `pendaftaran_siswa` (`id`, `siswa_id`, `kelas_id`, `paket_id`, `tanggal_mulai`, `status`) VALUES
(3, 3, 5, 1, '2026-01-10', 'aktif'), (4, 4, 5, 1, '2026-01-10', 'aktif'), (5, 5, 5, 1, '2026-01-10', 'aktif'),
(6, 6, 6, 2, '2026-01-10', 'aktif'), (7, 7, 6, 2, '2026-01-10', 'aktif'), (8, 8, 6, 2, '2026-01-10', 'aktif'),
(9, 9, 7, 2, '2026-01-10', 'aktif'), (10, 10, 7, 2, '2026-01-10', 'aktif'), (11, 11, 7, 2, '2026-01-10', 'aktif'),
(12, 12, 8, 3, '2026-01-10', 'aktif'), (13, 13, 8, 3, '2026-01-10', 'aktif'), (14, 14, 8, 3, '2026-01-10', 'aktif');

INSERT INTO `jadwal` (`id`, `kelas_id`, `tentor_id`, `mata_pelajaran`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `status_aktif`) VALUES
(3, 5, 3, 'Matematika', 2, '16:00:00', '17:30:00', 'Ruang C', 1),
(4, 5, 5, 'Bahasa Inggris', 4, '16:00:00', '17:30:00', 'Ruang C', 1),
(5, 6, 4, 'IPA Terpadu', 1, '17:00:00', '18:30:00', 'Ruang D', 1),
(6, 6, 3, 'Matematika', 5, '17:00:00', '18:30:00', 'Ruang D', 1),
(7, 7, 5, 'Bahasa Indonesia', 3, '18:00:00', '19:30:00', 'Ruang E', 1),
(8, 7, 4, 'Fisika Dasar', 6, '10:00:00', '11:30:00', 'Ruang E', 1),
(9, 8, 6, 'Fisika', 2, '19:00:00', '20:30:00', 'Lab 1', 1),
(10, 8, 6, 'Kimia', 5, '19:00:00', '20:30:00', 'Lab 1', 1);

INSERT INTO `pertemuan` (`id`, `jadwal_id`, `tentor_id`, `nomor_pertemuan`, `tanggal`, `jam_mulai`, `jam_selesai`) VALUES
(3, 3, 3, 1, '2026-09-01', '16:00:00', '17:30:00'), (4, 3, 3, 2, '2026-09-08', '16:00:00', '17:30:00'), (5, 3, 3, 3, '2026-09-15', '16:00:00', '17:30:00'), (6, 3, 3, 4, '2026-10-03', '16:00:00', '17:30:00'),
(7, 4, 5, 1, '2026-09-03', '16:00:00', '17:30:00'), (8, 4, 5, 2, '2026-09-10', '16:00:00', '17:30:00'), (9, 4, 5, 3, '2026-09-17', '16:00:00', '17:30:00'),
(10, 5, 4, 1, '2026-09-07', '17:00:00', '18:30:00'), (11, 5, 4, 2, '2026-09-14', '17:00:00', '18:30:00'), (12, 5, 4, 3, '2026-09-21', '17:00:00', '18:30:00'), (13, 5, 4, 4, '2026-10-05', '17:00:00', '18:30:00'),
(14, 6, 3, 1, '2026-09-04', '17:00:00', '18:30:00'), (15, 6, 3, 2, '2026-09-11', '17:00:00', '18:30:00'), (16, 6, 3, 3, '2026-09-18', '17:00:00', '18:30:00'),
(17, 7, 5, 1, '2026-09-02', '18:00:00', '19:30:00'), (18, 7, 5, 2, '2026-09-09', '18:00:00', '19:30:00'), (19, 7, 5, 3, '2026-09-16', '18:00:00', '19:30:00'), (20, 7, 5, 4, '2026-10-07', '18:00:00', '19:30:00'),
(21, 8, 4, 1, '2026-09-05', '10:00:00', '11:30:00'), (22, 8, 4, 2, '2026-09-12', '10:00:00', '11:30:00'), (23, 8, 4, 3, '2026-09-19', '10:00:00', '11:30:00'),
(24, 9, 6, 1, '2026-09-08', '19:00:00', '20:30:00'), (25, 9, 6, 2, '2026-09-15', '19:00:00', '20:30:00'), (26, 9, 6, 3, '2026-10-06', '19:00:00', '20:30:00'),
(27, 10, 6, 1, '2026-09-11', '19:00:00', '20:30:00'), (28, 10, 6, 2, '2026-09-18', '19:00:00', '20:30:00'), (29, 10, 6, 3, '2026-10-09', '19:00:00', '20:30:00');

-- Setiap sesi dummy otomatis memiliki presensi seluruh siswa di kelasnya.
INSERT INTO `presensi` (`pertemuan_id`, `siswa_id`, `status_kehadiran`, `nilai_sikap`, `nilai_akademik`, `catatan`)
SELECT p.id, ps.siswa_id,
       CASE MOD(p.id + ps.siswa_id, 9) WHEN 0 THEN 'sakit' WHEN 1 THEN 'izin' WHEN 2 THEN 'alfa' ELSE 'hadir' END,
       CASE MOD(p.id + ps.siswa_id, 5) WHEN 0 THEN 'B' WHEN 1 THEN 'A' ELSE 'A' END,
       CASE MOD(p.id + ps.siswa_id, 6) WHEN 0 THEN 'B' WHEN 1 THEN 'A' ELSE 'A' END,
       'Data dummy untuk pengujian laporan.'
FROM `pertemuan` p
JOIN `jadwal` j ON j.id = p.jadwal_id
JOIN `pendaftaran_siswa` ps ON ps.kelas_id = j.kelas_id AND ps.status = 'aktif'
WHERE p.id BETWEEN 3 AND 29;

-- 13c. Tambahan siswa agar seluruh kelas memiliki peserta laporan.
INSERT INTO `siswa` (`id`, `nis`, `nama_lengkap`, `asal_sekolah`, `status_aktif`) VALUES
(15, 'SIS-0015', 'Aditya Nugraha', 'SMP Negeri 12 Jakarta', 1),
(16, 'SIS-0016', 'Bella Kirana', 'SMP Negeri 12 Jakarta', 1),
(17, 'SIS-0017', 'Cahya Ramadhan', 'SMP Negeri 12 Jakarta', 1),
(18, 'SIS-0018', 'Dinda Maharani', 'SMP Negeri 15 Jakarta', 1),
(19, 'SIS-0019', 'Eka Saputra', 'SMP Negeri 15 Jakarta', 1),
(20, 'SIS-0020', 'Fina Aulia', 'SMP Negeri 15 Jakarta', 1),
(21, 'SIS-0021', 'Gilang Prakoso', 'SMP Negeri 9 Jakarta', 1),
(22, 'SIS-0022', 'Hana Lestari', 'SMP Negeri 9 Jakarta', 1),
(23, 'SIS-0023', 'Iqbal Maulana', 'SMP Negeri 9 Jakarta', 1),
(24, 'SIS-0024', 'Jihan Putri', 'SMA Negeri 6 Jakarta', 1),
(25, 'SIS-0025', 'Kezia Anindita', 'SMA Negeri 6 Jakarta', 1),
(26, 'SIS-0026', 'Lukman Hakim', 'SMA Negeri 6 Jakarta', 1),
(27, 'SIS-0027', 'Mira Amelia', 'SD Negeri 02 Jakarta', 1),
(28, 'SIS-0028', 'Niko Firmansyah', 'SD Negeri 02 Jakarta', 1),
(29, 'SIS-0029', 'Olivia Salsabila', 'SD Negeri 02 Jakarta', 1),
(30, 'SIS-0030', 'Putra Wijaya', 'SMP Negeri 5 Jakarta', 1),
(31, 'SIS-0031', 'Qori Azzahra', 'SMP Negeri 5 Jakarta', 1),
(32, 'SIS-0032', 'Rendra Kurnia', 'SMP Negeri 5 Jakarta', 1),
(33, 'SIS-0033', 'Salsa Nirmala', 'SMP Negeri 8 Jakarta', 1),
(34, 'SIS-0034', 'Tio Adinata', 'SMP Negeri 8 Jakarta', 1),
(35, 'SIS-0035', 'Una Safira', 'SMP Negeri 8 Jakarta', 1),
(36, 'SIS-0036', 'Vino Mahendra', 'SMA Negeri 3 Jakarta', 1),
(37, 'SIS-0037', 'Wulan Pertiwi', 'SMA Negeri 3 Jakarta', 1),
(38, 'SIS-0038', 'Yusuf Akbar', 'SMA Negeri 3 Jakarta', 1);

INSERT INTO `pendaftaran_siswa` (`id`, `siswa_id`, `kelas_id`, `paket_id`, `tanggal_mulai`, `status`) VALUES
(15, 15, 1, 1, '2026-01-10', 'aktif'), (16, 16, 1, 1, '2026-01-10', 'aktif'), (17, 17, 1, 1, '2026-01-10', 'aktif'),
(18, 18, 2, 1, '2026-01-10', 'aktif'), (19, 19, 2, 1, '2026-01-10', 'aktif'), (20, 20, 2, 1, '2026-01-10', 'aktif'),
(21, 21, 3, 2, '2026-01-10', 'aktif'), (22, 22, 3, 2, '2026-01-10', 'aktif'), (23, 23, 3, 2, '2026-01-10', 'aktif'),
(24, 24, 4, 2, '2026-01-10', 'aktif'), (25, 25, 4, 2, '2026-01-10', 'aktif'), (26, 26, 4, 2, '2026-01-10', 'aktif'),
(27, 27, 5, 1, '2026-01-10', 'aktif'), (28, 28, 5, 1, '2026-01-10', 'aktif'), (29, 29, 5, 1, '2026-01-10', 'aktif'),
(30, 30, 6, 2, '2026-01-10', 'aktif'), (31, 31, 6, 2, '2026-01-10', 'aktif'), (32, 32, 6, 2, '2026-01-10', 'aktif'),
(33, 33, 7, 2, '2026-01-10', 'aktif'), (34, 34, 7, 2, '2026-01-10', 'aktif'), (35, 35, 7, 2, '2026-01-10', 'aktif'),
(36, 36, 8, 3, '2026-01-10', 'aktif'), (37, 37, 8, 3, '2026-01-10', 'aktif'), (38, 38, 8, 3, '2026-01-10', 'aktif');

-- Jadwal dan pertemuan tambahan untuk kelas 7A, 8A, dan 12 IPA Private.
INSERT INTO `jadwal` (`id`, `kelas_id`, `tentor_id`, `mata_pelajaran`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `status_aktif`) VALUES
(11, 1, 1, 'Matematika', 2, '15:00:00', '16:30:00', 'Ruang A', 1),
(12, 2, 2, 'IPA', 3, '16:00:00', '17:30:00', 'Ruang B', 1),
(13, 4, 6, 'Fisika', 4, '19:00:00', '20:30:00', 'Lab 2', 1),
(14, 4, 6, 'Kimia', 6, '13:00:00', '14:30:00', 'Lab 2', 1);

INSERT INTO `pertemuan` (`id`, `jadwal_id`, `tentor_id`, `nomor_pertemuan`, `tanggal`, `jam_mulai`, `jam_selesai`) VALUES
(30, 11, 1, 1, '2026-09-01', '15:00:00', '16:30:00'), (31, 11, 1, 2, '2026-09-08', '15:00:00', '16:30:00'), (32, 11, 1, 3, '2026-09-15', '15:00:00', '16:30:00'),
(33, 12, 2, 1, '2026-09-02', '16:00:00', '17:30:00'), (34, 12, 2, 2, '2026-09-09', '16:00:00', '17:30:00'), (35, 12, 2, 3, '2026-09-16', '16:00:00', '17:30:00'),
(36, 13, 6, 1, '2026-09-03', '19:00:00', '20:30:00'), (37, 13, 6, 2, '2026-09-10', '19:00:00', '20:30:00'), (38, 13, 6, 3, '2026-09-17', '19:00:00', '20:30:00'),
(39, 14, 6, 1, '2026-09-05', '13:00:00', '14:30:00'), (40, 14, 6, 2, '2026-09-12', '13:00:00', '14:30:00'), (41, 14, 6, 3, '2026-09-19', '13:00:00', '14:30:00');

INSERT INTO `presensi` (`pertemuan_id`, `siswa_id`, `status_kehadiran`, `nilai_sikap`, `nilai_akademik`, `catatan`)
SELECT p.id, ps.siswa_id,
       CASE MOD(p.id + ps.siswa_id, 9) WHEN 0 THEN 'sakit' WHEN 1 THEN 'izin' WHEN 2 THEN 'alfa' ELSE 'hadir' END,
       'A', 'A', 'Data dummy tambahan untuk pengujian laporan.'
FROM `pertemuan` p
JOIN `jadwal` j ON j.id = p.jadwal_id
JOIN `pendaftaran_siswa` ps ON ps.kelas_id = j.kelas_id AND ps.status = 'aktif'
WHERE p.id BETWEEN 1 AND 41
  AND NOT EXISTS (SELECT 1 FROM `presensi` x WHERE x.pertemuan_id = p.id AND x.siswa_id = ps.siswa_id);

-- 14. Pengumuman Internal
INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `dibuat_oleh`, `target_peran`, `tipe_broadcast`, `kategori`, `status_aktif`) VALUES
(1, 'Batas Pengisian Presensi', 'Dimohon kepada seluruh tentor untuk menginputkan presensi & nilai paling lambat 24 jam setelah sesi berakhir.', 1, 'tentor', 'banner', 'Akademik', 1);

-- 15. Berita Publik Landing Page
INSERT INTO `berita` (`id`, `judul`, `slug`, `isi`, `dibuat_oleh`, `diterbitkan_pada`, `status_terbit`) VALUES
(1, 'Pembukaan Pendaftaran Siswa Baru TA 2026/2027', 'pembukaan-pendaftaran-siswa-baru-ta-2026-2027', 'Bimbingan Belajar kami kembali membuka pendaftaran untuk jenjang SD, SMP, dan SMA. Dapatkan diskon khusus pendaftaran awal.', 2, CURRENT_TIMESTAMP, 1);
