<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != "admin") {
    header('Location: login.php');
    exit();
}

// Database connection code
$host = 'database-1.clmcs66qmddh.ap-south-1.rds.amazonaws.com';
$port = 3306;
$db = 'bloodbank';
$user = 'admin';
$pass = 'password';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchTerm = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string($_POST['search']);
}

$query = "SELECT * FROM users";
if ($searchTerm) {
    $query .= " WHERE name LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%' OR phone LIKE '%$searchTerm%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
    <title>Admin - User Management</title>
    <script src="../assets/script.js"></script>
</head>
<body>
  <div class="container">
    <div class="logout-container">
        <button class="logout-button" onclick="logout()">
            <i class="fas fa-sign-out-alt logout-icon"></i>
        </button>
    </div>
    <nav>
        <a href="donations.php">Donations</a>
        <a href="users.php" class="active">Users</a>
    </nav>
    
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Blood Group</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['phone']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['blood_group']) . "</td>
                            <td>" . htmlspecialchars($row['type']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>

    <?php
    $conn->close();
    ?>
</body>
</html>
