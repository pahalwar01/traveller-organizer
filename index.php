<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Traveller Organizer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: url('images/travel-bg.jpg') center/cover no-repeat fixed; }
    .overlay { background: rgba(0,0,0,0.55); min-height:100vh; padding:60px 0; color:#fff; }
    .card { border-radius:12px; }
  </style>
</head>
<body>
  <div class="overlay">
    <div class="container text-center">
      <div class="card p-5 mx-auto" style="max-width:700px; background:rgba(255,255,255,0.05); color:#fff;">
        <h1>Traveller Organizer</h1>
        <p class="lead">Plan trips, book packages and cars — simple & fast.</p>

        <?php if (isset($_SESSION['user_id'])): ?>
          <p>Hello <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
          <a class="btn btn-primary" href="user/profile.php">My Profile</a><br>
          <a class="btn btn-light" href="user/logout.php">Logout</a>
        <?php else: ?>
          <a class="btn btn-success" href="user/register.php">Register</a><br>
          <a class="btn btn-primary" href="user/login.php">Login</a>
        <?php endif; ?>

        <hr style="background:rgba(255,255,255,0.1)">

        <a class="btn btn-outline-warning" href="admin/admin_login.php">Admin Login</a>
      </div>
    </div>
  </div>
</body>
</html>
