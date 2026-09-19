-- ====================================================================
-- Database Specification — Sistem Informasi Bimbingan Belajar
-- Version: 1.0
-- Database: MySQL
-- Backend: PHP Native
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `berita`;
DROP TABLE IF EXISTS `pengumuman`;
DROP TABLE IF EXISTS `presensi`;
DROP TABLE IF EXISTS `pertemuan`;
DROP TABLE IF EXISTS `jadwal`;
DROP TABLE IF EXISTS `pendaftaran_siswa`;
DROP TABLE IF EXISTS `kelas`;
DROP TABLE IF EXISTS `paket`;
DROP TABLE IF EXISTS `program`;
DROP TABLE IF EXISTS `jenjang`;
DROP TABLE IF EXISTS `siswa_orang_tua`;
DROP TABLE IF EXISTS `siswa`;
DROP TABLE IF EXISTS `orang_tua`;
DROP TABLE IF EXISTS `tentor`;
DROP TABLE IF EXISTS `pengguna`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------------------
-- 1. Tabel pengguna
-- --------------------------------------------------------------------
CREATE TABLE `pengguna` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `peran` ENUM('owner', 'admin', 'tentor') NOT NULL,
  `status_aktif` BOOLEAN NOT NULL DEFAULT TRUE,
  `terakhir_login` DATETIME NULL,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pengguna_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 2. Tabel tentor
-- --------------------------------------------------------------------
CREATE TABLE `tentor` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `pengguna_id` BIGINT NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `asal_universitas` VARCHAR(150) NOT NULL,
  `nomor_telepon` VARCHAR(20) NULL,
  `bio` TEXT NULL,
  `foto` VARCHAR(255) NULL,
  `status_aktif` BOOLEAN NOT NULL DEFAULT TRUE,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tentor_pengguna` (`pengguna_id`),
  CONSTRAINT `fk_tentor_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. Tabel orang_tua
-- --------------------------------------------------------------------
CREATE TABLE `orang_tua` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `nomor_telepon` VARCHAR(20) NOT NULL,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4. Tabel siswa
-- --------------------------------------------------------------------
CREATE TABLE `siswa` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `asal_sekolah` VARCHAR(150) NOT NULL,
  `status_aktif` BOOLEAN NOT NULL DEFAULT TRUE,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 5. Tabel siswa_orang_tua
-- --------------------------------------------------------------------
CREATE TABLE `siswa_orang_tua` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `siswa_id` BIGINT NOT NULL,
  `orang_tua_id` BIGINT NOT NULL,
  `hubungan` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_siswa_orang_tua_siswa` (`siswa_id`),
  KEY `idx_siswa_orang_tua_orang_tua` (`orang_tua_id`),
  CONSTRAINT `fk_siswa_orangtua_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_siswa_orangtua_ortu` FOREIGN KEY (`orang_tua_id`) REFERENCES `orang_tua` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6. Tabel jenjang
-- --------------------------------------------------------------------
CREATE TABLE `jenjang` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 7. Tabel program
-- --------------------------------------------------------------------
CREATE TABLE `program` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(50) NOT NULL,
  `tipe` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 8. Tabel paket
-- --------------------------------------------------------------------
CREATE TABLE `paket` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 9. Tabel kelas
-- --------------------------------------------------------------------
CREATE TABLE `kelas` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `jenjang_id` BIGINT NOT NULL,
  `program_id` BIGINT NOT NULL,
  `nama` VARCHAR(20) NOT NULL,
  `status_aktif` BOOLEAN NOT NULL DEFAULT TRUE,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_kelas_jenjang` (`jenjang_id`),
  KEY `idx_kelas_program` (`program_id`),
  CONSTRAINT `fk_kelas_jenjang` FOREIGN KEY (`jenjang_id`) REFERENCES `jenjang` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_kelas_program` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 10. Tabel pendaftaran_siswa
-- --------------------------------------------------------------------
CREATE TABLE `pendaftaran_siswa` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `siswa_id` BIGINT NOT NULL,
  `kelas_id` BIGINT NOT NULL,
  `paket_id` BIGINT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NULL,
  `status` ENUM('aktif', 'selesai') NOT NULL DEFAULT 'aktif',
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pendaftaran_siswa` (`siswa_id`),
  KEY `idx_pendaftaran_kelas` (`kelas_id`),
  KEY `idx_pendaftaran_paket` (`paket_id`),
  CONSTRAINT `fk_pendaftaran_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pendaftaran_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pendaftaran_paket` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 11. Tabel jadwal
-- --------------------------------------------------------------------
CREATE TABLE `jadwal` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `kelas_id` BIGINT NOT NULL,
  `tentor_id` BIGINT NOT NULL,
  `hari` TINYINT NOT NULL COMMENT '1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu, 7=Minggu',
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  `ruangan` VARCHAR(50) NULL,
  `status_aktif` BOOLEAN NOT NULL DEFAULT TRUE,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_jadwal_kelas` (`kelas_id`),
  KEY `idx_jadwal_tentor` (`tentor_id`),
  CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_jadwal_tentor` FOREIGN KEY (`tentor_id`) REFERENCES `tentor` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 12. Tabel pertemuan
-- --------------------------------------------------------------------
CREATE TABLE `pertemuan` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `jadwal_id` BIGINT NOT NULL,
  `tentor_id` BIGINT NOT NULL,
  `nomor_pertemuan` INT NOT NULL,
  `tanggal` DATE NOT NULL,
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pertemuan_jadwal` (`jadwal_id`),
  KEY `idx_pertemuan_tentor` (`tentor_id`),
  CONSTRAINT `fk_pertemuan_jadwal` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pertemuan_tentor` FOREIGN KEY (`tentor_id`) REFERENCES `tentor` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 13. Tabel presensi
-- --------------------------------------------------------------------
CREATE TABLE `presensi` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `pertemuan_id` BIGINT NOT NULL,
  `siswa_id` BIGINT NOT NULL,
  `status_kehadiran` ENUM('hadir', 'sakit', 'izin', 'alfa', 'none') NOT NULL DEFAULT 'none',
  `nilai_sikap` ENUM('A', 'B', 'C', 'D') NULL,
  `nilai_akademik` ENUM('A', 'B', 'C', 'D') NULL,
  `catatan` TEXT NULL,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_presensi_pertemuan` (`pertemuan_id`),
  KEY `idx_presensi_siswa` (`siswa_id`),
  CONSTRAINT `fk_presensi_pertemuan` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_presensi_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 14. Tabel pengumuman
-- --------------------------------------------------------------------
CREATE TABLE `pengumuman` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `judul` VARCHAR(150) NOT NULL,
  `isi` TEXT NOT NULL,
  `dibuat_oleh` BIGINT NOT NULL,
  `target_peran` ENUM('semua', 'tentor', 'admin') NOT NULL DEFAULT 'semua',
  `diterbitkan_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_aktif` BOOLEAN NOT NULL DEFAULT TRUE,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pengumuman_dibuat_oleh` (`dibuat_oleh`),
  CONSTRAINT `fk_pengumuman_pengguna` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 15. Tabel berita
-- --------------------------------------------------------------------
CREATE TABLE `berita` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `judul` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `isi` TEXT NOT NULL,
  `gambar` VARCHAR(255) NULL,
  `dibuat_oleh` BIGINT NOT NULL,
  `diterbitkan_pada` DATETIME NULL,
  `status_terbit` BOOLEAN NOT NULL DEFAULT FALSE,
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_berita_slug` (`slug`),
  KEY `idx_berita_dibuat_oleh` (`dibuat_oleh`),
  CONSTRAINT `fk_berita_pengguna` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
