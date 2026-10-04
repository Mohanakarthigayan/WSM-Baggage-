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

// CRUD Operations:

// CREATE: Add a new event
if (isset($_POST['create'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $description = $_POST['description'];

    $sql = "INSERT INTO events (event_name, event_date, event_time, description) VALUES ('$event_name', '$event_date', '$event_time', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "New event created successfully!";
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

// UPDATE: Edit an event
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM events WHERE id = $id");
    $event = $result->fetch_assoc();

    if (isset($_POST['update'])) {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];
        $description = $_POST['description'];

        $update_query = "UPDATE events SET event_name='$event_name', event_date='$event_date', event_time='$event_time', description='$description' WHERE id=$id";
        
        if ($conn->query($update_query) === TRUE) {
            header("Location: schedule_event.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

// DELETE: Delete an event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM events WHERE id=$id";

    if ($conn->query($delete_query) === TRUE) {
        header("Location: schedule_event.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Event</title> <a href="admin.php"></a>
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
        .form-container {
            margin-top: 20px;
            width: 80%;
            max-width: 600px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        input[type="text"], input[type="date"], input[type="time"], textarea {
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
        .event-list tr:hover {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<header>
    <h1>Schedule Event</h1>
</header>

<!-- CREATE / UPDATE FORM -->
<div class="form-container">
    <form method="POST" action="">
        <input type="text" name="event_name" placeholder="Event Name" required value="<?php echo isset($event) ? $event['event_name'] : ''; ?>">
        <input type="date" name="event_date" required value="<?php echo isset($event) ? $event['event_date'] : ''; ?>">
        <input type="time" name="event_time" required value="<?php echo isset($event) ? $event['event_time'] : ''; ?>">
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
                <th>Event Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo $event['event_name']; ?></td>
                    <td><?php echo $event['event_date']; ?></td>
                    <td><?php echo $event['event_time']; ?></td>
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
