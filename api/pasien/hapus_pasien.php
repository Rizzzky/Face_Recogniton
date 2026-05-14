<?php

require_once __DIR__ . '/../config/database.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !isset($supabase)) {
    header('Location: ../index.php?page=dashboard');
    exit;
}

// Get photo path before deleting
$result = $supabase->from('penunggu_pasien')->eq('id_penunggu', $id)->get();
$data = (is_array($result) && count($result) > 0) ? $result[0] : null;

if ($data && !empty($data['foto'])) {
    $fotoPath = __DIR__ . '/../../' . $data['foto'];
    if (file_exists($fotoPath) && is_file($fotoPath)) {
        @unlink($fotoPath);
    }
}

// Delete from Supabase
$supabase->from('penunggu_pasien')->eq('id_penunggu', $id)->delete();

header('Location: ../index.php?page=dashboard');
exit;
?>
