-- =============================================
-- Supabase (PostgreSQL) Setup Script
-- =============================================

-- Buat Tabel penunggu_pasien
CREATE TABLE IF NOT EXISTS penunggu_pasien (
  id_penunggu SERIAL PRIMARY KEY,
  no_rm VARCHAR(50),
  nama_penunggu VARCHAR(100) NOT NULL,
  nama_pasien VARCHAR(100) NOT NULL,
  nama_ruangan VARCHAR(50) NOT NULL,
  foto VARCHAR(255) NOT NULL,
  status VARCHAR(20) DEFAULT 'aktif',
  tanggal_masuk DATE DEFAULT CURRENT_DATE,
  tgl_input TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  tgl_update TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

-- Indeks untuk performa
CREATE INDEX IF NOT EXISTS idx_nama_penunggu ON penunggu_pasien (nama_penunggu);
CREATE INDEX IF NOT EXISTS idx_status ON penunggu_pasien (status);

-- Buat Tabel riwayat_scan
CREATE TABLE IF NOT EXISTS riwayat_scan (
  id_riwayat SERIAL PRIMARY KEY,
  nama_penunggu VARCHAR(100) NOT NULL,
  nama_pasien VARCHAR(100) NOT NULL,
  ruangan VARCHAR(50) NOT NULL,
  status VARCHAR(20) DEFAULT 'MASUK',
  waktu_scan TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

-- Indeks
CREATE INDEX IF NOT EXISTS idx_riwayat_nama ON riwayat_scan (nama_penunggu);
CREATE INDEX IF NOT EXISTS idx_riwayat_status ON riwayat_scan (status);

-- Triggers untuk update tgl_update otomatis (opsional di PG)
CREATE OR REPLACE FUNCTION update_modified_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.tgl_update = now();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_penunggu_modtime
    BEFORE UPDATE ON penunggu_pasien
    FOR EACH ROW
    EXECUTE PROCEDURE update_modified_column();

-- =============================================
-- Contoh Data
-- =============================================

INSERT INTO penunggu_pasien 
(no_rm, nama_penunggu, nama_pasien, nama_ruangan, foto, status, tanggal_masuk) 
VALUES 
('RM-001', 'Budi Santoso', 'Siti Aminah', 'Ruang 101', 'uploads/faces/budi.jpg', 'aktif', CURRENT_DATE),
('RM-002', 'Ahmad Wijaya', 'Hartono Sumirat', 'Ruang 102', 'uploads/faces/ahmad.jpg', 'aktif', CURRENT_DATE),
('RM-003', 'Siti Nurhaliza', 'Rina Suryanto', 'Ruang 103', 'uploads/faces/siti.jpg', 'aktif', CURRENT_DATE),
('RM-004', 'Rudi Hermawan', 'Bambang Irawan', 'Ruang 104', 'uploads/faces/rudi.jpg', 'aktif', CURRENT_DATE);
