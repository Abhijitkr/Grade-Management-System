<?php
// Start a session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit();
}

// Include the db_connection.php file
require_once 'db_connection.php';

// Retrieve the list of grades from the database
$stmt = $mysqli->prepare('SELECT grades.grade_id, students.student_name, courses.course_name, grades.grade FROM grades INNER JOIN students ON grades.student_id = students.student_id INNER JOIN courses ON grades.course_id = courses.course_id');
$stmt->execute();
$result = $stmt->get_result();
$grades = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grade Report</title>
    <!-- Include your CSS stylesheets and any necessary scripts -->
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style type="text/css" media="print">
        /* Define print-specific CSS styles */
        body {
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border: 1px solid black;
        }
    </style>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #printButton {
            display: block;
            margin: 0 auto;
            text-align: center;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #printButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Grade Report</h1>

    <div class="container">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Grade ID</th>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grades as $grade) { ?>
                    <tr>
                        <td><?php echo $grade['grade_id']; ?></td>
                        <td><?php echo $grade['student_name']; ?></td>
                        <td><?php echo $grade['course_name']; ?></td>
                        <td><?php echo $grade['grade']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <button id="printButton" onclick="window.print()">Print Report</button>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
