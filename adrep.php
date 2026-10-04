<?php
// Start the session
session_start();

// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'event_management';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE Event: Adding a new event
if (isset($_POST['create_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];

    $sql = "INSERT INTO events (event_name, event_date, event_description) 
            VALUES ('$event_name', '$event_date', '$event_description')";

    if ($conn->query($sql) === TRUE) {
        echo "New event added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// CREATE Report: Adding a report for an event
if (isset($_POST['create_report'])) {
    $event_id = $_POST['event_id'];
    $report_title = $_POST['report_title'];
    $report_description = $_POST['report_description'];

    $sql = "INSERT INTO event_reports (event_id, report_title, report_description) 
            VALUES ('$event_id', '$report_title', '$report_description')";

    if ($conn->query($sql) === TRUE) {
        echo "Report added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<?php
// Start the session to manage user authentication
//session_start();

// If the user clicks "Log Out", destroy the session and redirect to main.html
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: admin.php'); // Redirect to main.html after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Report and Schedule Event</title>

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
            padding: 1rem 0;
            color: white;
            width: 100%;
            padding: 5px;
            text-align: center;
        }
        nav {
            background: #555;
            overflow: visible;
        }
        nav a {
            float: right;
            display:grid;
            color: whitesmoke;
            text-align: right;
            padding: 8px 100px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #555;
            color: black;
        }
        .form-container {
            margin-top: 20px;
            width: 80%;
            max-width: 600px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
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
    </style>
</head>
<body>

<header>
    <h1>Event and Report Management</h1>
    <h2><nav><a href="?logout=true" style="color: red ">Log Out</a></h2></nav>
</header>

<!-- Form to Add a New Event -->
<div class="form-container">
    <h2>Create a New Event</h2>
    

    <form method="POST" action="">
        <input type="text" name="event_name" placeholder="Event Name" required>
        <input type="date" name="event_date" required>
        <textarea name="event_description" placeholder="Event Description" required></textarea>
        <button type="submit" name="create_event">Create Event</button>
    </form>
</div>

<!-- Form to Add a Report for an Event -->
<div class="form-container">
    <h2>Create a Report for an Event</h2>
    <form method="POST" action="">
        <select name="event_id" required>
            <option value="">Select Event</option>
            <?php
            // Fetch events from the database
            $conn = new mysqli($host, $username, $password, $dbname);
            $result = $conn->query("SELECT id, event_name FROM events");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['event_name']}</option>";
                }
            }
            ?>
        </select>
        <input type="text" name="report_title" placeholder="Report Title" required>
        <textarea name="report_description" placeholder="Report Description" required></textarea>
        <button type="submit" name="create_report">Create Report</button>
    </form>
</div>

<!-- Display Created Events -->
<div class="event-list">
    <h2>List of Events</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch events from the database
            $conn = new mysqli($host, $username, $password, $dbname);
            $result = $conn->query("SELECT * FROM events");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['event_name']}</td>
                        <td>{$row['event_date']}</td>
                        <td>{$row['event_description']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No events found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
