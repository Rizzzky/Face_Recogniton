# Panduan Akses Kamera - Sistem Pengenalan Wajah

## Masalah: "Izin kamera ditolak" (Permission Denied)

### Solusi untuk berbagai browser:

---

## 1. **Google Chrome**

### Langkah-langkah:
1. Di sebelah kanan address bar, klik **ikon lock** atau **info icon** (ⓘ)
2. Cari bagian **"Camera"** atau **"Permissions"**
3. Ubah setting kamera dari **"Block"** menjadi **"Allow"**
4. **Refresh halaman** (tekan F5 atau Ctrl+R)
5. Klik **"Izinkan"** saat dialog muncul

### Alternatif:
1. Klik **3 titik** (menu) → **Settings**
2. Pilih **Privacy and security** → **Site settings**
3. Cari **Camera**
4. Tambahkan **http://localhost** atau **localhost:80** ke daftar **Allowed sites**
5. Refresh halaman

---

## 2. **Mozilla Firefox**

### Langkah-langkah:
1. Di address bar, klik ikon **lock** atau **i**
2. Klik **"Permissions"**
3. Cari **Camera** dan ubah menjadi **Allow**
4. **Refresh halaman**
5. Klik **"Allow"** saat dialog muncul

### Alternatif:
1. Ketik `about:preferences` di address bar
2. Pilih **Privacy & Security**
3. Scroll ke bawah ke **Permissions** → **Camera**
4. Klik **Exceptions** dan tambahkan **http://localhost**
5. Set ke **Allow**

---

## 3. **Microsoft Edge**

### Langkah-langkah:
1. Klik **3 garis** (menu) → **Settings**
2. Pilih **Privacy, search, and services**
3. Scroll ke **Permissions** → **Camera**
4. Tambahkan **http://localhost** ke **Allow**
5. Refresh halaman

---

## 4. **Safari (macOS)**

### Langkah-langkah:
1. Buka **System Preferences** → **Security & Privacy**
2. Pilih tab **Camera**
3. Pastikan **Safari** ada di daftar yang diizinkan
4. Klik **"Allow"** saat Safari minta akses kamera
5. Refresh halaman di Safari

---

## Catatan Penting:

⚠️ **Untuk localhost (HTTP):**
- Beberapa browser memerlukan **HTTPS** untuk akses kamera
- Jika masih tidak bekerja, coba setup HTTPS untuk localhost
- Atau gunakan IP address lokal: `http://192.168.x.x` (mungkin memerlukan port 8080+)

✅ **Setelah memberikan izin:**
1. Anda akan melihat **video feed dari kamera**
2. Loading indicator akan menghilang
3. Sistem siap untuk **pengenalan wajah**

---

## Troubleshooting:

| Masalah | Solusi |
|---------|--------|
| Kamera tidak ditemukan | Periksa kabel USB kamera / gunakan built-in camera |
| Kamera digunakan aplikasi lain | Tutup aplikasi yang menggunakan kamera (Skype, OBS, dll) |
| Izin masih ditolak | Clear browser cache & cookies, atau coba incognito mode |
| HTTPS error | Setup SSL certificate untuk localhost atau gunakan IP lokal |

---

## Untuk Administrator Sistem:

Jika ingin **permanent access** tanpa perlu user consent setiap kali:
- Setup **HTTPS** dengan valid SSL certificate
- Atau configure **domain permissions** di browser policies
- Atau setup **Kubernetes/Docker** dengan local development server

---

*Last Updated: May 14, 2026*
