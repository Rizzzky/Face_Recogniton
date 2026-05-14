<?php
require_once 'SupabaseClient.php';

// Supabase Configuration from Environment Variables
$supabaseUrl = getenv('SUPABASE_URL') ?: "https://blmyieexavlvlfsbqqub.supabase.co";

// Use Secret Key if available for full access, fallback to Publishable Key
// IMPORTANT: For security, DO NOT hardcode Secret Keys here. 
// Set them in Vercel Environment Variables or local .env file.
$supabaseKey = getenv('SUPABASE_SECRET_KEY') ?: getenv('SUPABASE_KEY');

// Initialize Supabase Client
try {
    if (!$supabaseUrl || !$supabaseKey) {
        throw new Exception("Supabase URL or Key is missing. Check Environment Variables.");
    }

    $supabase = new SupabaseClient($supabaseUrl, $supabaseKey);
    $conn = $supabase; // Alias for backward compatibility
    
    // Test connection by fetching 1 row from penunggu_pasien
    $test = $supabase->from('penunggu_pasien')->limit(1)->get();
    if ($test === false) {
        error_log("Supabase connection test failed. Check table and keys.");
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
