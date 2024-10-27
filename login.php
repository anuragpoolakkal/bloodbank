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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Both fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, type FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $user_type);
            $stmt->fetch();
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $user_type;
            echo "<script>
                    localStorage.setItem('user_id', '$user_id');
                    localStorage.setItem('email', '$email');
                    localStorage.setItem('user_type', '$user_type');
                    window.location.href = '" . ($user_type === 'admin' ? "admin.php" : "home.php") . "';
                  </script>";
            exit();
        } else {
            echo "Invalid email or password.";
        }

        $stmt->close();
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
    <title>User Login</title>
</head>
<body>
    <div class="container">
        <h1>Blood Bank</h1>
        <form action="login.php" method="post">
            <h2>Log in</h2>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" maxlength="40" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" maxlength="40" required><br><br>

            <input type="submit" value="Login">
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>
