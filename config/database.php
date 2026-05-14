<?php
$host = "localhost";
$user = "root";
$pass = ""; // default XAMPP kosong
$db   = "db_penunggu_rs";

// Suppress mysqli exceptions temporarily
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    // Set connection error but don't die
    // This allows pages to handle errors gracefully
    $conn = null;
}
