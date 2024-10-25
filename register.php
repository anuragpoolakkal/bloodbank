<?php
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
        echo "All fields are required.";
    } else {
        $sql = "INSERT INTO users (name, phone, email, password, blood_group) 
                VALUES ('$name', '$phone', '$email', '$password', '$blood_group')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>User Registration</title>
</head>
<body>
    <h2>Blood Bank User Registration</h2>
    <form action="register.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" maxlength="40"><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" maxlength="10"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="40"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" maxlength="40"><br><br>

        <label for="blood_group">Blood Group:</label>
        <input type="text" id="blood_group" name="blood_group" maxlength="3"><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
