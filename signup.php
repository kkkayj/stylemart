<?php
// register.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Basic validation
    $errors = [];
    if (!$username || !$email || !$password) $errors[] = "All fields required.";
    if ($password !== $password_confirm) $errors[] = "Passwords do not match.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";

    if (empty($errors)) {
        // Check unique
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Username or email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
            $stmt->execute([$username, $email, $hash]);
            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>
<!-- Simple registration form -->
<!doctype html>
<html><head><meta charset="utf-8"><title>Register</title></head>
<body>
  <h2>Register</h2>
  <?php if (!empty($errors)) foreach ($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
  <form method="post">
    <label>Username: <input name="username" required></label><br>
    <label>Email: <input name="email" type="email" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <label>Confirm: <input name="password_confirm" type="password" required></label><br>
    <button type="submit">Register</button>
  </form>
</body></html>