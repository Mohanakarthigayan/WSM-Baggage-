<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .login-container {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"] {
            width: 92%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background:rgb(6, 69, 169);;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background:rgb(6, 69, 169);
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="studentName"><hr style="width: 50;"> <br>Student:</label>
                <input type="text" id="studentName" name="studentName" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $studentName = $_POST['studentName'];
            $password = $_POST['password'];

            // Hardcoded password
            $correctPassword = 'au220547';


            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'stdlg');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Log the login attempt in the database
            $sql = "INSERT INTO login_logs (student_name, login_time) VALUES (?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $studentName);
            $stmt->execute();

            // Check if the password is correct
            if ($password === $correctPassword) {
                // Redirect to student module
                echo "<script>window.location.href='student.php';</script>";
            } else {
                // Show error message
                echo "<p class='error'>Invalid password!</p>";
            }

            // Close the database connection
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
