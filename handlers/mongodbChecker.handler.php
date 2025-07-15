<?php
// ✅ Load env config
$config = require_once UTILS_PATH . '/envSetter.util.php'; // <-- fixed missing slash

try {
    // ✅ Create MongoDB connection using URI from config
    $mongo = new MongoDB\Driver\Manager($config['mongo_uri']);

    // ✅ Run a ping command to check connectivity
    $command = new MongoDB\Driver\Command(["ping" => 1]);
    $mongo->executeCommand($config['mongo_db'], $command);

    echo "✅ Connected to MongoDB successfully.<br>";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "❌ MongoDB connection failed: " . $e->getMessage() . "<br>";
}
