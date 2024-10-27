<?php
session_start();

$host = 'database-1.clmcs66qmddh.ap-south-1.rds.amazonaws.com';
$port = 3306;
$db = 'bloodbank';
$user = 'admin';
$pass = 'password';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$isLoggedIn = isset($_SESSION['email']); // Check for the correct session variable
$userType = $isLoggedIn ? $_SESSION['user_type'] : null;

if ($isLoggedIn) {
    if ($userType === 'user') {
        header('Location: home.php');
        exit();
    } elseif ($userType === 'admin') {
        header('Location: admin.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
    <title>Blood Bank</title>
</head>
<body>
    <h1>Blood Bank</h1>
</body>
</html>
