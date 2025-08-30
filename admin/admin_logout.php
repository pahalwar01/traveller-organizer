<?php
session_start();

// Sabhi admin session variables clear kar do
session_unset();
session_destroy();

// Redirect back to admin login page
header("Location: admin_login.php");
exit();
