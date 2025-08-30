<?php
session_start();
include('db.php');
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
    <br>
    <?php
// Fetch all packages added by admin
$packages_sql = "SELECT * FROM packages";
$packages_result = mysqli_query($conn, $packages_sql);
?>

<div class="container mt-5">
    <h3 class="text-center mb-4">🌍 Available Trips</h3> <hr><hr>
    <div class="row">
        <?php while ($package = mysqli_fetch_assoc($packages_result)) { ?>
            <div class="col-md-4">
                <div class="card shadow-lg mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $package['title']; ?></h5>
                        <p><strong>Days:</strong> <?php echo $package['days']; ?> | 
                           <strong>Nights:</strong> <?php echo $package['nights']; ?></p>
                        <p><strong>Price:</strong> ₹<?php echo $package['price']; ?></p>
                        <p><?php echo $package['description']; ?></p>
                        <form method="post" action="/user/book_package.php">
                            <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                            <button type="submit" class="btn btn-success">Book Now</button>
                        </form>
                    </div>
                </div>
            </div><hr>
        <?php } ?>
    </div>
</div>

</div>




</body>
</html>
