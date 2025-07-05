<?php
define('BASE_PATH', realpath(__DIR__));
define('UTILS_PATH', BASE_PATH . '/utils/');
define('VENDOR_PATH', BASE_PATH . '/vendor/'); // <-- corrected
define('HANDLERS_PATH', BASE_PATH . '/handlers/');

chdir(BASE_PATH);