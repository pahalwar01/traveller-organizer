<?php
session_start();
include_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $package_id = intval($_POST['package_id']);

    // check duplicate
    $stmt = mysqli_prepare($conn, "SELECT id FROM package_bookings WHERE user_id = ? AND package_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $package_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        echo "<script>alert('This Package is already added to your profile'); window.location.href='profile.php';</script>";
        exit();
    }
    mysqli_stmt_close($stmt);

    // insert booking
    $ins = mysqli_prepare($conn, "INSERT INTO package_bookings (user_id, package_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($ins, "ii", $user_id, $package_id);
    if (mysqli_stmt_execute($ins)) {
        echo "<script>alert('Package booked successfully'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error booking package'); window.location.href='profile.php';</script>";
    }
    mysqli_stmt_close($ins);
}
