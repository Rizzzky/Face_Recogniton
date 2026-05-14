<?php
// pasien/simpan_riwayat.php

include '../config/database.php';

$nama_penunggu = $_POST['nama_penunggu'];
$nama_pasien   = $_POST['nama_pasien'];
$ruangan       = $_POST['ruangan'];
$status        = $_POST['status'];

mysqli_query($conn, "
    INSERT INTO riwayat_scan
    (nama_penunggu, nama_pasien, ruangan, status)
    VALUES
    ('$nama_penunggu', '$nama_pasien', '$ruangan', '$status')
");

echo "success";
exit;
?>