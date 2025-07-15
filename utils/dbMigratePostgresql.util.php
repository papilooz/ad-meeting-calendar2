<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Project bootstrap
require_once 'bootstrap.php';

// 3) Load environment settings
$typeConfig = require_once UTILS_PATH . '/envSetter.util.php';

// Build config array
$pgConfig = [
    'host' => $typeConfig['pgHost'],
    'port' => $typeConfig['pgPort'],
    'db'   => $typeConfig['pgDB'],
    'user' => $typeConfig['pgUser'],
    'pass' => $typeConfig['pgPass'],
];

// Connect to PostgreSQL
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";

try {
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connected to PostgreSQL\n";
} catch (PDOException $e) {
    echo "❌ Connection Failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Drop existing tables
echo "🧹 Dropping old tables…\n";
$tables = ['meeting_users', 'tasks', 'meetings', 'users', 'projects'];

foreach ($tables as $table) {
    $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
    echo "✅ Dropped: {$table}\n";
}

// Re-apply schema files
$modelFiles = [
    'users.model.sql',
    'meeting.model.sql',
    'meeting_users.model.sql',
    'tasks.model.sql',
];

foreach ($modelFiles as $modelFile) {
    $path = DATABASE_PATH . "/{$modelFile}";
    echo "📄 Applying schema from {$path}…\n";

    $sql = file_get_contents($path);

    if ($sql === false) {
        throw new RuntimeException("❌ Could not read {$path}");
    }

    $pdo->exec($sql);
    echo "✅ Created from {$modelFile}\n";
}

echo "🎉 Migration Complete!\n";
