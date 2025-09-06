<?php
session_start();
include_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit(); }

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: manage_users.php"); exit();
}

$res = mysqli_query($conn, "SELECT id, name, email, phone, created_at FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-4">
  <a class="btn btn-secondary mb-3" href="admin_dashboard.php">&larr; Back to Dashboard</a>
  <div class="card">
    <div class="card-body">
      <h5>Registered Users</h5>
      <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th><th>Action</th></tr></thead>
        <tbody>
          <?php while ($u = mysqli_fetch_assoc($res)): ?>
            <tr>
              <td><?php echo $u['id']; ?></td>
              <td><?php echo htmlspecialchars($u['name']); ?></td>
              <td><?php echo htmlspecialchars($u['email']); ?></td>
              <td><?php echo htmlspecialchars($u['phone']); ?></td>
              <td><?php echo $u['created_at']; ?></td>
              <td><a class="btn btn-sm btn-danger" href="manage_users.php?delete=<?php echo $u['id']; ?>" onclick="return confirm('Delete user?')">Delete</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
