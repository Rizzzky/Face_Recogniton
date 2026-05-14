<?php

require_once __DIR__ . '/../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: ../index.php?page=dashboard');
    exit;
}

$query = mysqli_query($conn, "SELECT foto FROM penunggu_pasien WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if ($data && !empty($data['foto'])) {
    $fotoPath = __DIR__ . '/../' . $data['foto'];
    if (file_exists($fotoPath) && is_file($fotoPath)) {
        unlink($fotoPath);
    }
}

mysqli_query($conn, "DELETE FROM penunggu_pasien WHERE id='$id'");

header('Location: ../index.php?page=dashboard');
exit;
?>
