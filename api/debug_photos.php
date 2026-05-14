<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/database.php';

echo "<h2>Database Photo Check</h2>";

if (!$conn) {
    echo "❌ Database connection failed";
    exit;
}

// First check table structure
echo "<h3>Table Structure:</h3><pre>";
$desc = mysqli_query($conn, "DESCRIBE penunggu_pasien");
while ($col = mysqli_fetch_assoc($desc)) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}
echo "</pre>";

// Get all photos from database - try different column names
$queries = [
    "SELECT * FROM penunggu_pasien LIMIT 1"
];

foreach ($queries as $query) {
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            echo "<h3>Sample Row (First Record):</h3><pre>";
            print_r($row);
            echo "</pre>";
        }
    }
}

// Now get all records with automatic column detection
$result = mysqli_query($conn, "SELECT * FROM penunggu_pasien");
if (!$result) {
    echo "❌ Query failed: " . mysqli_error($conn);
    exit;
}

echo "<h3>All Records:</h3><pre>";
$row_num = 1;
while ($row = mysqli_fetch_assoc($result)) {
    echo "Record #" . $row_num . ": ";
    // Find name and photo columns dynamically
    $nama = $row['nama_penunggu'] ?? $row['name'] ?? 'N/A';
    $foto = $row['foto'] ?? $row['photo'] ?? 'N/A';
    
    if ($foto !== 'N/A') {
        // Check if file exists
        if (file_exists($foto)) {
            $status = "✅ (direct)";
        } elseif (file_exists('../' . $foto)) {
            $status = "✅ (relative)";
        } elseif (file_exists('uploads/faces/' . basename($foto))) {
            $status = "✅ (fallback)";
        } else {
            $status = "❌ NOT FOUND";
        }
    } else {
        $status = "⚠️ NO PHOTO";
    }
    
    printf("Nama: %-20s | Foto: %-40s | %s\n", $nama, $foto, $status);
    $row_num++;
}
echo "</pre>";

mysqli_close($conn);
?>
