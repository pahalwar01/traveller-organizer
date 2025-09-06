<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current details
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

// Update details
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Handle profile picture upload
    $profile_pic = $user['profile_pic']; // by default existing image

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/profile_pics/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic = $file_name;
        }
    }

    $update = "UPDATE users SET name='$name', email='$email', phone='$phone', profile_pic='$profile_pic' WHERE id='$user_id'";
    if (mysqli_query($conn, $update)) {
        echo "<script>alert('✅ Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('❌ Error updating profile.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-lg p-4">
    <h3 class="mb-4">✏️ Edit Profile</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="text-center mb-4">
        <?php if (!empty($user['profile_pic'])) { ?>
          <img src="../uploads/profile_pics/<?php echo $user['profile_pic']; ?>" class="rounded-circle" width="120" height="120">
        <?php } else { ?>
          <img src="https://via.placeholder.com/120" class="rounded-circle">
        <?php } ?>
      </div>
      <div class="mb-3">
        <label class="form-label">Change Profile Photo</label>
        <input type="file" name="profile_pic" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>">
      </div>
      <button type="submit" class="btn btn-success">💾 Save Changes</button>
      <a href="profile.php" class="btn btn-secondary">⬅ Back</a>
    </form>
  </div>
</div>
</body>
</html>
