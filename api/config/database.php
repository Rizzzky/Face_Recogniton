<?php
require_once 'SupabaseClient.php';

// Supabase Configuration from Environment Variables
// On Vercel, these are set in the Project Settings > Environment Variables
// Locally, they can be set in the system or web server
$supabaseUrl = getenv('SUPABASE_URL') ?: "https://blmyieexavlvlfsbqqub.supabase.co";
$supabaseKey = getenv('SUPABASE_KEY') ?: "sb_publishable_iWb3thkPhw0ScFmobcxezQ_NdJZkz3d";

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
