<?php
session_start();

require_once 'bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';

if (!isAuthenticated()) {
    header('Location: /login.php');
    exit;
}

$user = getCurrentUser();

// Connect to DB
try {
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    exit("❌ DB Error: " . $e->getMessage());
}

// Fetch all users
$stmt = $pdo->query("SELECT id, username, role, first_name, last_name FROM users ORDER BY id");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User List</title>
</head>
<body>
  <h2>👥 Users</h2>
  <table border="1" cellpadding="8">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?= htmlspecialchars($u['id']) ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p><a href="/index.php">⬅ Back to Home</a></p>
</body>
</html>
