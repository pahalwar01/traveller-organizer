<?php
session_start();
include_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit();
}

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "UPDATE package_bookings SET status='Cancelled' WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Booking cancelled'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Unable to cancel'); window.location.href='profile.php';</script>";
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: profile.php");
}
