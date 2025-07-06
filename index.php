<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/bootstrap.php';

$contentView = __DIR__ . '/views/indexContent.view.php';

// Instead of just `include`, pass the variable cleanly:
require_once __DIR__ . '/layouts/layout.view.php';
