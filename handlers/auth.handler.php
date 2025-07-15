<?php
declare(strict_types=1);

// 1. Core includes
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';

// 2. Load environment config
$config = require UTILS_PATH . '/envSetter.util.php';

// ✅ Double check config is an array
if (!is_array($config)) {
    die("❌ Failed to load configuration.");
}

// 3. Extract config vars
$host = $config['pgHost'] ?? '';
$port = $config['pgPort'] ?? '';
$db   = $config['pgDB'] ?? '';
$user = $config['pgUser'] ?? '';
$pass = $config['pgPass'] ?? '';

// ✅ Safety check
if (!$host || !$port || !$db || !$user || !$pass) {
    die("❌ Incomplete DB config: host={$host}, port={$port}, db={$db}, user={$user}");
}

// 4. Connect to PostgreSQL
try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$db}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("❌ DB connection failed: " . $e->getMessage());
}

// 5. Start session
Auth::init();

// 6. Route handler
$action = $_REQUEST['action'] ?? null;

// --- LOGIN ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');

    if (Auth::login($pdo, $usernameInput, $passwordInput)) {
        $user = Auth::user();

        // Role-based redirect
        if ($user['role'] === 'team lead') {
            header('Location: /pages/Dashboard/index.php');
        } else {
            header('Location: /pages/Dashboard/index.php');
        }
        exit;
    } else {
        header('Location: /index.php?error=Invalid%20Credentials');
        exit;
    }
}

// --- LOGOUT ---
if ($action === 'logout') {
    Auth::init();
    Auth::logout();
    header('Location: /index.php');
    exit;
}

// Default redirect
header('Location: /index.php');
exit;
