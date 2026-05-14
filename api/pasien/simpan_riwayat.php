<?php
// pasien/simpan_riwayat.php

include '../config/database.php';

$nama_penunggu = $_POST['nama_penunggu'] ?? '';
$nama_pasien   = $_POST['nama_pasien'] ?? '';
$ruangan       = $_POST['ruangan'] ?? '';
$status        = $_POST['status'] ?? 'MASUK';

if (!isset($supabase)) {
    die("error: supabase not configured");
}

$insertData = [
    'nama_penunggu' => $nama_penunggu,
    'nama_pasien'   => $nama_pasien,
    'ruangan'       => $ruangan,
    'status'        => $status
];

$result = $supabase->from('riwayat_scan')->insert($insertData);

if ($result) {
    echo "success";
} else {
    echo "error";
}
exit;
?>
