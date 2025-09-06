<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../db.php';

// Total Counts
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$package_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM packages"))['total'];
$booking_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM package_bookings"))['total'];

// All packages added by admin
$result = mysqli_query($conn, "SELECT * FROM packages ORDER BY id DESC");

// Latest 5 Users
$latest_users = mysqli_query($conn, "SELECT id, name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");

// Latest 5 Car Bookings
$latest_car_bookings = mysqli_query($conn, "
    SELECT b.id, u.name AS user_name, b.car_type, b.start_date, b.end_date, b.status 
    FROM car_bookings b 
    JOIN users u ON b.user_id = u.id 
    ORDER BY b.id DESC LIMIT 5
");


// Latest 5 Package Bookings
$latest_package_bookings = mysqli_query($conn, "SELECT pb.id, u.name, p.title, pb.date, pb.created_at, pb.status 
                                                FROM package_bookings pb
                                                JOIN users u ON pb.user_id = u.id
                                                JOIN packages p ON pb.package_id = p.id
                                                ORDER BY pb.date DESC LIMIT 5");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: #fff;
        }
        .header {
            background: #ff9800;
            padding: 20px;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            padding: 40px;
        }
        .card {
            background: #1f1f1f;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
        }
        .card:hover {
            transform: translateY(-5px);
            background: #2a2a2a;
        }
        .card i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #ff9800;
        }
        .card h2 {
            margin: 0;
            font-size: 36px;
            color: #ff9800;
        }
        .card p {
            margin-top: 8px;
            font-size: 18px;
            color: #ccc;
        }
        .logout-btn {
            display: inline-block;
            margin: 20px;
            padding: 10px 15px;
            background: #e53935;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        .logout-btn:hover {
            background: #c62828;
        }
        .section {
            margin: 30px;
            background: #1f1f1f;
            padding: 20px;
            border-radius: 12px;
        }
        .section h3 {
            margin: 0 0 15px;
            font-size: 22px;
            color: #ff9800;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }
        table th {
            background: #2a2a2a;
        }
        table tr:hover {
            background: #333;
        }
    </style>
</head>
<body>

<div class="header">⚡ Admin Dashboard</div>

<!-- Top Stats -->
<div class="dashboard">
    <div class="card" onclick="window.location.href='manage_users.php'">
        <i class="fas fa-users"></i>
        <h2><?php echo $user_count; ?></h2>
        <p>Total Users</p>
    </div>

    <div class="card" onclick="window.location.href='manage_packages.php'">
        <i class="fas fa-suitcase-rolling"></i>
        <h2><?php echo $package_count; ?></h2>
        <p>Total Packages</p>
    </div>

    <div class="card" onclick="window.location.href='manage_bookings.php'">
        <i class="fas fa-car"></i>
        <h2><?php echo $booking_count; ?></h2>
        <p>Total Package Bookings</p>
    </div>
</div>

<!-- Latest Users -->
<div class="section">
    <h3><i class="fas fa-user-clock"></i> Latest 5 Users</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Joined</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($latest_users)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<!-- Latest Car Bookings -->
<div class="section">
    <h3><i class="fas fa-car-side"></i> Latest 5 Car Bookings</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Car Model</th>
            <th>Booking Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($latest_car_bookings)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['car_model']); ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<!-- Latest Package Bookings -->
<div class="section">
    <h3><i class="fas fa-suitcase"></i> Latest 5 Package Bookings</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Package</th>
            <th>Booked On Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($latest_package_bookings)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<a href="admin_logout.php" class="logout-btn">🚪 Logout</a>

</body>
</html>
