<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

include '../config/database.php';

// Set response header
header('Content-Type: application/json');

// Initialize response
$response = [];
$error = null;

// Check connection
if (!isset($supabase)) {
    echo json_encode(['error' => 'Supabase client not initialized', 'data' => []]);
    exit;
}

// Query data
$result = $supabase->from('penunggu_pasien')->select('*')->get();

if ($result === false) {
    echo json_encode(['error' => 'Gagal mengambil data dari Supabase', 'data' => []]);
    exit;
}

$data = [];
$totalRows = 0;

foreach ($result as $d) {
    // Validate required fields
    if (empty($d['nama_penunggu']) || empty($d['foto'])) {
        continue; // Skip rows with missing critical data
    }

    // Build full photo path for browser (absolute path from web root)
    $fotoPath = $d['foto'];
    
    // Convert to absolute web path for browser to load
    // Database stores: uploads/faces/xxxxx.png
    // We need to return: /face-recognitions-rs/uploads/faces/xxxxx.png
    if (strpos($fotoPath, 'uploads/faces/') === 0) {
        // Already in relative format, convert to absolute
        $fotoPath = '/face-recognitions-rs/' . $fotoPath;
    } elseif (file_exists($fotoPath)) {
        // Already full path, use as is
        $fotoPath = $fotoPath;
    } elseif (file_exists('../' . $fotoPath)) {
        // Relative path from current dir, adjust
        $fotoPath = '/face-recognitions-rs/' . $fotoPath;
    } else {
        // Photo file not found, skip this record
        error_log("Photo not found: " . $fotoPath);
        continue;
    }

    $data[] = [
        'label' => trim($d['nama_penunggu']),
        'nama_pasien' => trim($d['nama_pasien'] ?? 'N/A'),
        'ruangan' => trim($d['nama_ruangan'] ?? 'N/A'),
        'foto' => $fotoPath,
        'id' => $d['id'] ?? null
    ];
    $totalRows++;
}

// Return data with metadata
echo json_encode([
    'error' => null,
    'data' => $data,
    'total' => $totalRows,
    'message' => $totalRows > 0 ? "Data berhasil diambil" : "Tidak ada data penunggu yang aktif"
]);

exit;
?>
