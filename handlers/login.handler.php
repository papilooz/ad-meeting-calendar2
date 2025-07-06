<?php
declare(strict_types=1);
session_start();

// Load bootstrap and config
require_once '../bootstrap.php';
require_once VENDOR_PATH . 'autoload.php';
$typeConfig = require_once UTILS_PATH . 'envSetter.util.php';

if (!is_array($typeConfig)) {
    exit("❌ Config not loaded from envSetter.util.php");
}


// Get form credentials
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Return with error if empty
if ($username === '' || $password === '') {
    $_SESSION['error'] = 'Username and password are required.';
    header('Location: /login.php');
    exit;
}

// PG Config
$pgConfig = [
    'host' => $typeConfig['pg_host'],
    'port' => $typeConfig['pg_port'],
    'db'   => $typeConfig['pg_db'],
    'user' => $typeConfig['pg_user'],
    'pass' => $typeConfig['pg_pass'],
];

// Connect to PostgreSQL
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";

try {
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    exit("❌ Database connection failed: " . $e->getMessage());
}

// Query for user
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Validate credentials
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error'] = 'Invalid username or password';
    header('Location: /login.php');
    exit;
}

// Successful login
$_SESSION['user'] = [
    'id'         => $user['id'],
    'username'   => $user['username'],
    'role'       => $user['role'],
    'first_name' => $user['first_name'],
    'last_name'  => $user['last_name'],
];

header('Location: /index.php');
exit;
