<?php
session_start();
include '../db.php'; // yeh file me aapke DB connection ka code hai

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check duplicate email
    $check = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "⚠️ Email already registered!";
    } else {
        $sql = "INSERT INTO admins (name, email, password) VALUES ('$name','$email','$password')";
        if (mysqli_query($conn, $sql)) {
            $message = "✅ Admin registered successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #222 url('images/admin-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: rgba(0,0,0,0.7);
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type=text], input[type=email], input[type=password] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
        }
        .btn {
            background: #00bcd4;
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
            background: #0097a7;
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
    <h2>Admin Registration</h2>
    <?php if($message != "") echo "<p class='message'>$message</p>"; ?>
    <form method="post" action="">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Register</button>
    </form>
    <div class="link">
        <p>Already an admin? <a href="admin_login.php">Login Here</a></p>
    </div>
</div>

</body>
</html>
