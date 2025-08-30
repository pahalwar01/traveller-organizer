<?php
session_start();
include('../db.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// User details
$user_sql = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Profile picture update
if(isset($_POST['upload_pic'])){
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;
        if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)){
            $update_sql = "UPDATE users SET profile_pic='$target_file' WHERE id='$user_id'";
            mysqli_query($conn, $update_sql);
            $user['profile_pic'] = $target_file;
        }
    }
}

// Package bookings
$package_sql = "SELECT pb.id, p.title, p.price, pb.status, pb.created_at 
                FROM package_bookings pb 
                JOIN packages p ON pb.package_id = p.id 
                WHERE pb.user_id = '$user_id' 
                ORDER BY pb.created_at DESC";
$package_result = mysqli_query($conn, $package_sql);

// Car bookings
$car_sql = "SELECT cb.id, cb.car_model, cb.start_date, cb.end_date, cb.status, cb.created_at 
            FROM car_bookings cb 
            WHERE cb.user_id = '$user_id' 
            ORDER BY cb.created_at DESC";
$car_result = mysqli_query($conn, $car_sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .card { border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .profile-header {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            color: white; padding: 30px; border-radius: 15px; text-align: center;
        }
        .profile-header h2 { margin-bottom: 10px; }
        .profile-pic {
            width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
            border: 4px solid #fff; margin-bottom: 10px;
        }
        .section-title { font-size: 1.3rem; margin-bottom: 15px; color: #007bff; }
        .status-badge { padding: 5px 10px; border-radius: 12px; font-size: 0.85rem; }
        .status-pending { background: orange; color: white; }
        .status-confirmed { background: green; color: white; }
        .status-cancelled { background: red; color: white; }
        .btn-cancel { background: #e67e22; color: #fff; padding: 6px 12px; font-size: 13px; }
        .packages-list { margin-top: 15px; }
        .package-card {
            background: #f9f9f9; border: 1px solid #ddd;
            padding: 15px; margin-bottom: 12px; border-radius: 8px;
        }
        .package-card h3 { margin: 0; color: #2c3e50; }
        .package-card p { margin: 5px 0; color: #555; }
    </style>
</head>
<body>
<div class="container mt-4">

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Traveller Organizer</a>
        <div class="d-flex">
            <a href="../index.php" class="btn btn-outline-light me-2">🏠 Back to Homepage</a>
            <a href="../logout.php" class="btn btn-danger">🚪 Logout</a>
        </div>
    </div>
    </nav>


    <!-- Profile Header -->
    <div class="profile-header">
        <?php if(!empty($user['profile_pic'])) { ?>
            <img src="<?php echo $user['profile_pic']; ?>" class="profile-pic" alt="Profile Picture">
        <?php } else { ?>
            <img src="https://via.placeholder.com/120x120.png?text=No+Image" class="profile-pic" alt="Profile Picture">
        <?php } ?>
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?> | Phone: <?php echo htmlspecialchars($user['phone']); ?></p>

        <!-- Upload Form -->
        <form method="post" enctype="multipart/form-data" class="mt-2">
            <input type="file" name="profile_pic" required>
            <button type="submit" name="upload_pic" class="btn btn-light btn-sm">Upload</button>
        </form>
    </div>

    <!-- User Info 
    <div class="card p-4 mt-3">
        <h4 class="section-title">Your Profile Details</h4>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>-->

    <!-- Change Password Card -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            Change Password
        </div>
        <div class="card-body">
            <a href="change_password.php" class="btn btn-warning">Change Password</a>
        </div>
    </div>


    <!-- Package Bookings -->
    <div class="card p-4">
        <h4 class="section-title">Your Package Bookings</h4>
        <?php if(mysqli_num_rows($package_result)> 0) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Booked On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($pb = mysqli_fetch_assoc($package_result)) { ?>
                        <tr>
                            <td><?php echo $pb['title']; ?></td>
                            <td>₹<?php echo $pb['price']; ?></td>
                            <td><span class="status-badge status-<?php echo strtolower($pb['status']); ?>"><?php echo ucfirst($pb['status']); ?></span></td>
                            <td><?php echo $pb['created_at']; ?></td>
                            <td><a href="cancel_booking.php?id=<?php echo $pb['id']; ?>" 
                               class="btn btn-cancel"
                               onclick="return confirm('Are you sure you want to cancel this booking?');">
                               ❌ Cancel Booking
                            </a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No package bookings yet.</p>
        <?php } ?>
    </div>

    <!-- Car Bookings -->
    <div class="card p-4">
        <h4 class="section-title">Your Car Bookings</h4>
        <?php if(mysqli_num_rows($car_result) > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Car Model</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Booked On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($cb = mysqli_fetch_assoc($car_result)) { ?>
                        <tr>
                            <td><?php echo $cb['car_model']; ?></td>
                            <td><?php echo $cb['start_date']; ?></td>
                            <td><?php echo $cb['end_date']; ?></td>
                            <td><span class="status-badge status-<?php echo strtolower($cb['status']); ?>"><?php echo ucfirst($cb['status']); ?></span></td>
                            <td><?php echo $cb['created_at']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No car bookings yet.</p>
        <?php } ?>
    </div>
</div>
</body>
</html>
