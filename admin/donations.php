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

$query = "SELECT donations.*, users.name AS user_name, users.email AS user_email, users.phone AS user_phone 
          FROM donations 
          JOIN users ON donations.user_id = users.id";
if ($searchTerm) {
    $query .= " WHERE users.name LIKE '%$searchTerm%' 
                OR donations.blood_group LIKE '%$searchTerm%' 
                OR donations.donation_date LIKE '%$searchTerm%' 
                OR donations.quantity_ml LIKE '%$searchTerm%'";
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
    <title>Admin - Donations Management</title>
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
        <a href="donations.php" class="active">Donations</a>
        <a href="users.php">Users</a>
    </nav>
    
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search donations..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Donation ID</th>
                <th>Donation Date</th>
                <th>Donor</th>
                <th>Blood Group</th>
                <th>Quantity (ml)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['donation_id']) . "</td>
                            <td>" . htmlspecialchars($row['donation_date']) . "</td>
                            <td><b>" . htmlspecialchars($row['user_name']) . "</b><br/>" . htmlspecialchars($row['user_email']) . "<br/>" . htmlspecialchars($row['user_phone']) . "</td>
                            <td>" . htmlspecialchars($row['blood_group']) . "</td>
                            <td>" . htmlspecialchars($row['quantity_ml']) . "</td>
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
