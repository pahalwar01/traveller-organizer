<?php
session_start();
include("db.php");

// Fetch all active packages
$packages = mysqli_query($conn, "SELECT * FROM packages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Traveller Organizer - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">🌍 Traveller Organizer</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id'])) { ?>
          <li class="nav-item"><a class="nav-link" href="user/profile.php">👤 Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="user/logout.php">🚪 Logout</a></li>
        <?php } else { ?>
          <li class="nav-item"><a class="nav-link" href="user/login.php">🔑 Login</a></li>
          <li class="nav-item"><a class="nav-link" href="user/register.php">📝 Register</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="text-center mb-4">
    <h2 class="fw-bold">🌟 Explore Our Travel Packages</h2>
    <p class="text-muted">Choose your next adventure with Traveller Organizer</p>
  </div>
  
  <div class="row">
    <?php while($pkg = mysqli_fetch_assoc($packages)) { ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-lg h-100">
          <img src="uploads/packages/<?php echo $pkg['image'] ?? 'default.jpg'; ?>" class="card-img-top" height="200" style="object-fit:cover;">
          <div class="card-body">
            <h5 class="card-title"><?php echo $pkg['package_name']; ?></h5>
            <p class="card-text"><?php echo substr($pkg['description'], 0, 100) . "..."; ?></p>
            <p><strong>🕒 <?php echo $pkg['days']; ?> Days / <?php echo $pkg['nights']; ?> Nights</strong></p>
            <p><strong>💰 Price: ₹<?php echo $pkg['price']; ?></strong></p>
            <?php if(isset($_SESSION['user_id'])) { ?>
              <a href="user/book_package.php?package_id=<?php echo $pkg['id']; ?>" class="btn btn-success">📦 Book Now</a>
            <?php } else { ?>
              <a href="user/login.php" class="btn btn-primary">🔑 Login to Book</a>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
</body>
</html>