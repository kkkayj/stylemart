<?php
require_once __DIR__ . '/../auth.php';
ensure_logged_in();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Customer Home</title></head>
<body>
  <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
  <p><a href="/index.html">Continue shopping</a> | <a href="/logout.php">Logout</a></p>
</body></html>
