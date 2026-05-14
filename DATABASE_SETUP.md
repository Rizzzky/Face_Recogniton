# Panduan Setup Database - Sistem Pengenalan Wajah

## Masalah: "Tidak ada data wajah ditemukan"

Ini biasanya terjadi karena salah satu dari:
1. Database belum dibuat
2. Tabel belum ada
3. Belum ada data di database
4. Foto penunggu tidak valid

---

## 1. Membuat Database

### Menggunakan phpMyAdmin:

1. Buka **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Klik **"New"** di panel kiri
3. Masukkan nama database: `db_penunggu_rs`
4. Klik **"Create"**

### Atau menggunakan SQL Command:

```sql
CREATE DATABASE IF NOT EXISTS db_penunggu_rs;
USE db_penunggu_rs;
```

---

## 2. Membuat Tabel `penunggu_pasien`

Copy dan jalankan SQL ini di phpMyAdmin:

```sql
CREATE TABLE IF NOT EXISTS `penunggu_pasien` (
  `id_penunggu` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_penunggu` VARCHAR(100) NOT NULL,
  `nama_pasien` VARCHAR(100) NOT NULL,
  `nama_ruangan` VARCHAR(50) NOT NULL,
  `foto` VARCHAR(255) NOT NULL,
  `status` ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
  `tgl_input` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `nama_penunggu` (`nama_penunggu`),
  INDEX `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 3. Insert Data Penunggu

Contoh data yang bisa ditambahkan:

```sql
INSERT INTO penunggu_pasien 
(nama_penunggu, nama_pasien, nama_ruangan, foto, status) 
VALUES 
('Budi Santoso', 'Siti Aminah', 'Ruang 101', 'uploads/faces/budi.jpg', 'aktif'),
('Ahmad Wijaya', 'Hartono Sumirat', 'Ruang 102', 'uploads/faces/ahmad.jpg', 'aktif'),
('Siti Nurhaliza', 'Rina Suryanto', 'Ruang 103', 'uploads/faces/siti.jpg', 'aktif');
```

---

## 4. Struktur Folder Foto

Pastikan folder foto sudah ada:

```
face-recognitions-rs/
├── uploads/
│   └── faces/
│       ├── budi.jpg
│       ├── ahmad.jpg
│       └── siti.jpg
```

---

## 5. Checklist Troubleshooting

### Database Connection
- [ ] Database `db_penunggu_rs` sudah dibuat?
- [ ] User MySQL adalah `root`?
- [ ] Password MySQL kosong (default XAMPP)?

### Tabel & Data
- [ ] Tabel `penunggu_pasien` sudah ada?
- [ ] Ada data di tabel? 
  ```sql
  SELECT COUNT(*) FROM penunggu_pasien WHERE status = 'aktif';
  ```

### Foto
- [ ] Folder `uploads/faces/` sudah ada?
- [ ] Foto sudah diupload ke folder tersebut?
- [ ] Path foto di database benar? (contoh: `uploads/faces/budi.jpg`)
- [ ] Format foto: JPG, PNG, JPEG?
- [ ] Ukuran foto: besar minimal 200x200px?

### Face Detection
- [ ] Wajah di foto jelas dan tidak blur?
- [ ] Pose wajah frontal (menghadap ke depan)?
- [ ] Lighting cukup baik di foto?

---

## 6. Debug: Cek API Response

1. Buka **Developer Tools** (F12) → **Console** tab
2. Jalankan command:
   ```javascript
   fetch('pasien/get_data_penunggu.php')
     .then(r => r.json())
     .then(d => console.log(JSON.stringify(d, null, 2)))
   ```

3. Lihat output di console - harus menampilkan:
   ```json
   {
     "error": null,
     "data": [
       {
         "label": "Budi Santoso",
         "nama_pasien": "Siti Aminah",
         "ruangan": "Ruang 101",
         "foto": "uploads/faces/budi.jpg",
         "id": 1
       }
     ],
     "total": 1,
     "message": "Data berhasil diambil"
   }
   ```

---

## 7. Solusi Cepat

### Jika masih error:

1. **Refresh halaman** dengan Ctrl+Shift+Delete (clear cache)
2. **Check Browser Console** (F12) untuk error message
3. **Verify file path** - pastikan semua path relatif benar
4. **Check permissions** - folder `uploads/faces/` harus writable
5. **Restart XAMPP** Apache & MySQL

---

## Hubungi Admin Jika:
- Database tidak bisa diakses
- File permission error
- Model face recognition tidak bisa load

---

*Last Updated: May 14, 2026*
