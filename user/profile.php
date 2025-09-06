<?php
session_start();
include_once __DIR__ . '/../db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
// Fetch fresh user
$stmt = mysqli_prepare($conn, "SELECT id, name, email, phone, profile_pic, created_at FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

// Handle profile edit
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $u = mysqli_prepare($conn, "UPDATE users SET name=?, phone=? WHERE id=?");
    mysqli_stmt_bind_param($u, "ssi", $name, $phone, $user_id);
    if (mysqli_stmt_execute($u)) {
        $msg = "Profile updated.";
        $_SESSION['user_name'] = $name;
        $user['name'] = $name; $user['phone'] = $phone;
    } else { $msg = "Update failed."; }
    mysqli_stmt_close($u);
}

// Handle profile pic upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_pic'])) {
    if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] === 0) {
        $target_dir = __DIR__ . "/uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $newname = time() . "_" . rand(1000,9999) . "." . $ext;
        $dest = $target_dir . $newname;
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $dest)) {
            // store relative path for web
            $rel = "user/uploads/" . $newname;
            $u = mysqli_prepare($conn, "UPDATE users SET profile_pic=? WHERE id=?");
            mysqli_stmt_bind_param($u, "si", $rel, $user_id);
            if (mysqli_stmt_execute($u)) {
                $msg = "Profile picture updated.";
                $user['profile_pic'] = $rel;
            } else $msg = "DB error saving image.";
            mysqli_stmt_close($u);
        } else $msg = "Upload error.";
    } else $msg = "No file selected.";
}

// Booked packages
$booked = mysqli_query($conn, "
    SELECT pb.id AS booking_id, p.id AS package_id, p.package_name, p.description, p.days, p.nights, pb.booking_date, pb.status
    FROM package_bookings pb
    JOIN packages p ON pb.package_id = p.id
    WHERE pb.user_id = $user_id
    ORDER BY pb.booking_date DESC
");

// Available packages (to book)
$packages = mysqli_query($conn, "SELECT * FROM packages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Profile - Traveller Organizer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.profile-pic { width:100px; height:100px; object-fit:cover; border-radius:50%; }
.card { border-radius:12px; }
.section-title { color:#007bff; font-weight:600; }
</style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="../index.php">Traveller Organizer</a>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">🏠<?php echo htmlspecialchars($user['name']); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="btn btn-outline-light me-2" href="edit_profile.php">✏️ Edit Profile</a>
            </li>
            <li>
              <a class="btn btn-outline-light me-2" href="../index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-danger" href="logout.php">🚪 Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- <div>
      <a class="btn btn-outline-light me-2" href="../index.php">Home</a>
      <a class="btn btn-danger" href="logout.php">Logout</a>
    </div> -->
  </div>
</nav>
<div class="container py-4">
  <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
  <div class="row">
    <div class="col-md-4">
      <div class="card p-3 mb-3 text-center">
        <?php if (!empty($user['profile_pic'])): ?>
          <img src="<?php echo htmlspecialchars('../' . $user['profile_pic']); ?>" class="profile-pic mb-2" alt="pic">
        <?php else: ?>
          <img src="https://via.placeholder.com/100" class="profile-pic mb-2" alt="no-pic">
        <?php endif; ?>
        <h5><?php echo htmlspecialchars($user['name']); ?></h5>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
        <p><?php echo htmlspecialchars($user['phone']); ?></p>

        <form method="post" enctype="multipart/form-data" class="mt-2">
          <input type="file" name="profile_pic" class="form-control mb-2">
          <button class="btn btn-sm btn-secondary w-100" name="upload_pic">Upload Photo</button>
        </form>
      </div>

      <div class="card p-3">
        <h6 class="section-title">Edit Profile</h6>
        <form method="post">
          <input class="form-control mb-2" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
          <input class="form-control mb-2" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
          <button class="btn btn-primary w-100" name="update_profile">Save</button>
        </form>
        <a class="btn btn-warning w-100 mt-2" href="change_password.php">Change Password</a>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card p-3 mb-3">
        <h6 class="section-title">Your Booked Packages</h6>
        <?php if (mysqli_num_rows($booked) > 0): ?>
          <?php while ($b = mysqli_fetch_assoc($booked)): ?>
            <div class="border rounded p-2 mb-2">
              <div class="d-flex justify-content-between">
                <div>
                  <h6><?php echo htmlspecialchars($b['package_name']); ?></h6>
                  <p class="mb-1"><?php echo htmlspecialchars($b['description']); ?></p>
                  <small><?php echo (int)$b['days']; ?> days • <?php echo (int)$b['nights']; ?> nights</small>
                </div>
                <div class="text-end">
                  <div><small><?php echo htmlspecialchars($b['booking_date']); ?></small></div>
                  <div>
                    <span class="badge <?php
                      $s = $b['status'];
                      echo ($s=='Pending') ? 'bg-warning text-dark' : (($s=='Confirmed') ? 'bg-success' : (($s=='Cancelled' || $s=='Rejected') ? 'bg-danger' : 'bg-secondary'));
                    ?>"><?php echo htmlspecialchars($s = $b['status']); ?></span>
                  </div>
                  <?php if ($b['status'] === 'Pending' || $b['status'] === 'Confirmed'): ?>
                    <a class="btn btn-sm btn-danger mt-2" href="cancel_booking.php?id=<?php echo $b['booking_id']; ?>" onclick="return confirm('Cancel this booking?');">Cancel</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No bookings yet.</p>
        <?php endif; ?>
      </div>

      <div class="card p-3">
        <h6 class="section-title">Available Trips</h6>
        <div class="row">
          <?php while ($p = mysqli_fetch_assoc($packages)): ?>
            <div class="col-md-12 mb-2">
              <div class="d-flex justify-content-between align-items-center border rounded p-2">
                <div>
                  <h6><?php echo htmlspecialchars($p['package_name']); ?></h6>
                  <small><?php echo (int)$p['days']; ?> days • <?php echo (int)$p['nights']; ?> nights • ₹<?php echo $p['price']; ?></small>
                  <p class="mb-0"><?php echo htmlspecialchars($p['description']); ?></p>
                </div>
                <div>
                  <form method="post" action="book_package.php">
                    <input type="hidden" name="package_id" value="<?php echo $p['id']; ?>">
                    <button class="btn btn-success">Book Now</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>
