<?php
session_start();
include_once __DIR__ . '/../db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $conf = $_POST['confirm_password'];

    if ($new !== $conf) { $msg = "New passwords do not match."; }
    else {
        $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hash);
        if (mysqli_stmt_fetch($stmt) && password_verify($old, $hash)) {
            mysqli_stmt_close($stmt);
            $nh = password_hash($new, PASSWORD_DEFAULT);
            $up = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($up, "si", $nh, $user_id);
            if (mysqli_stmt_execute($up)) { $msg = "Password updated successfully."; }
            else { $msg = "Error updating password."; }
            mysqli_stmt_close($up);
        } else {
            $msg = "Old password incorrect.";
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Change Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto" style="max-width:480px">
    <div class="card-body">
      <h4>Change Password</h4>
      <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
      <form method="post">
        <input class="form-control mb-2" name="old_password" type="password" placeholder="Old password" required>
        <input class="form-control mb-2" name="new_password" type="password" placeholder="New password" required>
        <input class="form-control mb-2" name="confirm_password" type="password" placeholder="Confirm new password" required>
        <button class="btn btn-warning w-100">Update Password</button>
      </form>
      <a href="profile.php" class="btn btn-secondary mt-2 w-100">Back to Profile</a>
    </div>
  </div>
</div>
</body>
</html>
