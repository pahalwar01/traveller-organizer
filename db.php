<?php
// db.php - single DB connection used by all pages
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "traveller_db";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");