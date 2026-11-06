<?php
// Start session or check authentication logic
session_start();

$isLoggedIn = false; // Imagine this is checked from a database

if (!$isLoggedIn) {
    // Redirect within the same file if conditions aren't met
    header("Location: simon.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard!</h1>
</body>
</html>