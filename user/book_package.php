<?php
session_start();
include("../db.php");  // db.php ek level upar hai

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_SESSION['user_id'];
    $package_id = $_POST['package_id'];

    // Pehle check karo ki user ne already package book kiya hai ya nahi
    $check = mysqli_query($conn, "SELECT * FROM package_bookings WHERE user_id='$user_id' AND package_id='$package_id'");
    
    if (mysqli_num_rows($check) > 0) {
        // Agar record mil gaya to error show karo
        echo "<script>alert('⚠️ This Package is already added to your profile!'); window.location.href='profile.php';</script>";
    } else {
        // Agar nahi mila to insert karo
        $sql = "INSERT INTO package_bookings (user_id, package_id) VALUES ('$user_id', '$package_id')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('🎉 Package booked successfully!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('❌ Error booking package.'); window.location.href='profile.php';</script>";
        }
    }
}
?>
