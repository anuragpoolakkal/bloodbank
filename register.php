<?php
session_start();
$message = "";

$host = 'database-1.clmcs66qmddh.ap-south-1.rds.amazonaws.com';
$port = 3306;
$db = 'bloodbank';
$user = 'admin';
$pass = 'password';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $blood_group = $_POST['blood_group'];

    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($blood_group)) {
        $message = "All fields are required.";
    } else {
        $checkEmailSql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($checkEmailSql);

        if ($result->num_rows > 0) {
            $message = "Email already exists. Please use a different email.";
        } else {
            // Proceed to insert the new user
            $sql = "INSERT INTO users (name, phone, email, password, blood_group) 
                    VALUES ('$name', '$phone', '$email', '$password', '$blood_group')";

            if ($conn->query($sql) === TRUE) {
                $message = "Registration successful!";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
    <title>User Registration</title>
</head>
<body>
    <div class="container">
        <h1>Blood Bank</h1>
        <div class="message">
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
        <form action="register.php" method="post">
            <h2>Register</h2>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" maxlength="40" required><br><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" maxlength="10" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" maxlength="40" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" maxlength="40" required><br><br>

            <label for="blood_group">Blood Group:</label>
            <input type="text" id="blood_group" name="blood_group" maxlength="3" required><br><br>

            <input type="submit" value="Register">

            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
