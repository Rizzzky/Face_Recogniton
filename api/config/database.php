<?php
require_once 'SupabaseClient.php';

// Supabase Configuration
$supabaseUrl = "https://blmyieexavlvlfsbqqub.supabase.co";
$supabaseKey = "sb_publishable_iWb3thkPhw0ScFmobcxezQ_NdJZkz3d";

// Initialize Supabase Client
try {
    $supabase = new SupabaseClient($supabaseUrl, $supabaseKey);
    $conn = $supabase; // Alias for backward compatibility
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
