<?php
// Start the session to manage user authentication
session_start();

// If the user clicks "Log Out", destroy the session and redirect to main.html
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: main.php'); // Redirect to main.html after logout
    exit();
}
// Start the session to access the student name
// session_start();
// Check if the student name exists in the session
if (isset($_SESSION['studentName'])) {
$student_name = $_SESSION['studentName'];
} else {
  // If the student is not logged in, you can redirect or show a default message
     $studentName = "Student";
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Module</title>
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
            display: flex;
            gap: 15px;
        }
        nav a {
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        nav a:hover {
            background-color: #555;
        }
        section {
            margin: 20px;
            width: 80%;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        footer {
            margin-top: auto;
            background-color: #333;
            color: white;
            width: 100%;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Student Module</h1>
    </header>

    <nav>
        <a href="student_profile.php">Profile</a>
        <a href="work_list.php">Work List</a>
        <a href="download.php">Download</a>
        <a href="?logout=true" style="color: red;">Log Out</a>
    </nav>

    <section>
    <h2>Welcome, <?php echo $studentName; ?>!</h2>
</section>
    <footer>
        <p>&copy; 2024 Work Scholarship System</p>
    </footer>
</body>
</html>

