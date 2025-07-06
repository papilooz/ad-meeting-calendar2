<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';

if (!isAuthenticated()) {
    header('Location: /login.php');
    exit;
}
