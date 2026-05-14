# Troubleshooting: "Tidak ada data wajah ditemukan"

## ⚠️ Gejala
- Halaman pengenalan wajah menampilkan: **"⚠️ Tidak ada data wajah ditemukan"**
- Sistem tidak bisa mendeteksi wajah penunggu
- Tombol Masuk/Keluar tidak responsif

---

## 🔍 Penyebab Umum

### 1. **Database Tidak Ter-Setup**
```
Database 'db_penunggu_rs' belum dibuat
Tabel 'penunggu_pasien' belum ada
```

### 2. **Tidak Ada Data di Database**
```
Database kosong - belum ada data penunggu
Semua data memiliki status 'nonaktif'
```

### 3. **Foto Tidak Valid**
```
Path foto salah atau tidak lengkap
File foto tidak ditemukan di server
Wajah di foto tidak terdeteksi (blur/kecil)
```

### 4. **Model Face Recognition Tidak Ter-Load**
```
Koneksi internet putus
File model tidak lengkap di folder assets/models/
```

---

## ✅ Solusi Langkah Demi Langkah

### STEP 1: Setup Database

#### Opsi A - Menggunakan phpMyAdmin
1. Buka `http://localhost/phpmyadmin`
2. Klik **"New"** atau **"Create Database"**
3. Nama: `db_penunggu_rs`
4. Collation: `utf8mb4_unicode_ci`
5. Klik **"Create"**

#### Opsi B - Menggunakan SQL File
1. Copy file: `database_setup.sql` (ada di root project)
2. Buka phpMyAdmin
3. Pilih tab **"SQL"** atau **"Import"**
4. Paste atau upload file `database_setup.sql`
5. Klik **"Execute"**

#### Opsi C - Menggunakan Command Line
```bash
cd C:\xampp\mysql\bin
mysql -u root -p < "C:\xampp\htdocs\face-recognitions-rs\database_setup.sql"
# Tekan Enter saat diminta password (kosongkan untuk default XAMPP)
```

---

### STEP 2: Buat Tabel Penunggu

Jika manual, jalankan SQL ini di phpMyAdmin:

```sql
USE db_penunggu_rs;

CREATE TABLE IF NOT EXISTS `penunggu_pasien` (
  `id_penunggu` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_penunggu` VARCHAR(100) NOT NULL,
  `nama_pasien` VARCHAR(100) NOT NULL,
  `nama_ruangan` VARCHAR(50) NOT NULL,
  `foto` VARCHAR(255) NOT NULL,
  `status` ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
  `tgl_input` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_nama_penunggu` (`nama_penunggu`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### STEP 3: Input Data Penunggu

#### Opsi A - Via Aplikasi Web
1. Buka sistem: `http://localhost/face-recognitions-rs`
2. Klik menu **"Input Penunggu"**
3. Isi data:
   - Nama Penunggu
   - Nama Pasien
   - Ruangan
   - Upload Foto
4. Klik **"Simpan"**

#### Opsi B - Via SQL Insert
```sql
USE db_penunggu_rs;

INSERT INTO penunggu_pasien 
(nama_penunggu, nama_pasien, nama_ruangan, foto, status) 
VALUES 
('John Doe', 'Jane Smith', 'ICU 01', 'uploads/faces/john.jpg', 'aktif'),
('Ahmad Yusuf', 'Ibu Sari', 'Ruang 201', 'uploads/faces/ahmad.jpg', 'aktif'),
('Siti Mariam', 'Pak Budi', 'Ruang 302', 'uploads/faces/siti.jpg', 'aktif');
```

---

### STEP 4: Upload Foto

1. Buka folder project: `C:\xampp\htdocs\face-recognitions-rs`
2. Masuk ke folder: `uploads/faces/`
3. Upload foto penunggu di sini
4. **Catatan nama file** - harus sama dengan path di database

Struktur folder yang benar:
```
face-recognitions-rs/
├── uploads/
│   └── faces/
│       ├── john.jpg
│       ├── ahmad.jpg
│       └── siti.jpg
```

---

### STEP 5: Verifikasi Data

1. Buka halaman Pengenalan Wajah
2. Klik tombol **"🔍 Cek Data"** di panel kanan bawah
3. Lihat alert yang muncul - harus menampilkan:
   ```
   Status Database:
   Total Data: X (seharusnya > 0)
   Descriptors Loaded: X
   Models: Loaded
   Camera: Active
   ```

---

## 🔧 Advanced Troubleshooting

### Debug di Browser Console

Buka **Developer Tools** (F12) → **Console** tab, jalankan:

```javascript
// Check data from API
fetch('pasien/get_data_penunggu.php')
  .then(r => r.json())
  .then(d => console.table(d))

// Check model status
console.log('Models Loaded:', modelsLoaded)
console.log('Labeled Descriptors:', labeledFaceDescriptors.length)
console.log('Camera Stream:', video.srcObject)
```

### Check Database Connection

Buat file test di root project: `test_db.php`

```php
<?php
include 'config/database.php';

if ($conn) {
    echo "✅ Database connected!";
    
    // Check table
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'penunggu_pasien'");
    if (mysqli_num_rows($result) > 0) {
        echo "<br>✅ Table exists";
        
        // Check data
        $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM penunggu_pasien WHERE status = 'aktif'");
        $row = mysqli_fetch_assoc($count);
        echo "<br>✅ Active records: " . $row['total'];
    } else {
        echo "<br>❌ Table not found";
    }
} else {
    echo "❌ Database connection failed: " . mysqli_connect_error();
}
?>
```

Akses: `http://localhost/face-recognitions-rs/test_db.php`

---

## 📋 Checklist Lengkap

- [ ] Database `db_penunggu_rs` sudah dibuat
- [ ] Tabel `penunggu_pasien` sudah ada
- [ ] Minimal 1 data penunggu dengan status = 'aktif'
- [ ] Folder `uploads/faces/` sudah ada dan dapat ditulis
- [ ] Foto penunggu sudah diupload ke folder tersebut
- [ ] Path foto di database benar (misal: `uploads/faces/john.jpg`)
- [ ] Foto dalam format JPG/PNG/JPEG
- [ ] Foto ukuran minimum 200x200px
- [ ] Wajah di foto jelas (tidak blur/blur)
- [ ] Pose wajah frontal (menghadap ke depan)
- [ ] Lighting foto cukup baik
- [ ] Browser sudah beri izin akses kamera
- [ ] Model face recognition sudah ter-load
- [ ] Refresh halaman setelah setup database

---

## 🆘 Jika Masih Tidak Bekerja

1. **Restart XAMPP**
   - Stop Apache & MySQL
   - Tunggu beberapa detik
   - Start Apache & MySQL lagi

2. **Clear Browser Cache**
   - Ctrl + Shift + Delete
   - Pilih "All Time"
   - Hapus semua

3. **Check File Permissions**
   - Folder `uploads/faces/` harus writable
   - Database user harus punya akses penuh

4. **Lihat Error Log**
   - XAMPP Apache Error Log: `C:\xampp\apache\logs\error.log`
   - Cek MySQL Error Log: `C:\xampp\mysql\data\`

5. **Contact Admin atau Hubungi Support**
   - Sertakan screenshot error
   - Sertakan output dari "Cek Data" button
   - Sertakan browser console error (F12)

---

## 📚 Referensi File

- **Setup Database**: `database_setup.sql`
- **Panduan Kamera**: `CAMERA_ACCESS_GUIDE.md`
- **Setup Dokumentasi**: `DATABASE_SETUP.md`
- **Config**: `config/database.php`
- **API Endpoint**: `pasien/get_data_penunggu.php`

---

*Last Updated: May 14, 2026*
