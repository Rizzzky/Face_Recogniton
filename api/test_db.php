<?php
// api/test_db.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Supabase Connection Diagnostic</h1>";

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    echo "✅ .env file found.<br>";
} else {
    echo "❌ .env file NOT found at $envFile.<br>";
}

require_once 'config/database.php';

echo "<h2>Environment Variables:</h2>";
echo "SUPABASE_URL: " . (getenv('SUPABASE_URL') ? getenv('SUPABASE_URL') : "NOT SET") . "<br>";
echo "SUPABASE_KEY: " . (getenv('SUPABASE_KEY') ? "SET (Hidden)" : "NOT SET") . "<br>";
echo "SUPABASE_SECRET_KEY: " . (getenv('SUPABASE_SECRET_KEY') ? "SET (Hidden)" : "NOT SET") . "<br>";

if (isset($supabase) && $supabase !== null) {
    echo "<h2 style='color:green'>✅ Supabase Client Initialized</h2>";
    
    echo "<h3>Testing Query (penunggu_pasien):</h3>";
    $test = $supabase->from('penunggu_pasien')->limit(1)->get();
    
    if (isset($test['error'])) {
        echo "<div style='color:red; border:1px solid red; padding:10px;'>";
        echo "❌ Query Error!<br>";
        echo "Message: " . ($test['message'] ?? 'N/A') . "<br>";
        echo "Hint: " . ($test['hint'] ?? 'N/A') . "<br>";
        echo "Status: " . ($test['status'] ?? 'N/A') . "<br>";
        echo "</div>";
    } else {
        echo "<div style='color:green; border:1px solid green; padding:10px;'>";
        echo "✅ Query Successful!<br>";
        echo "Data found: " . count($test) . " rows.<br>";
        echo "</div>";
    }
} else {
    echo "<h2 style='color:red'>❌ Supabase Client NOT Initialized</h2>";
    if (isset($error_message)) {
        echo "Error: " . $error_message;
    }
}
?>
