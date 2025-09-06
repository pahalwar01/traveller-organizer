<?php
session_start();
include_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit(); }

// Add package
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package'])) {
    $name = trim($_POST['package_name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $days = intval($_POST['days']);
    $nights = intval($_POST['nights']);
    $stmt = mysqli_prepare($conn, "INSERT INTO packages (package_name, description, price, days, nights) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssdii", $name, $desc, $price, $days, $nights);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: manage_packages.php");
    exit();
}

// Update package
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_package'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['package_name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $days = intval($_POST['days']);
    $nights = intval($_POST['nights']);
    $stmt = mysqli_prepare($conn, "UPDATE packages SET package_name=?, description=?, price=?, days=?, nights=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssdiii", $name, $desc, $price, $days, $nights, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: manage_packages.php");
    exit();
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM packages WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: manage_packages.php");
    exit();
}

// Edit fetch
$edit_package = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM packages WHERE id = $id");
    $edit_package = mysqli_fetch_assoc($res);
}

// list packages
$pack_result = mysqli_query($conn, "SELECT * FROM packages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Manage Packages</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-4">
  <a class="btn btn-secondary mb-3" href="admin_dashboard.php">&larr; Back to Dashboard</a>
  <div class="card mb-3">
    <div class="card-body">
      <h5><?php echo $edit_package ? 'Edit Package' : 'Add Package'; ?></h5>
      <form method="post">
        <input type="hidden" name="id" value="<?php echo $edit_package['id'] ?? ''; ?>">
        <input class="form-control mb-2" name="package_name" placeholder="Package Name" value="<?php echo $edit_package['package_name'] ?? ''; ?>" required>
        <textarea class="form-control mb-2" name="description" placeholder="Description" required><?php echo $edit_package['description'] ?? ''; ?></textarea>
        <input class="form-control mb-2" name="price" type="number" step="0.01" placeholder="Price" value="<?php echo $edit_package['price'] ?? ''; ?>" required>
        <input class="form-control mb-2" name="days" type="number" placeholder="Days" value="<?php echo $edit_package['days'] ?? ''; ?>" required>
        <input class="form-control mb-2" name="nights" type="number" placeholder="Nights" value="<?php echo $edit_package['nights'] ?? ''; ?>" required>
        <?php if ($edit_package): ?>
          <button class="btn btn-primary" name="update_package">Update Package</button>
          <a class="btn btn-secondary" href="manage_packages.php">Cancel</a>
        <?php else: ?>
          <button class="btn btn-success" name="add_package">Add Package</button>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h5>All Packages</h5>
      <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Days</th><th>Nights</th><th>Action</th></tr></thead>
        <tbody>
          <?php while ($p = mysqli_fetch_assoc($pack_result)): ?>
            <tr>
              <td><?php echo $p['id']; ?></td>
              <td><?php echo htmlspecialchars($p['package_name']); ?></td>
              <td>₹<?php echo $p['price']; ?></td>
              <td><?php echo $p['days']; ?></td>
              <td><?php echo $p['nights']; ?></td>
              <td>
                <a class="btn btn-sm btn-primary" href="manage_packages.php?edit=<?php echo $p['id']; ?>">Edit</a>
                <a class="btn btn-sm btn-danger" href="manage_packages.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('Delete package?')">Delete</a>
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
