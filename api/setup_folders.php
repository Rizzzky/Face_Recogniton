<?php
// File untuk memastikan folder uploads/faces ada

$folder = __DIR__ . '/../uploads/faces';

if (!is_dir($folder)) {
    if (mkdir($folder, 0755, true)) {
        echo "✅ Folder uploads/faces berhasil dibuat";
    } else {
        echo "❌ Gagal membuat folder uploads/faces. Silakan buat manual di: " . $folder;
    }
} else {
    echo "✅ Folder uploads/faces sudah ada";
}

// Check permissions
if (is_writable($folder)) {
    echo "<br>✅ Folder dapat ditulis";
} else {
    echo "<br>⚠️ Folder tidak dapat ditulis. Ubah permissions ke 755 atau 777";
}
?>
