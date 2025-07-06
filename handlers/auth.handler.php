<?php
declare(strict_types=1);
session_start();

// Bootstrap + DB config
require_once '../bootstrap.php';
require_once UTILS_PATH . 'envSetter.util.php';

$pgConfig = [
    'host' => $typeConfig['pg_host'],
    'port' => $typeConfig['pg_port'],
    'db'   => $typeConfig['pg_db'],
    'user' => $typeConfig['pg_user'],
    'pass' => $typeConfig['pg_pass'],
];

$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass']);

// Handle input
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error'] = 'Invalid username or password';
    header('Location: /login.php');
    exit;
}

// Save session
$_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'],
    'role' => $user['role']
];

header('Location: /index.php');
exit;
