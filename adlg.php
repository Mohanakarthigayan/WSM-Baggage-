<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
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
        <h2>Admin Login</h2> 
        <form method="POST" action="">
            <div class="form-group">
                <label for="adminName"><hr style="width: 50;"> <br>Name:</label>
                <input type="text" id="adminName" name="adminName" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <a href="stdlog.php"></a>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminName = $_POST['adminName'];
            $password = $_POST['password'];

            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'admin_db');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "CREATE TABLE IF NOT EXISTS admins (
                id INT AUTO_INCREMENT PRIMARY KEY,
                admin_name VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL
            )";
            $conn->query($sql);

            $sql = "SELECT * FROM admins WHERE admin_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $adminName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['password'] == $password) {
                    echo "<script>window.location.href='admin.php';</script>";
                } else {
                    echo "<p class='error'>Invalid password!</p>";
                }
            } else {
                echo "<p class='error'>Admin not found!</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
