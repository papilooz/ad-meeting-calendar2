<?php
declare(strict_types=1);

require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
$databases = require_once UTILS_PATH . '/envSetter.util.php';
require_once UTILS_PATH . '/signup.util.php';
require_once UTILS_PATH . '/auth.util.php';

// Start session so we can flash errors / old input
Auth::init();

// Build PDO
$host = $databases['pgHost'];
$port = $databases['pgPort'];
$dbUser = $databases['pgUser'];
$dbPass = $databases['pgPass'];
$dbName = $databases['pgDB'];

$dsn = "pgsql:host={$host};port={$port};dbname={$dbName}";
$pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/signup/index.php');
    exit;
}

// Collect raw input
$input = [
    'first_name' => $_POST['first_name'] ?? '',
    'middle_name' => $_POST['middle_name'] ?? '',
    'last_name' => $_POST['last_name'] ?? '',
    'username' => $_POST['username'] ?? '',
    'password' => $_POST['password'] ?? '',
    'role' => $_POST['role'] ?? '',
];

// 1) Validate
$errors = Signup::validate($input);

if (count($errors) > 0) {
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['signup_old'] = $input;
    header('Location: /pages/signup/index.php');
    exit;
}

// 2) Create user
try {
    Signup::create($pdo, $input);
    $user = Signup::findByUsername($pdo, $input['username']);
    Auth::login($user);

} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        $_SESSION['signup_errors'] = ['Username already taken.'];
        $_SESSION['signup_old'] = $input;
        header('Location: /pages/signup/index.php');
        exit;
    }

    error_log('[signup.handler] PDOException: ' . $e->getMessage());
    http_response_code(500);
    exit('Server error.');
}

unset($_SESSION['signup_errors'], $_SESSION['signup_old']);
header('Location: /pages/Dashboard/index.php');
exit;
