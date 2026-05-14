<?php
// pasien/proses_simpan.php
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Correct path to database config
include '../config/database.php';

// Check database connection
if (!$conn) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . mysqli_connect_error()
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

// Get table structure first
$describe = mysqli_query($conn, "DESCRIBE penunggu_pasien");
$columns = [];
while ($col = mysqli_fetch_assoc($describe)) {
    $columns[] = $col['Field'];
}

// Build query with available columns
$fields = ['nama_penunggu', 'nama_pasien', 'nama_ruangan', 'foto'];
$values = ["'$nama_penunggu'", "'$nama_pasien'", "'$nama_ruangan'", "'$path_db'"];

// Add optional fields if they exist in table
if (in_array('tgl_input', $columns)) {
    $fields[] = 'tgl_input';
    $values[] = "NOW()";
}
if (in_array('status', $columns)) {
    $fields[] = 'status';
    $values[] = "'aktif'";
}
if (in_array('tanggal_masuk', $columns)) {
    $fields[] = 'tanggal_masuk';
    $values[] = "'$tanggal_masuk'";
}
if (in_array('no_rm', $columns)) {
    $fields[] = 'no_rm';
    $values[] = "'$no_rm'";
}

// Build final query
$fields_str = implode(', ', $fields);
$values_str = implode(', ', $values);
$query = "INSERT INTO penunggu_pasien ($fields_str) VALUES ($values_str)";

// Execute query
if (!mysqli_query($conn, $query)) {
    // Delete the uploaded file if insert fails
    @unlink($path);
    die(json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan ke database: ' . mysqli_error($conn)
    ]));
}

// Close connection
mysqli_close($conn);

// Return success
echo json_encode([
    'success' => true,
    'message' => 'Data berhasil disimpan',
    'redirect' => '/face-recognitions-rs/index.php?page=dashboard'
]);
exit;
?>
