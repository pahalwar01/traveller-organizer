<?php
session_start();
include_once __DIR__ . '/../db.php';
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = mysqli_prepare($conn, "SELECT id, name, password FROM admins WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $hash);
    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $hash)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            header("Location: admin_dashboard.php"); exit();
        } else $msg = "Invalid password.";
    } else $msg = "No admin found.";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto" style="max-width:420px">
    <div class="card-body">
      <h4>Admin Login</h4>
      <?php if ($msg) echo "<div class='alert alert-warning'>$msg</div>"; ?>
      <form method="post">
        <input class="form-control mb-2" name="email" placeholder="Email" type="email" required>
        <input class="form-control mb-2" name="password" placeholder="Password" type="password" required>
        <button class="btn btn-primary w-100">Login</button>
      </form>
      <p class="mt-3">Not registered? <a href="admin_register.php">Register</a></p>
    </div>
  </div>
</div>
</body>
</html>
