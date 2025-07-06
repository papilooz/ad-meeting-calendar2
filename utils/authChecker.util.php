<?php
declare(strict_types=1);

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../bootstrap.php';

// Auth check
if (!isset($_SESSION['user'])) {
    header('Location: /login.php');
    exit;
}
