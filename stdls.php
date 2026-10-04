<?php
// Start the session
session_start();

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

// Start the session to manage user authentication
// session_start();

// If the user clicks "Log Out", destroy the session and redirect to main.html
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: admin.php'); // Redirect to main.html after logout
    exit();
}

// CRUD Operations:

// CREATE: Add a new student
if (isset($_POST['create'])) {
    $student_name = $_POST['student_name'];
    $department = $_POST['department'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $register_number = $_POST['register_number'];

    $sql = "INSERT INTO students (student_name, department, age, gender, address, phone_number, register_number) 
            VALUES ('$student_name', '$department', $age, '$gender', '$address', '$phone_number', '$register_number')";

    if ($conn->query($sql) === TRUE) {
        echo "New student added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// READ: Fetch all students
$students = [];
$result = $conn->query("SELECT * FROM students");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// UPDATE: Edit a student's information
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    // Fetch student data
    $result = $conn->query("SELECT * FROM students WHERE id = $id");
    if ($result) {
        $student = $result->fetch_assoc();
    } else {
        die("Error fetching student data: " . $conn->error);
    }

    if (isset($_POST['update'])) {
        // Collect data from the form
        $student_name = $_POST['student_name'];
        $department = $_POST['department'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];
        $register_number = $_POST['register_number'];

        // Update query
        $update_query = "UPDATE students SET student_name='$student_name', 
        department='$department', age=$age, gender='$gender', address='$address',
         phone_number='$phone_number', register_number='$register_number' WHERE id=$id";
        if ($conn->query($update_query) === TRUE) {
            header("Location: stdls.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Ensure the ID is numeric
    if (!is_numeric($id)) {
        die("Invalid ID");
    }

    // Prepare delete query
    $delete_query = "DELETE FROM students WHERE id = $id";

    // Execute the delete query
    if ($conn->query($delete_query) === TRUE) {
        header("Location: stdls.php"); // Redirect after successful deletion
        exit();
    } else {
        echo "Error deleting record: " . $conn->error; // Error message if query fails
    }
}

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
    <title>Student Management</title> 
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
        input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="date"], select, textarea {
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
        .student-list tr:hover {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<header>
    <h1>Student Management</h1>
    <h2><nav><a href="?logout=true" style="color: red ">Log Out</a></h2></nav>
</header>



<!-- CREATE / UPDATE FORM -->
<div class="form-container">
    <form method="POST" action="">
        <input type="text" name="student_name" placeholder="Student Name" required value="<?php echo isset($student) ? $student['student_name'] : ''; ?>">
        <input type="text" name="department" placeholder="Department" required value="<?php echo isset($student) ? $student['department'] : ''; ?>">
        <input type="number" name="age" placeholder="Age" required value="<?php echo isset($student) ? $student['age'] : ''; ?>">
        <select name="gender" required>
            <option value="Male" <?php echo isset($student) && $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo isset($student) && $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo isset($student) && $student['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
        </select>
        <textarea name="address" placeholder="Address"><?php echo isset($student) ? $student['address'] : ''; ?></textarea>
        <input type="tel" name="phone_number" placeholder="Phone Number" value="<?php echo isset($student) ? $student['phone_number'] : ''; ?>">
        <input type="text" name="register_number" placeholder="Register Number" required value="<?php echo isset($student) ? $student['register_number'] : ''; ?>">

        <?php if (isset($student)): ?>
            <button type="submit" name="update">Update Student</button>
        <?php else: ?>
            <button type="submit" name="create">Create Student</button>
        <?php endif; ?>
    </form>
</div>

<!-- READ: Display all students -->
<div class="student-list">
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['student_name']; ?></td>
                    <td><?php echo $student['department']; ?></td>
                    <td><?php echo $student['age']; ?></td>
                    <td><?php echo $student['gender']; ?></td>
                    <td><?php echo $student['address']; ?></td>
                    <td><?php echo $student['phone_number']; ?></td>
                    <td><?php echo $student['register_number']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $student['id']; ?>">Edit</a>
                        <a href="?delete=<?php echo $student['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>