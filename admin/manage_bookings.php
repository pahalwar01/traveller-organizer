<?php
session_start();
include_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit(); }

// Approve / Reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == "approve") {
        mysqli_query($conn, "UPDATE package_bookings SET status='Confirmed' WHERE id='$booking_id'");
    } elseif ($action == "reject") {
        mysqli_query($conn, "UPDATE package_bookings SET status='Rejected' WHERE id='$booking_id'");
    } elseif ($action == "cancel") {
        mysqli_query($conn, "UPDATE package_bookings SET status='Cancelled' WHERE id='$booking_id'");
    } elseif ($action == "reject_cancel") {
        mysqli_query($conn, "UPDATE package_bookings SET status='Confirmed' WHERE id='$booking_id'");
    }
    if (isset($stmt)) { mysqli_stmt_bind_param($stmt, "i", $id); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); }
    header("Location: manage_bookings.php"); exit();
}

// Fetch bookings
$res = mysqli_query($conn, "
    SELECT pb.id, u.name AS user_name, u.email, p.package_name, pb.booking_date, pb.status
    FROM package_bookings pb
    JOIN users u ON pb.user_id = u.id
    JOIN packages p ON pb.package_id = p.id
    ORDER BY pb.booking_date DESC
");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Manage Bookings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-4">
  <a class="btn btn-secondary mb-3" href="admin_dashboard.php">&larr; Back to Dashboard</a>
  <div class="card">
    <div class="card-body">
      <h5>All Package Bookings</h5>
      <table class="table table-bordered">
        <thead><tr><th>ID</th><th>User</th><th>Email</th><th>Package</th><th>Status</th><th>Booking Date</th><th>Action</th></tr></thead>
        <tbody>
          <?php while ($b = mysqli_fetch_assoc($res)): ?>
            <tr>
              <td><?php echo $b['id']; ?></td>
              <td><?php echo htmlspecialchars($b['user_name']); ?></td>
              <td><?php echo htmlspecialchars($b['email']); ?></td>
              <td><?php echo htmlspecialchars($b['package_name']); ?></td>
              <td><?php echo htmlspecialchars($b['status']); ?></td>
              <td><?php echo $b['booking_date']; ?></td>
              <td>
                <?php if ($b['status'] == "Pending") { ?>
                    <a href="manage_bookings.php?action=approve&id=<?php echo $b['id']; ?>" class="btn btn-success btn-sm">✅ Approve</a>
                    <a href="manage_bookings.php?action=reject&id=<?php echo $b['id']; ?>" class="btn btn-danger btn-sm">❌ Reject</a>
                <?php } elseif ($b['status'] == "Cancel_Request") { ?>
                    <a href="manage_bookings.php?action=cancel&id=<?php echo $b['id']; ?>" class="btn btn-warning btn-sm">✔ Confirm Cancel</a>
                    <a href="manage_bookings.php?action=reject_cancel&id=<?php echo $b['id']; ?>" class="btn btn-info btn-sm">🚫 Reject Cancel</a>
                <?php } else { ?>
                    <em>No action</em>
                <?php } ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
