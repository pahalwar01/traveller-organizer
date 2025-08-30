<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$adminName = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #ff9800;
            padding: 15px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #fff;
        }
        .sidebar {
            width: 220px;
            background: #1f1f1f;
            position: fixed;
            top: 0;
            bottom: 0;
            padding-top: 60px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 14px;
            text-decoration: none;
            border-bottom: 1px solid #333;
        }
        .sidebar a:hover {
            background: #ff9800;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
        }
        h2 {
            color: #ff9800;
        }
        .card {
            background: #1f1f1f;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 0 8px rgba(0,0,0,0.5);
        }
        .logout-btn {
            background: #e53935;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: #c62828;
        }
    </style>
</head>
<body>

<div class="header">
    🚀 Admin Dashboard - Welcome <?php echo $adminName; ?>
</div>

<div class="sidebar">
    <a href="admin_dashboard.php">🏠 Dashboard</a>
    <a href="manage_packages.php">📦 Manage Travel Packages</a>
    <a href="manage_cars.php">🚗 Manage Car Bookings</a>
    <a href="manage_trips.php">🗺 Manage Trips</a>
    <a href="manage_users.php">👤 Manage Users</a>
    <a href="admin_logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h2>Dashboard Overview</h2>

    <div class="card">
        <h3>📦 Travel Packages</h3>
        <p>Add, edit, delete and view all travel packages.</p>
    </div>

    <div class="card">
        <h3>🚗 Car Bookings</h3>
        <p>View and manage all user car bookings.</p>
    </div>

    <div class="card">
        <h3>🗺 Trips</h3>
        <p>Manage trips created by users.</p>
    </div>

    <div class="card">
        <h3>👤 Users</h3>
        <p>View and manage all registered users.</p>
    </div>

    <form method="post" action="admin_logout.php">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

</body>
</html>
