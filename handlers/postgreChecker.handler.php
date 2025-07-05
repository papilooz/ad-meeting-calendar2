<?php
// ✅ Define $config by loading env values
$config = require UTILS_PATH . 'envSetter.util.php';

$host = $config['pg_host'];
$port = $config['pg_port'];
$username = $config['pg_user'];
$password = $config['pg_pass'];
$dbname = $config['pg_db'];
$conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";

$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    echo "❌ PostgreSQL connection failed: " . pg_last_error() . "<br>";
    exit();
} else {
    echo "✅ Connected to PostgreSQL<br>";
    pg_close($dbconn);
}
