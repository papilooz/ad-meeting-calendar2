<?php
require_once BASE_PATH . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Detect if running inside Docker
$isDocker = file_exists('/.dockerenv');

// Final config based on environment
return [
    'pgHost' => $isDocker ? 'ad-meeting-calendar-postgresql' : ($_ENV['PG_HOST'] ?? 'localhost'),
    'pgPort' => $isDocker ? '5432' : ($_ENV['PG_PORT'] ?? '5112'),
    'pgDB'   => $_ENV['PG_DB'] ?? 'newdatabase',
    'pgUser' => $_ENV['PG_USER'] ?? 'user',
    'pgPass' => $_ENV['PG_PASS'] ?? 'password',

    'mongoUri' => $isDocker
        ? 'mongodb://ad-meeting-calendar-mongodb:27017'
        : ($_ENV['MONGO_URI'] ?? 'mongodb://localhost:27111'),
    'mongoDB'  => $_ENV['MONGO_DB'] ?? 'newdatabase',
];
