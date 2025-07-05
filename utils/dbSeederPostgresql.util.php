<?php
declare(strict_types=1);

// Load bootstrap (which sets up env, paths, and DB credentials)
require_once __DIR__ . '/../bootstrap.php';

// Load dummy data
$users = require STATIC_DATA_PATH . '/users.staticData.php';

// Connect to PostgreSQL using variables from bootstrap
try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connected to PostgreSQL\n";
} catch (PDOException $e) {
    exit("❌ Database connection failed: " . $e->getMessage() . "\n");
}

// Load SQL schema file and run it
$modelFile = BASE_PATH . '/database/users.model.sql';

if (!file_exists($modelFile)) {
    exit("❌ Could not read $modelFile\n");
}

$sql = file_get_contents($modelFile);
$pdo->exec($sql);
echo "✅ Users table created or verified\n";

// Prepare seeding statement
$stmt = $pdo->prepare("
    INSERT INTO users (username, email, role, first_name, last_name, password)
    VALUES (:username, :email, :role, :fn, :ln, :pw)
");


// Seed users
echo "🌱 Seeding users…\n";

foreach ($users as $u) {
   $stmt->execute([
    ':username' => $u['username'],
    ':email'    => $u['email'],
    ':role'     => $u['role'],
    ':fn'       => $u['first_name'],
    ':ln'       => $u['last_name'],
    ':pw'       => password_hash($u['password'], PASSWORD_DEFAULT),
]);

}

echo "✅ PostgreSQL seeding complete!\n";
