<?php
// Start the session
session_start();

// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'events_db'; // Database name

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all events from the database
$sql = "SELECT * FROM events ORDER BY event_datetime ASC"; // Order by datetime to show events in chronological order
$result = $conn->query($sql);

// Initialize variables
$events = [];
$totalWorks = 0;

// Check if there are any events
if ($result->num_rows > 0) {
    $events = $result->fetch_all(MYSQLI_ASSOC);
    $totalWorks = count($events); // Calculate total works
}

// Close the connection
$conn->close();

// Start the session to manage user authentication
// session_start();
// If the user clicks "Log Out", destroy the session and redirect to main.html
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: Schedule.php'); // Redirect to main.html after logout
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
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
            display: right;
            gap: 30px;
        }
        nav a {
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 25px;
            float: right;
        }
        nav a:hover {
            background-color: #555;
        }
        .event-list {
            width: 80%;
            max-width: 800px;
            margin-top: 20px;
        }
        .event-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .event-list th, .event-list td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        .event-list th {
            background-color: #333;
            color: white;
        }
        .total-count {
            text-align: left;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <h1>Event List</h1>
    <h2><nav><a href="?logout=true" style="color: red">Log Out</a></h2></nav>
</header>

<!-- Display Events -->
<div class="event-list">
    <div class="total-count">Total Works: <?php echo $totalWorks; ?></div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Date & Time</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $index => $event): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['event_datetime']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No events found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
