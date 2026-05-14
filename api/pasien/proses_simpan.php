<?php
// pasien/proses_simpan.php
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Correct path to database config
include '../config/database.php';

// Check database connection
if (!isset($supabase)) {
    die(json_encode([
        'success' => false,
        'message' => 'Supabase connection failed'
    ]));
}

// Get form data
$nama_penunggu = trim($_POST['nama_penunggu'] ?? '');
$nama_pasien = trim($_POST['nama_pasien'] ?? '');
$nama_ruangan = trim($_POST['nama_ruangan'] ?? '');
$no_rm = trim($_POST['no_rm'] ?? '');
$tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
$foto_base64 = $_POST['foto_base64'] ?? '';

// Validation
if (empty($nama_penunggu) || empty($nama_pasien) || empty($nama_ruangan)) {
    die(json_encode([
        'success' => false,
        'message' => 'Semua field harus diisi'
    ]));
}

if (!$foto_base64) {
    die(json_encode([
        'success' => false,
        'message' => 'Foto belum diambil'
    ]));
}

// Create uploads/faces directory if not exists
$upload_dir = '../uploads/faces/';
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        die(json_encode([
            'success' => false,
            'message' => 'Gagal membuat folder uploads/faces'
        ]));
    }
}

// Decode base64
$foto_base64 = str_replace('data:image/png;base64,', '', $foto_base64);
$foto_base64 = str_replace('data:image/jpeg;base64,', '', $foto_base64);
$foto_base64 = str_replace('data:image/jpg;base64,', '', $foto_base64);
$foto_base64 = str_replace(' ', '+', $foto_base64);
$data_foto = base64_decode($foto_base64);

if (!$data_foto) {
    die(json_encode([
        'success' => false,
        'message' => 'Gagal decode foto'
    ]));
}

// Generate unique filename
$nama_file = uniqid() . '.png';
$path = $upload_dir . $nama_file;

// Save file
if (!file_put_contents($path, $data_foto)) {
    die(json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan file foto'
    ]));
}

// Path for database (relative)
$path_db = 'uploads/faces/' . $nama_file;

// Data to insert
$insertData = [
    'nama_penunggu' => $nama_penunggu,
    'nama_pasien' => $nama_pasien,
    'nama_ruangan' => $nama_ruangan,
    'foto' => $path_db,
    'status' => 'aktif'
];

// Execute insert
$result = $supabase->from('penunggu_pasien')->insert($insertData);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Data berhasil disimpan',
        'file' => $path_db
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan ke Supabase'
    ]);
}
exit;
?>
