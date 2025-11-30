
<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        echo "✅ Password updated successfully! <a href='login.html'>Login now</a>";
    } else {
        echo "❌ Error updating password.";
    }
} else {
    // Show reset form
    $email = $_GET['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Stylemart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow" style="max-width: 400px; width:100%;">
    <h3 class="text-center">Reset Password</h3>
    <form action="reset_password.php" method="post">
      <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
        <label for="password">New Password</label>
      </div>
      <button class="btn btn-success w-100" type="submit">Update Password</button>
    </form>
  </div>
</div>

</body>
</html>
