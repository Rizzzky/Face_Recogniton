<?php

include 'config/database.php';

$nama_penunggu = $_POST['nama_penunggu'] ?? '';

if (!isset($supabase)) {
    echo json_encode(['total_masuk' => 0, 'total_keluar' => 0, 'error' => 'Supabase not configured']);
    exit;
}

// Fetch all records for this person to calculate totals
$result = $supabase->from('riwayat_scan')->eq('nama_penunggu', $nama_penunggu)->get();

$total_masuk = 0;
$total_keluar = 0;

if (is_array($result)) {
    foreach ($result as $row) {
        if ($row['status'] == 'MASUK') $total_masuk++;
        if ($row['status'] == 'KELUAR') $total_keluar++;
    }
}

echo json_encode([
    'total_masuk' => $total_masuk,
    'total_keluar' => $total_keluar
]);
exit;
?>
