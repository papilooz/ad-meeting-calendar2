<?php
declare(strict_types=1);
require_once __DIR__ . '/../utils/authChecker.util.php';
if (!isset($contentView)) {
    exit('❌ $contentView not set in layout.view.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AD Meeting Calendar</title>
</head>
<body>
    <?php include __DIR__ . '/../components/componentGroup/navbar.php'; ?>

    <main style="padding: 20px;">
        <?php include $contentView; ?>
    </main>
</body>
</html>
