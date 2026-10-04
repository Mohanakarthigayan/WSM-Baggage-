<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy(); 
    header('Location: main.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Module</title>
    
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
        <h1>Admin Module</h1>
    </header>

    <nav>
        <a href="profile.php">Profile</a>
        <a href="view_report.php">View Reports</a>
        <a href="view_students.php">View Students</a>
         <a href="add_report.php">Add Report</a>
        <a href="stdls.php">Add Student Details</a>
        <a href="?logout=true" style="color: red;">Log Out</a>
    </nav>

    <section>
        <h2>Welcome, Admin</h2>
        <p>Select a module from the navigation bar to manage the system.</p>
    </section>

    <footer>
        <p>&copy; 2024 Work Scholarship System</p>
    </footer>

</body>
</html>
