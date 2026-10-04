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

// Fetch events from the database
$sql = "SELECT id, event_name, event_datetime, description FROM events ORDER BY event_datetime ASC"; 
$result = $conn->query($sql);
$events = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Close the connection
$conn->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['selected_events'])) {
        // Send selected events to work.php
        $selected_events = $_POST['selected_events'];
        // Create a query string of selected event IDs
        $query_string = http_build_query(['events' => $selected_events]);
        header("Location: work.php?$query_string");
        exit();
    } else {
        echo "<p style='color: red;'>No events selected.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Events</title>
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
<body>
<header>
<h1>Upload Events</h1>
<h2><nav><a href="?logout=true" style="color: red">Log Out</a></h2></nav>
</header>
<form method="POST" action="">
    <h1>View Events</h1>
    <h2>Select Events</h2>
    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Event Name</th>
                <th>Date & Time</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_events[]" value="<?php echo $event['id']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['event_datetime']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No events available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button type="submit">Submit</button>
</form>
</body>
</html>
