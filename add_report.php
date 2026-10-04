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

// CREATE: Add a new event
if (isset($_POST['create'])) {
    $event_name = $_POST['event_name'];
    $event_datetime = $_POST['event_datetime'];
    $description = $_POST['description'];

    $sql = "INSERT INTO events (event_name, event_datetime, description) 
        VALUES ('$event_name', '$event_datetime', '$description')";


    if ($conn->query($sql) === TRUE) {
        echo "New event added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// READ: Fetch all events
$events = [];
$result = $conn->query("SELECT * FROM events");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// UPDATE: Edit an event's information
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    // Fetch event data
    $result = $conn->query("SELECT * FROM events WHERE id = $id");
    if ($result) {
        $event = $result->fetch_assoc();
    } else {
        die("Error fetching event data: " . $conn->error);
    }

    if (isset($_POST['update'])) {
        // Collect data from the form
        $event_name = $_POST['event_name'];
        $event_datetime = $_POST['event_datetime'];
        $description = $_POST['description'];

        // Update query
        $update_query = "UPDATE events SET event_name='$event_name', 
        event_datetime='$event_datetime', description='$description' WHERE id=$id";
        if ($conn->query($update_query) === TRUE) {
            header("Location: add_report.php"); // Redirect to the same page
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

// DELETE: Remove an event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM events WHERE id = $id";

    if ($conn->query($delete_query) === TRUE) {
        header("Location: add_report.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

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
    <title>Event Management</title>
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
        .form-container {
            margin-top: 20px;
            width: 80%;
            max-width: 600px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        input[type="text"], input[type="datetime-local"], textarea {
            width: 97%;
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
    <h1>Event Management</h1>
    <h2><nav><a href="?logout=true" style="color: red">Log Out</a></h2></nav>

</header>

<!-- CREATE / UPDATE FORM -->
<div class="form-container">
    <form method="POST" action="">
        <input type="text" name="event_name" placeholder="Event Name" required value="<?php echo isset($event) ? $event['event_name'] : ''; ?>">
        <input type="datetime-local" name="event_datetime" required value="<?php echo isset($event) ? $event['event_datetime'] : ''; ?>">
        <textarea name="description" placeholder="Description"><?php echo isset($event) ? $event['description'] : ''; ?></textarea>

        <?php if (isset($event)): ?>
            <button type="submit" name="update">Update Event</button>
        <?php else: ?>
            <button type="submit" name="create">Create Event</button>
        <?php endif; ?>
    </form>
</div>

<!-- READ: Display all events -->
<div class="event-list">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Date & Time</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $index => $event): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $event['event_name']; ?></td>
                    <td><?php echo $event['event_datetime']; ?></td>
                    <td><?php echo $event['description']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $event['id']; ?>">Edit</a>
                        <a href="?delete=<?php echo $event['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
