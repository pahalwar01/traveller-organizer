<?php
session_start();
include("../db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Approve or Reject Booking
if (isset($_GET['action']) && isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == "approve") {
        mysqli_query($conn, "UPDATE package_bookings SET status='Confirmed' WHERE id='$booking_id'");
    } elseif ($action == "reject") {
        mysqli_query($conn, "UPDATE package_bookings SET status='Rejected' WHERE id='$booking_id'");
    }
    header("Location: manage_bookings.php");
    exit();
}

// ✅ Fetch All Bookings
$query = "SELECT pb.id, u.name as user_name, u.email, p.package_name, pb.status, pb.booking_date 
          FROM package_bookings pb
          JOIN users u ON pb.user_id = u.id
          JOIN packages p ON pb.package_id = p.id
          ORDER BY pb.booking_date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">📑 Manage Bookings</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>
    
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Booking Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['package_name']; ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                    if($row['status']=="Pending") echo "bg-warning";
                                    elseif($row['status']=="Confirmed") echo "bg-success";
                                    elseif($row['status']=="Rejected") echo "bg-danger";
                                    else echo "bg-secondary";
                                ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['booking_date']; ?></td>
                        <td>
                            <?php if ($row['status'] == "Pending") { ?>
                                <a href="manage_bookings.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">✅ Approve</a>
                                <a href="manage_bookings.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">❌ Reject</a>
                            <?php } else { ?>
                                <em>No action</em>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
