<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != "user") {
    header('Location: login.php');
    exit();
}

// Database connection code remains unchanged
$host = 'database-1.clmcs66qmddh.ap-south-1.rds.amazonaws.com';
$port = 3306;
$db = 'bloodbank';
$user = 'admin';
$pass = 'password';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id'], $_POST['blood_group'], $_POST['donation_date'], $_POST['quantity_ml'])) {
        $userId = $_POST['user_id'];
        $bloodGroup = $_POST['blood_group'];
        $donationDate = $_POST['donation_date'];
        $quantityMl = $_POST['quantity_ml'];

        $stmt = $conn->prepare("INSERT INTO donations (user_id, blood_group, donation_date, quantity_ml) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $userId, $bloodGroup, $donationDate, $quantityMl);

        if ($stmt->execute()) {
            echo "Donation recorded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
    <title>Blood Bank - Home</title>
    <script src="assets/script.js"></script>
</head>
<body>
  <div class="container">
    <div class="logout-container">
        <button class="logout-button" onclick="logout()">
            <i class="fas fa-sign-out-alt logout-icon"></i>
        </button>
    </div>
    <h1>Blood Bank</h1>
    <form method="POST" action="home.php">
        <!-- user_id is stored in the localStorage (js), set it in a hidden input field -->
        <script>
            document.write('<input type="hidden" name="user_id" value="' + localStorage.getItem('user_id') + '">');
        </script>
        <h2>Donation Details</h2>   
        <label for="blood_group">Blood Group:</label>
        <select id="blood_group" name="blood_group" required>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>

        <label for="donation_date">Donation Date:</label>
        <input type="date" id="donation_date" name="donation_date" required>

        <label for="quantity_ml">Quantity (in ml):</label>
        <input type="number" id="quantity_ml" name="quantity_ml" required>

        <button type="submit">Submit Donation</button>
    </form>
  </div>
</body>
</html>
