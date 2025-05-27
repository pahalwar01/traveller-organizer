<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            header("Location: index.php");
            exit();
        } else {
            $message = "Incorrect password. Try again.";
        }
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - RK Traveller Organizer</title>
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
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .btn {
            background: #007BFF;
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
    <h2>Login</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email Address" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" class="btn">Login</button>
    </form>
    <p class="msg"><?php echo $message; ?></p>
    <p>Don't have an account? <a href="register.php" style="color: yellow;">Register here</a>.</p>
</div>

</body>
</html>
