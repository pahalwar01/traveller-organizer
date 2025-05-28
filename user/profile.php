<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $created_at);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - Traveller Organizer</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f0f2f5;
            padding: 50px;
        }
        .profile-box {
            background: white;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .info {
            font-size: 18px;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            background: #007BFF;
            color: white;
            padding: 10px 15px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="profile-box">
    <h2>My Profile</h2>
    <div class="info"><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></div>
    <div class="info"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></div>
    <div class="info"><strong>Joined On:</strong> <?php echo date("d M, Y", strtotime($created_at)); ?></div>

    <a href="index.php" class="btn">Home</a>
    <a href="logout.php" class="btn" style="background-color: #dc3545;">Logout</a>
</div>

</body>
</html>
