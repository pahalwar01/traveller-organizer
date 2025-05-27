<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "Email already registered. Try logging in.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $message = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Traveller Organizer</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 50px;
            background: url('images/travel-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .form-box {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 0 10px #ccc;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .btn {
            background: #28a745;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            margin-top: 10px;
        }
        .msg {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Create an Account</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email Address" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" class="btn">Register</button>
    </form>
    <p class="msg"><?php echo $message; ?></p>
    <p>Already have an account? <a href="login.php" style="color: yellow;">Login here</a>.</p>
</div>

</body>
</html>
