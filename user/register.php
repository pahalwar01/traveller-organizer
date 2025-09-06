<?php
session_start();
include_once __DIR__ . '/../db.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email.";
    } elseif (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters.";
    } else {
        // check duplicate
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "ssss", $name, $email, $phone, $hash);
            if (mysqli_stmt_execute($insert)) {
                header("Location: login.php");
                exit();
            } else {
                $msg = "Registration failed. Try again.";
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - Traveller Organizer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto" style="max-width:480px;">
    <div class="card-body">
      <h4 class="card-title mb-3">Create Account</h4>
      <?php if ($msg) echo "<div class='alert alert-warning'>{$msg}</div>"; ?>
      <form method="post">
        <input class="form-control mb-2" name="name" placeholder="Full name" required>
        <input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
        <input class="form-control mb-2" name="phone" placeholder="Phone (optional)">
        <input class="form-control mb-2" name="password" type="password" placeholder="Password" required>
        <button class="btn btn-success w-100">Register</button>
      </form>
      <p class="mt-3">Already have account? <a href="login.php">Login</a></p>
    </div>
  </div>
</div>
</body>
</html>
