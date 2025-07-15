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

// Drop existing tables (in dependency-safe order)
echo "🧹 Dropping old tables…\n";
$tables = [
    'public.meeting_users',
    'public.tasks',
    'public.meetings',
    'public.projects',  // adjust order if projects depend on meetings
    'public.images',
    'public.users'
];

foreach ($tables as $table) {
    try {
        $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
        echo "✅ Dropped: {$table}\n";
    } catch (PDOException $e) {
        echo "⚠️ Error dropping {$table}: " . $e->getMessage() . "\n";
    }
}

// Re-apply schema files
$modelFiles = [
    'users.model.sql',
    'meeting.model.sql',
    'meeting_users.model.sql',
    'tasks.model.sql',
    'images.model.sql'
];

foreach ($modelFiles as $modelFile) {
    $path = DATABASE_PATH . "/{$modelFile}";
    echo "📄 Applying schema from {$path}…\n";

    if (!file_exists($path)) {
        echo "❌ File not found: {$path}\n";
        continue;
    }

    $sql = file_get_contents($path);

    if ($sql === false) {
        echo "❌ Failed to read file: {$path}\n";
        continue;
    }

    try {
        $pdo->exec($sql);
        echo "✅ Created from {$modelFile}\n";
    } catch (PDOException $e) {
        echo "❌ Failed to apply {$modelFile}: " . $e->getMessage() . "\n";
    }
}

echo "🎉 Migration Complete!\n";
