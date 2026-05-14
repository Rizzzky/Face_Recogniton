<?php
require_once 'SupabaseClient.php';

// Supabase Configuration from Environment Variables
$supabaseUrl = getenv('SUPABASE_URL') ?: "https://blmyieexavlvlfsbqqub.supabase.co";
$supabaseKey = getenv('SUPABASE_KEY') ?: "sb_publishable_iWb3thkPhw0ScFmobcxezQ_NdJZkz3d";

// Debug: Pastikan URL dan Key tidak kosong
if (empty($supabaseUrl) || empty($supabaseKey)) {
    error_log("Supabase URL or Key is missing!");
}

// Initialize Supabase Client
try {
    $supabase = new SupabaseClient($supabaseUrl, $supabaseKey);
    $conn = $supabase; // Alias for backward compatibility
    
    // Test connection by fetching 1 row from penunggu_pasien
    $test = $supabase->from('penunggu_pasien')->limit(1)->get();
    if ($test === false) {
        $supabase = null;
        $conn = null;
    }
} catch (Exception $e) {
    error_log("Supabase Connection Error: " . $e->getMessage());
    $supabase = null;
    $conn = null;
}

/**
 * Global helper to access Supabase
 */
function getSupabase() {
    global $supabase;
    return $supabase;
}
?>
