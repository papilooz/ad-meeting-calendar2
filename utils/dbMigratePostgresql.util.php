<?php
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
$typeConfig = require_once UTILS_PATH . 'envSetter.util.php';

// Prepare config array
$pgConfig = [
    'host' => $typeConfig['pg_host'],
    'port' => $typeConfig['pg_port'],
    'db'   => $typeConfig['pg_db'],
    'user' => $typeConfig['pg_user'],
    'pass' => $typeConfig['pg_pass'],
];

// ——— Connect to PostgreSQL ———
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
// ——— Drop existing tables ———
echo "🧹 Dropping old tables…\n";

foreach ([
    'meeting_users',
    'tasks',
    'meetings',
    'users',
    'projects' // ← include any other tables you expect to exist
] as $table) {
    $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
    echo "✅ Dropped: {$table}\n";
}
// ——— Re-apply schema files ———
$modelFiles = [
    'users.model.sql',
    'meeting.model.sql',
    'meeting_users.model.sql',
    'tasks.model.sql',
];

foreach ($modelFiles as $modelFile) {
    $path = BASE_PATH . "/database/{$modelFile}";
    echo "📄 Applying schema from {$path}…\n";

    $sql = file_get_contents($path);

    if ($sql === false) {
        throw new RuntimeException("❌ Could not read {$path}");
    } else {
        echo "✅ Created from {$modelFile}\n";
    }

    $pdo->exec($sql);
}

echo "🎉 Migration Complete!\n";
