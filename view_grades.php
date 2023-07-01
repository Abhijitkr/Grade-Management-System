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
    <title>View Grades</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 40px;
        }

        h1 {
            text-align: center;
            font-size: 30px;
            margin-bottom: 30px;
        }

        .table-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .btn-print {
            margin-top: 20px;
            text-align: center;
        }

        .btn-print button {
            font-size: 18px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Grades</h1>

        <div class="table-container">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Grade ID</th>
                        <th>Student Name</th>
                        <th>Course Name</th>
                        <th>Grade</th>
                        <?php if ($_SESSION['role'] === 'instructor') { ?>
                            <th>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $grade) { ?>
                        <tr>
                            <td><?php echo $grade['grade_id']; ?></td>
                            <td><?php echo $grade['student_name']; ?></td>
                            <td><?php echo $grade['course_name']; ?></td>
                            <td><?php echo $grade['grade']; ?></td>
                            <?php if ($_SESSION['role'] === 'instructor') { ?>
                                <td>
                                    <a href="assign_grade.php?id=<?php echo $grade['grade_id']; ?>" class="btn btn-outline-primary btn-sm">Assign Grade</a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="btn-print">
            <button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = 'report.php';">Print Report</button>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
