<?php
// Start the session
session_start();

// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'student_management';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE Student: Adding a new student
if (isset($_POST['create_student'])) {
    $student_name = $_POST['student_name'];
    $department = $_POST['department'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $register_number = $_POST['register_number'];

    $sql = "INSERT INTO students (student_name, department, age, gender, address, phone_number, register_number) 
            VALUES ('$student_name', '$department', '$age', '$gender', '$address', '$phone_number', '$register_number')";

    if ($conn->query($sql) === TRUE) {
        echo "New student added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// CREATE Report: Adding a report for a student
if (isset($_POST['create_report'])) {
    $student_id = $_POST['student_id'];
    $report_title = $_POST['report_title'];
    $report_description = $_POST['report_description'];

    $sql = "INSERT INTO student_reports (student_id, report_title, report_description) 
            VALUES ('$student_id', '$report_title', '$report_description')";

    if ($conn->query($sql) === TRUE) {
        echo "Report added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student and Report Management</title>
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
        input[type="text"], input[type="number"], input[type="tel"], input[type="date"], textarea {
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
        .student-list {
            width: 80%;
            max-width: 800px;
            margin-top: 20px;
        }
        .student-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-list th, .student-list td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        .student-list th {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>

<header>
    <h1>Student and Report Management</h1>
    
</header>

<!-- Form to Add a New Student -->
<div class="form-container">
    <h2>Create a New Student</h2>
    <form method="POST" action="">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="text" name="department" placeholder="Department" required>
        <input type="number" name="age" placeholder="Age" required>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <textarea name="address" placeholder="Address" required></textarea>
        <input type="tel" name="phone_number" placeholder="Phone Number" required>
        <input type="text" name="register_number" placeholder="Register Number" required>
        <button type="submit" name="create_student">Create Student</button>
    </form>
</div>

<!-- Form to Add a Report for a Student -->
<div class="form-container">
    <h2>Create a Report for a Student</h2>
    <form method="POST" action="">
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php
            // Fetch students from the database
            $conn = new mysqli($host, $username, $password, $dbname);
            $result = $conn->query("SELECT id, student_name FROM students");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['student_name']}</option>";
                }
            }
            ?>
        </select>
        <input type="text" name="report_title" placeholder="Report Title" required>
        <textarea name="report_description" placeholder="Report Description" required></textarea>
        <button type="submit" name="create_report">Create Report</button>
    </form>
</div>

<!-- Display Created Students -->
<div class="student-list">
    <h2>List of Students</h2>
    <table>
        <thead>
            <tr>
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
            <?php
            // Fetch students from the database
            $conn = new mysqli($host, $username, $password, $dbname);
            $result = $conn->query("SELECT * FROM students");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['student_name']}</td>
                        <td>{$row['department']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['phone_number']}</td>
                        <td>{$row['register_number']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No students found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
