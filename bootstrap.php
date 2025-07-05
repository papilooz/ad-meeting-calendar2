<?php
define('BASE_PATH', realpath(__DIR__));
define('UTILS_PATH', BASE_PATH . '/utils/');
define('VENDOR_PATH', BASE_PATH . '/vendor/');
define('HANDLERS_PATH', BASE_PATH . '/handlers/');
define('STATIC_DATA_PATH', BASE_PATH . '/staticDatas/dummies');

chdir(BASE_PATH);

// Load Composer autoload
require_once VENDOR_PATH . 'autoload.php';

// Load environment variables using vlucas/phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Define database connection variables from environment
$dsn = sprintf(
    'pgsql:host=%s;port=%s;dbname=%s',
    $_ENV['PG_HOST'] ?? 'localhost',
    $_ENV['PG_PORT'] ?? '5432',
    $_ENV['PG_DB'] ?? 'database'
);

$user = $_ENV['PG_USER'] ?? 'user';
$password = $_ENV['PG_PASS'] ?? 'password';
