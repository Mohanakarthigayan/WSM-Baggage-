<?php
// Start the session to manage user authentication
session_start();

// If the user clicks "Log Out", destroy the session and redirect to main.html
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: student.php'); // Redirect to main.html after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Student Work Reports</title>
    <style>
        /* Internal CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: whitesmoke;
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
            padding: 10px 150px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #555;
            color: black;
        }
        .container {
            width: 100%;
            margin: 0px auto;
            background: #333;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #fff;
        }

        .download-buttons {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin: 15px 0;
        }

        .download-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            cursor: pointer;
            border-radius: 6px;
        }

        .download-buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Download Student Work Reports</h1><h2><nav><a href="?logout=true" style="color: red "><b>Log Out</b></a></nav></h2>
        
        <div class="download-buttons">
            <button onclick="downloadReport('month')">Download Monthly Report</button>
            <button onclick="downloadReport('year')">Download Yearly Report</button>
        

        </div>
    </div>

    <script>
        // JavaScript to handle report download
        function downloadReport(type) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'download_report.php';

            const inputType = document.createElement('input');
            inputType.type = 'hidden';
            inputType.name = 'reportType';
            inputType.value = type;
            form.appendChild(inputType);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>
</html>

<?php
// download_report.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];

    // Prepare data for the report
    $data = [
        ["title" => "Project 1", "work_list" => "Design", "time" => "10:00 AM", "student_name" => "John Doe", "register_number" => "12345"],
        ["title" => "Project 2", "work_list" => "Development", "time" => "2:00 PM", "student_name" => "Jane Smith", "register_number" => "67890"],
    ];

    // Define filename based on type
    $fileName = $reportType === 'month' ? 'Monthly_Report.csv' : 'Yearly_Report.csv';

    // Set headers for file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $fileName);

    // Open file stream
    $output = fopen('php://output', 'w');

    // Write the CSV header
    fputcsv($output, ['Title', 'Work List', 'Time', 'Student Name', 'Register Number']);

    // Write the data rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Close file stream
    fclose($output);
    exit;
}
?>
