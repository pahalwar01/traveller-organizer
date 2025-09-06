<?php
session_start();
include_once __DIR__ . '/../db.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $msg = "Invalid email.";
    else {
        // duplicate check
        $stmt = mysqli_prepare($conn, "SELECT id FROM admins WHERE email=?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) $msg = "Email already used.";
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = mysqli_prepare($conn, "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($ins, "sss", $name, $email, $hash);
            if (mysqli_stmt_execute($ins)) {
                header("Location: admin_login.php");
                exit();
            } else $msg = "Registration failed.";
            mysqli_stmt_close($ins);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Admin Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto" style="max-width:480px">
    <div class="card-body">
      <h4>Admin Register</h4>
      <?php if ($msg) echo "<div class='alert alert-warning'>$msg</div>"; ?>
      <form method="post">
        <input class="form-control mb-2" name="name" placeholder="Full name" required>
        <input class="form-control mb-2" name="email" placeholder="Email" type="email" required>
        <input class="form-control mb-2" name="password" placeholder="Password" type="password" required>
        <button class="btn btn-primary w-100">Register</button>
      </form>
      <p class="mt-3">Already admin? <a href="admin_login.php">Login</a></p>
    </div>
  </div>
</div>
</body>
</html>
