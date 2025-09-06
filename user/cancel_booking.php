<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check booking status
    $check = mysqli_query($conn, "SELECT * FROM package_bookings WHERE id='$booking_id' AND user_id='$user_id'");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);

        if ($row['status'] == "Confirmed") {
            // If booking was already approved → send Cancel Request to admin
            mysqli_query($conn, "UPDATE package_bookings SET status='Cancel_Request' WHERE id='$booking_id'");
            echo "<script>alert('⏳ Cancel request sent to admin.'); window.location.href='profile.php';</script>";
        } elseif ($row['status'] == "Pending") {
            // Pending bookings user can cancel directly
            mysqli_query($conn, "UPDATE package_bookings SET status='Cancelled' WHERE id='$booking_id'");
            echo "<script>alert('❌ Booking cancelled successfully.'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('⚠️ This booking cannot be cancelled.'); window.location.href='profile.php';</script>";
        }
    } else {
        echo "<script>alert('⚠️ Booking not found.'); window.location.href='profile.php';</script>";
    }
}
?>
