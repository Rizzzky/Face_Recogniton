-- =============================================
-- Database Setup Script - Sistem Pengenalan Wajah
-- =============================================

-- Buat Database
CREATE DATABASE IF NOT EXISTS `db_penunggu_rs`;
USE `db_penunggu_rs`;

-- Buat Tabel penunggu_pasien
CREATE TABLE IF NOT EXISTS `penunggu_pasien` (
  `id_penunggu` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_penunggu` VARCHAR(100) NOT NULL,
  `nama_pasien` VARCHAR(100) NOT NULL,
  `nama_ruangan` VARCHAR(50) NOT NULL,
  `foto` VARCHAR(255) NOT NULL,
  `status` ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
  `tgl_input` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `tgl_update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_nama_penunggu` (`nama_penunggu`),
  INDEX `idx_status` (`status`),
  INDEX `idx_tgl_input` (`tgl_input`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Buat Tabel riwayat_penunggu
CREATE TABLE IF NOT EXISTS `riwayat_penunggu` (
  `id_riwayat` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_penunggu` VARCHAR(100) NOT NULL,
  `nama_pasien` VARCHAR(100) NOT NULL,
  `ruangan` VARCHAR(50) NOT NULL,
  `status` ENUM('MASUK', 'KELUAR') DEFAULT 'MASUK',
  `waktu_scan` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_nama_penunggu` (`nama_penunggu`),
  INDEX `idx_waktu_scan` (`waktu_scan`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Contoh Data (Ganti dengan data asli Anda)
-- =============================================

INSERT INTO `penunggu_pasien` 
(`nama_penunggu`, `nama_pasien`, `nama_ruangan`, `foto`, `status`) 
VALUES 
('Budi Santoso', 'Siti Aminah', 'Ruang 101', 'uploads/faces/budi.jpg', 'aktif'),
('Ahmad Wijaya', 'Hartono Sumirat', 'Ruang 102', 'uploads/faces/ahmad.jpg', 'aktif'),
('Siti Nurhaliza', 'Rina Suryanto', 'Ruang 103', 'uploads/faces/siti.jpg', 'aktif'),
('Rudi Hermawan', 'Bambang Irawan', 'Ruang 104', 'uploads/faces/rudi.jpg', 'aktif');

-- =============================================
-- Verifikasi
-- =============================================

SELECT COUNT(*) as total_penunggu FROM penunggu_pasien WHERE status = 'aktif';
SELECT COUNT(*) as total_riwayat FROM riwayat_penunggu;

-- =============================================
-- Selesai!
-- =============================================
-- Harapan output:
-- total_penunggu: 4
-- total_riwayat: 0 (awal kosong)
