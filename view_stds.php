<?php
// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'student_db';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all students from the database
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

// Calculate total students, male, and female
$total_students = $result->num_rows;
$total_male = 0;
$total_female = 0;

if ($total_students > 0) {
    while ($row = $result->fetch_assoc()) {
        if (strtolower($row['gender']) == 'male') {
            $total_male++;
        } elseif (strtolower($row['gender']) == 'female') {
            $total_female++;
        }
        $students[] = $row;
    }
} else {
    $students = [];
}

// Close the database connection
$conn->close();
?>
<?php
// Start the session to manage user authentication
session_start();
// If the user clicks "Log Out", destroy the session and redirect to main.html
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: report.php'); // Redirect to main.html after logout
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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
        nav a {
            float: right;
            color: white;
            background-color: #333;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        nav a:hover {
            background-color: #555;
        }
        .statistics {
            margin: 20px 0;
            display: flex;
            gap: 30px;
            justify-content: center;
            font-size: 1.2em;
        }
        .statistics div {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:hover {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <header>
        <h1>View Students</h1>
        <nav>
            <H2><a href="?logout=true" style="color: red;">Log Out</a></H2>
        </nav>
    </header>

    <div class="statistics">
        <div>Total Students: <?php echo $total_students; ?></div>
        <div>Total Male: <?php echo $total_male; ?></div>
        <div>Total Female: <?php echo $total_female; ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Department</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Register Number</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total_students > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo $student['student_name']; ?></td>
                        <td><?php echo $student['department']; ?></td>
                        <td><?php echo $student['age']; ?></td>
                        <td><?php echo $student['gender']; ?></td>
                        <td><?php echo $student['address']; ?></td>
                        <td><?php echo $student['phone_number']; ?></td>
                        <td><?php echo $student['register_number']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
