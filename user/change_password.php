<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check old password
    $sql = "SELECT password FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($old_password, $row['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password='$hashed_password' WHERE id='$user_id'";
            if (mysqli_query($conn, $update_sql)) {
                $message = "<div class='alert alert-success'>Password changed successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error updating password.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>New password and confirm password do not match.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Old password is incorrect.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-white text-center">
                    <h4>Change Password</h4>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Old Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Update Password</button>
                        <a href="profile.php" class="btn btn-secondary w-100 mt-2">Back to Profile</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
