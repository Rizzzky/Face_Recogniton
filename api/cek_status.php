<?php

include '/config/database.php';

$nama_penunggu = $_POST['nama_penunggu'];

$q = mysqli_query($conn, "
    SELECT 
        SUM(status='MASUK') AS total_masuk,
        SUM(status='KELUAR') AS total_keluar
    FROM riwayat_scan
    WHERE nama_penunggu = '$nama_penunggu'
");

$data = mysqli_fetch_assoc($q);

echo json_encode($data);
?>