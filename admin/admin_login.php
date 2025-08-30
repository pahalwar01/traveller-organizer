<?php
session_start();
include '../db.php'; // Database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch admin by email
    $sql = "SELECT * FROM admins WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "❌ Invalid password!";
        }
    } else {
        $message = "⚠️ No account found with this email!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212 url('images/admin-login-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: rgba(0,0,0,0.75);
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
        }
        .btn {
            background: #ff9800;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background: #e68900;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            color: #ffd600;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .link a { color: #00e5ff; }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Login</h2>
    <?php if($message != "") echo "<p class='message'>$message</p>"; ?>
    <form method="post" action="">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
    </form>
    <div class="link">
        <p>Not registered? <a href="admin_register.php">Register Here</a></p>
    </div>
</div>

</body>
</html>
