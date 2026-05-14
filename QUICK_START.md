# Quick Start Guide - Setup Data Penunggu

## Cara Cepat Mengatasi "Tidak ada data wajah ditemukan"

---

## ⚡ Solusi Cepat (5 Menit)

### 1️⃣ Import Database (SQL)

**File**: `database_setup.sql` (di root project)

**Langkah**:
- Buka `http://localhost/phpmyadmin`
- Klik tab **"Import"**
- Upload atau paste file `database_setup.sql`
- Klik **"Execute"**

✅ Database siap dalam 1 menit

---

### 2️⃣ Input Data Penunggu

**Via Web App**:
1. Buka: `http://localhost/face-recognitions-rs`
2. Klik **"Input Penunggu"** di sidebar
3. Isi form:
   - ✏️ Nama Penunggu: contoh "Budi Santoso"
   - ✏️ Nama Pasien: contoh "Siti Aminah"
   - ✏️ Ruangan: contoh "ICU 01"
   - 📷 Upload Foto
4. Klik **"Simpan"**

✅ Data tersimpan di database

---

### 3️⃣ Verifikasi Data

Di halaman **"Pengenalan Wajah"**:
1. Klik tombol **"🔍 Cek Data"** (di panel kanan bawah)
2. Lihat alert - harus menampilkan:
   ```
   Total Data: X (seharusnya ≥ 1)
   Descriptors Loaded: X (seharusnya ≥ 1)
   Models: Loaded
   Camera: Active
   ```

✅ Sistem siap deteksi

---

## 🔧 Jika Masih Error

### Error: "Koneksi database gagal"
```
❌ PENYEBAB: Database belum ada
✅ SOLUSI: 
   1. Import database_setup.sql
   2. Atau buat manual di phpMyAdmin
   3. Nama: db_penunggu_rs
```

### Error: "Tabel 'penunggu_pasien' tidak ditemukan"
```
❌ PENYEBAB: Tabel belum dibuat
✅ SOLUSI: 
   1. Buka phpMyAdmin → database db_penunggu_rs
   2. Klik tab "SQL"
   3. Copy-paste SQL dari DATABASE_SETUP.md
   4. Execute
```

### Error: "Tidak ada data penunggu yang aktif"
```
❌ PENYEBAB: Database kosong atau semua nonaktif
✅ SOLUSI: 
   1. Input data via menu "Input Penunggu"
   2. Atau insert via SQL (lihat DATABASE_SETUP.md)
   3. Pastikan status = "aktif"
```

### Error: "Model wajah tidak dapat diproses"
```
❌ PENYEBAB: Foto blur atau wajah tidak jelas
✅ SOLUSI: 
   1. Upload ulang foto dengan kualitas lebih baik
   2. Pastikan wajah frontal (menghadap ke depan)
   3. Lighting cukup terang
   4. Foto minimal 200x200px
```

---

## 📊 Testing Checklist

- [ ] Database ada (phpMyAdmin bisa akses db_penunggu_rs)
- [ ] Tabel ada (phpMyAdmin bisa lihat tabel penunggu_pasien)
- [ ] Data ada (phpMyAdmin ada minimal 1 row dengan status=aktif)
- [ ] Foto ada (folder uploads/faces/ ada file foto)
- [ ] Foto path benar (di database sesuai file yang ada)
- [ ] Tombol "Cek Data" bisa diklik
- [ ] Alert menampilkan Total Data > 0
- [ ] Alert menampilkan Descriptors Loaded > 0

---

## 💡 Tips

1. **Gunakan foto berkualitas** - minimal resolusi HD
2. **Wajah harus jelas** - tidak blur, tidak ada hiasan berlebihan
3. **Lighting baik** - hindari backlight atau shadow di wajah
4. **Status aktif** - pastikan di database status = 'aktif'
5. **Test setiap step** - jangan skip verifikasi

---

## 🎯 Hasil Akhir

Setelah setup benar, sistem akan:
- ✅ Mendeteksi wajah penunggu
- ✅ Menampilkan nama & info pasien
- ✅ Kotak hijau di sekitar wajah
- ✅ Bisa record Masuk/Keluar
- ✅ Simpan riwayat otomatis

---

## 📞 Need Help?

Jika masih stuck:
1. Klik **"🔍 Cek Data"** dan lihat errornya
2. Baca file **TROUBLESHOOTING.md**
3. Baca file **DATABASE_SETUP.md**
4. Buka Browser Console (F12) untuk detail error
5. Hubungi admin sistem

---

*Setup Time: ~5-10 menit*  
*Last Updated: May 14, 2026*
