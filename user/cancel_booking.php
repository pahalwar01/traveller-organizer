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

    // Update status to 'Cancelled' instead of deleting
    $query = "UPDATE package_bookings SET status='Cancelled' WHERE id='$booking_id' AND user_id='$user_id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Booking cancelled successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error cancelling booking!'); window.location.href='profile.php';</script>";
    }
} else {
    header("Location: profile.php");
}
?>
