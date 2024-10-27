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

$isLoggedIn = isset($_SESSION['email']);
$userType = $isLoggedIn ? $_SESSION['user_type'] : null;

if ($isLoggedIn) {
    if ($userType === 'admin') {
        if (basename($_SERVER['SCRIPT_NAME']) === 'index.php') {
            header('Location: donations.php');
            exit();
        }
    }
}
?>
