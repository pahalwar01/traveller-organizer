<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Traveller Organizer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            text-align: center;
            padding: 50px;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
            max-width: 500px;
            margin: auto;
        }
        a {
            text-decoration: none;
            color: #007BFF;
            margin: 0 10px;
        }
        .btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Welcome to Traveller Organizer</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Hello, <strong><?php echo $_SESSION['user_name']; ?></strong>!</p>
        <p><a class="btn" href="profile.php">My Profile</a></p>
        <p><a href="logout.php">Logout</a></p>
    <?php else: ?>
        <p>Please <a href="login.php">Login</a> or <a href="register.php">Register</a> to get started.</p>
    <?php endif; ?>
</div>

</body>
</html>
