<?php
// Database connection
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

// Check if selected event IDs are passed from the query string
if (isset($_GET['events']) && !empty($_GET['events'])) {
    $selected_event_ids = $_GET['events'];
    
    // Sanitize the event IDs (to avoid SQL injection)
    $event_ids = implode(',', array_map('intval', $selected_event_ids));
    
    // Fetch events based on selected event IDs
    $sql = "SELECT id, event_name, event_datetime, description FROM events WHERE id IN ($event_ids)";
    $result = $conn->query($sql);
    $selected_events = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
} else {
    $selected_events = [];
}

// Close the connection
$conn->close();
?>
<?php
// Start the session to manage user authentication
session_start();
// If the user clicks "Log Out", destroy the session and redirect to main.html
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: work_list.php'); // Redirect to main.html after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
        }
        h1, h2 {
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
<body><header>
       <h1>Selected Events</h1>
    <h2><nav><a href="?logout=true" style="color: red">Log Out</a></h2></nav>
    </header>
 
    <?php if (!empty($selected_events)): ?>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date & Time</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selected_events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['event_datetime']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No events selected.</p>
    <?php endif; ?>
</body>
</html>
