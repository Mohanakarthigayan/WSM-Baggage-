<?php
// Start the session
session_start();

// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'events_db';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Log Out
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: admin.php'); // Redirect to the same page (admin.php)
    exit();
}

// If admin is logged in, retrieve their name
if (isset($_SESSION['admin_name'])) {
    $admin_name = $_SESSION['admin_name'];
} else {
    header('Location: admin_login.php'); // Redirect to login page if not logged in
    exit();
}

// Check if we are displaying the profile or dashboard
$view = isset($_GET['view']) ? $_GET['view'] : 'dashboard'; // Default view is 'dashboard'

// Fetch other profile data (example: username, email) from the database if needed
if ($view == 'profile') {
    $admin_username = $_SESSION['admin_username']; // Assuming this is stored in the session
    $admin_email = $_SESSION['admin_email']; // Assuming this is stored in the session
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            background-color: #333;
            color: white;
            width: 100%;
            padding: 15px;
            text-align: center;
        }
        nav {
            margin: 20px 0;
            display: inline-block;
            gap: 30px;
        }
        nav a {
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 20px;
        }
        nav a:hover {
            background-color: #555;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin-top: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #333;
            color: white;
        }
        button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <a href="?view=dashboard">Dashboard</a> | 
        <a href="?view=profile">Profile</a> | 
        <a href="?logout=true" style="color: red;">Log Out</a>
    </nav>
</header>

<div class="container">
    <?php if ($view == 'dashboard'): ?>
        <h2>Welcome, <?php echo htmlspecialchars($admin_name); ?></h2>
        <h3>Dashboard</h3>
        <!-- Other dashboard content goes here (e.g., event statistics, reports, etc.) -->
        <p>This is your admin dashboard. You can manage events, view reports, etc.</p>
        
    <?php elseif ($view == 'profile'): ?>
        <h2>Admin Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($admin_name); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($admin_username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($admin_email); ?></p>
        
        <a href="?view=dashboard"><button>Back to Dashboard</button></a>
    <?php endif; ?>
</div>

</body>
</html>
