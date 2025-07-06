<?php
require_once VENDOR_PATH . 'autoload.php';

// Load .env variables from BASE_PATH (your project root)
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Check if running inside Docker
$isDocker = file_exists('/.dockerenv');

// Return environment config
return [
    'pg_host'   => $_ENV['PG_HOST']   ?? ($isDocker ? 'postgresql' : 'localhost'),
    'pg_port'   => $_ENV['PG_PORT']   ?? ($isDocker ? '5432' : '5112'),
    'pg_db'     => $_ENV['PG_DB']     ?? 'default_db',
    'pg_user'   => $_ENV['PG_USER']   ?? 'default_user',
    'pg_pass'   => $_ENV['PG_PASS']   ?? 'default_pass',
    'mongo_uri' => $_ENV['MONGO_URI'] ?? ($isDocker
                        ? 'mongodb://root:rootPassword@mongodb:27017'
                        : 'mongodb://root:rootPassword@localhost:27111'),
    'mongo_db'  => $_ENV['MONGO_DB']  ?? 'default_mongo_db',
];
