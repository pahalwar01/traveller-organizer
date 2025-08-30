<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include '../db.php';

// ✅ Add new package
if (isset($_POST['add_package'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $days = intval($_POST['days']);
    $nights = intval($_POST['nights']);

    $sql = "INSERT INTO packages (title, description, price, days, nights) 
            VALUES ('$title', '$description', '$price', '$days', '$nights')";

    if (!mysqli_query($conn, $sql)) {
        die("Error: " . mysqli_error($conn));
    }
    header("Location: manage_packages.php");
    exit();
}

// ✅ Update package
if (isset($_POST['update_package'])) {
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $days = intval($_POST['days']);
    $nights = intval($_POST['nights']);

    $sql = "UPDATE packages 
            SET title='$title', description='$description', price='$price', days='$days', nights='$nights' 
            WHERE id=$id";

    if (!mysqli_query($conn, $sql)) {
        die("Error: " . mysqli_error($conn));
    }
    header("Location: manage_packages.php");
    exit();
}

// ✅ Delete package
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM packages WHERE id=$id");
    header("Location: manage_packages.php");
    exit();
}

// ✅ Edit package form fill
$edit_package = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_query = mysqli_query($conn, "SELECT * FROM packages WHERE id=$id");
    $edit_package = mysqli_fetch_assoc($edit_query);
}

// ✅ Fetch all packages
$result = mysqli_query($conn, "SELECT * FROM packages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Travel Packages</title>
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
        }
        .content {
            margin: 30px;
        }
        form {
            margin-bottom: 20px;
            background: #1f1f1f;
            padding: 20px;
            border-radius: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 6px;
        }
        input[type="submit"] {
            background: #ff9800;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #e68900;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #1f1f1f;
            border-radius: 10px;
            overflow: hidden;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #333;
            text-align: left;
        }
        table th {
            background: #ff9800;
            color: #fff;
        }
        a.delete-btn, a.edit-btn {
            padding: 6px 12px;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        a.delete-btn { background: #e53935; }
        a.delete-btn:hover { background: #c62828; }
        a.edit-btn { background: #2196f3; }
        a.edit-btn:hover { background: #1976d2; }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #ff9800;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        .back-btn:hover {
            background: #e68900;
        }
    </style>
</head>
<body>

<div class="header">✈ Manage Travel Packages</div>

<div class="content">
    <!-- ✅ Add / Edit Package Form -->
    <form method="POST">
        <h2><?php echo $edit_package ? "✏ Edit Package" : "➕ Add New Package"; ?></h2>
        <input type="hidden" name="id" value="<?php echo $edit_package['id'] ?? ''; ?>">
        <input type="text" name="title" placeholder="Package Title" value="<?php echo $edit_package['title'] ?? ''; ?>" required>
        <textarea name="description" placeholder="Package Description" required><?php echo $edit_package['description'] ?? ''; ?></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price (INR)" value="<?php echo $edit_package['price'] ?? ''; ?>" required>
        <input type="number" name="days" placeholder="Number of Days" value="<?php echo $edit_package['days'] ?? ''; ?>" required>
        <input type="number" name="nights" placeholder="Number of Nights" value="<?php echo $edit_package['nights'] ?? ''; ?>" required>
        
        <?php if ($edit_package) { ?>
            <input type="submit" name="update_package" value="Update Package">
        <?php } else { ?>
            <input type="submit" name="add_package" value="Add Package">
        <?php } ?>
    </form>

    <!-- ✅ Package List -->
    <table>
        <tr>
            <th>ID</th>
            <th>📌 Title</th>
            <th>📖 Description</th>
            <th>💰 Price</th>
            <th>⏳ Days</th>
            <th>🌙 Nights</th>
            <th>⚡ Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td>₹<?php echo $row['price']; ?></td>
            <td><?php echo $row['days']; ?></td>
            <td><?php echo $row['nights']; ?></td>
            <td>
                <a href="manage_packages.php?edit=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                <a href="manage_packages.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Kya aap sure hain is package ko delete karne ke liye?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
