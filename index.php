<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traveller Organizer</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('images/travel-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .overlay {
            background: rgba(0, 0, 0, 0);
            padding: 80px 20px;
            min-height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn {
            background: #00bcd4;
            color: white;
            padding: 12px 25px 12px 25px;
            display: inline-block;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            margin: 10px;
            transition: 0.3s ease;
        }

        .btn:hover {
            background: #0097a7;
        }

        .btn-red {
            background: #e53935;
        }

        .btn-red:hover {
            background: #c62828;
        }

        @media(max-width: 600px) {
            h1 {
                font-size: 28px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="overlay">
    <div class="container">
        <h1>Welcome to Traveller Organizer</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Hello, <strong><?php echo $_SESSION['user_name']; ?></strong>! Plan your next trip with ease.</p>
            <a class="btn" href="user/profile.php">My Profile</a>
            <a class="btn" href="trip_add.php">Add New Trip</a>
            <a class="btn btn-red" href="logout.php">Logout</a>
        <?php else: ?>
            <p>Your gateway to stress-free travel planning.</p>
            <a class="btn" href="login.php">Login</a>
            <a class="btn" href="register.php">Register</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
