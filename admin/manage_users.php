<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include '../db.php';

// ✅ User delete request
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
    header("Location: manage_users.php");
    exit();
}

// ✅ Fetch all users
$result = mysqli_query($conn, "SELECT id, name, email, created_at FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
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
        a.delete-btn {
            background: #e53935;
            padding: 6px 12px;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        a.delete-btn:hover {
            background: #c62828;
        }
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

<div class="header">👤 Manage Users</div>

<div class="content">
    <table>
        <tr>
            <th>ID</th>
            <th>👤 Username</th>
            <th>📧 Email</th>
            <th>📅 Registered On</th>
            <th>⚡ Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="manage_users.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Kya aap sure hain is user ko delete karne ke liye?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
