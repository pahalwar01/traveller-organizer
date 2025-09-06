<?php
session_start();
include_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit(); }

$user_count = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$package_count = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM packages"))['total'];
$booking_count = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM package_bookings"))['total'];

$latest_users = mysqli_query($conn, "SELECT id, name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$latest_bookings = mysqli_query($conn, "
    SELECT pb.id, u.name AS user_name, p.package_name, pb.booking_date, pb.status
    FROM package_bookings pb
    JOIN users u ON pb.user_id = u.id
    JOIN packages p ON pb.package_id = p.id
    ORDER BY pb.booking_date DESC LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Admin Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>.card-stat { cursor:pointer; }</style>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Admin - <?php echo htmlspecialchars($_SESSION['admin_name']); ?></a>
    <div>
      <a class="btn btn-outline-light" href="admin_logout.php">Logout</a>
    </div>
  </div>
</nav>
<div class="container py-4">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card card-stat p-3" onclick="location.href='manage_users.php'">
        <h5>Total Users</h5><h2><?php echo $user_count; ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-stat p-3" onclick="location.href='manage_packages.php'">
        <h5>Total Packages</h5><h2><?php echo $package_count; ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-stat p-3" onclick="location.href='manage_bookings.php'">
        <h5>Total Bookings</h5><h2><?php echo $booking_count; ?></h2>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card p-3">
        <h6>Latest Users</h6>
        <ul class="list-group list-group-flush">
          <?php while($u = mysqli_fetch_assoc($latest_users)): ?>
            <li class="list-group-item"><?php echo htmlspecialchars($u['name']); ?> — <?php echo htmlspecialchars($u['email']); ?></li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <h6>Latest Bookings</h6>
        <ul class="list-group list-group-flush">
          <?php while($b = mysqli_fetch_assoc($latest_bookings)): ?>
            <li class="list-group-item"><?php echo htmlspecialchars($b['user_name']); ?> — <?php echo htmlspecialchars($b['package_name']); ?> (<?php echo $b['status']; ?>)</li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
</body>
</html>
