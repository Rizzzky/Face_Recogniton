<?php
require_once 'SupabaseClient.php';

// Simple .env loader for local development
if (file_exists(__DIR__ . '/../../.env')) {
    $lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value));
    }
}

// Supabase Configuration from Environment Variables
$supabaseUrl = getenv('SUPABASE_URL') ?: "https://blmyieexavlvlfsbqqub.supabase.co";
$supabaseKey = getenv('SUPABASE_SECRET_KEY') ?: getenv('SUPABASE_KEY');

// Global error message variable
$db_error = null;

// Initialize Supabase Client
try {
    if (!$supabaseUrl) {
        throw new Exception("SUPABASE_URL tidak ditemukan di Environment Variables.");
    }
    if (!$supabaseKey) {
        throw new Exception("SUPABASE_KEY atau SUPABASE_SECRET_KEY tidak ditemukan di Environment Variables.");
    }

    $supabase = new SupabaseClient($supabaseUrl, $supabaseKey);
    $conn = $supabase; // Alias for backward compatibility
    
    // Test connection
    $test = $supabase->from('penunggu_pasien')->limit(1)->get();
    
    // If Supabase returns an error object instead of an array of data
    if (isset($test['error'])) {
        throw new Exception("Supabase API Error: " . ($test['message'] ?? "Gagal terhubung ke tabel penunggu_pasien"));
    }
} catch (Exception $e) {
    error_log("Supabase Connection Error: " . $e->getMessage());
    $supabase = null;
    $conn = null;
    $db_error = $e->getMessage();
}

/**
 * Global helper to access Supabase
 */
function getSupabase() {
    global $supabase;
    return $supabase;
}
?>
