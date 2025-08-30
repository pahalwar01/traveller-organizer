<?php
// logout process start
session_start();  
session_unset(); 
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content=".1;url=index.php">
</head>
</html>