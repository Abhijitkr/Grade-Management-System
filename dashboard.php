<?php
// Start a session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit();
}

// Get the user ID and role from the session
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Include the db_connection.php file
require_once 'db_connection.php';

// Define variables to store the retrieved data
$total_students = 0;
$total_courses = 0;
$instructor_courses = array();
$student_courses = array();
$student_grades = array();

// Retrieve data based on the user's role
if ($role === 'admin') {
    // Get the total number of students
    $stmt_total_students = $mysqli->prepare('SELECT COUNT(*) FROM students');
    $stmt_total_students->execute();
    $stmt_total_students->bind_result($total_students);
    $stmt_total_students->fetch();
    $stmt_total_students->close();

    // Get the total number of courses
    $stmt_total_courses = $mysqli->prepare('SELECT COUNT(*) FROM courses');
    $stmt_total_courses->execute();
    $stmt_total_courses->bind_result($total_courses);
    $stmt_total_courses->fetch();
    $stmt_total_courses->close();
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 40px;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .dashboard-card {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .dashboard-card ul {
            list-style: none;
            padding: 0;
        }

        .dashboard-card ul li {
            margin-bottom: 10px;
        }

        .dashboard-card .dashboard-link {
            display: block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
            font-weight: bold;
            color: #007bff;
        }

        .dashboard-card .dashboard-link.view {
            color: #28a745;
        }

        .dashboard-card .dashboard-link.add {
            color: #dc3545;
        }

        .dashboard-card .dashboard-link:hover {
            background-color: #007bff;
            color: #fff;
        }

        .dashboard-card .dashboard-link:after {
            content: '';
            display: inline-block;
            vertical-align: middle;
            margin-left: 5px;
            width: 0;
            height: 0;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-left: 5px solid currentColor;
        }
        .list-unstyled{
            margin-top: 10px;
        }
        .logout-link {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545; /* Red color */
            color: #fff; /* White text color */
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s; /* Add CSS transition for background color and transform */
        }

        .logout-link:hover {
            background-color: #c82333; /* Darker shade of red on hover */
            transform: scale(1.02); /* Add scale transform on hover */
            text-decoration: none;
            color: #fff;
        }
        .total-card {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .total-card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .total-card p {
            font-size: 20px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-card text-center">
            <h2>Welcome to the Dashboard</h2>

            <!-- Display user-specific content based on role -->
            <?php if ($role === 'admin') { ?>
                <div class="alert alert-primary" role="alert">
                    <h3>Admin Dashboard</h3>
                </div>
                <!-- Beautiful cards for displaying total students and total courses -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="total-card">
                            <h3>Total Students</h3>
                            <p><?php echo $total_students; ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="total-card">
                            <h3>Total Courses</h3>
                            <p><?php echo $total_courses; ?></p>
                        </div>
                    </div>
                </div>
                <!-- Display admin-specific options and functionalities -->
                <ul class="list-unstyled">
                    <li><a class="dashboard-link add" href="add_student.php">Add Student</a></li>
                    <li><a class="dashboard-link view" href="view_students.php">Manage Students</a></li>
                    <li><a class="dashboard-link add" href="add_course.php">Add Course</a></li>
                    <li><a class="dashboard-link view" href="view_courses.php">Manage Courses</a></li>
                    <!-- Add more admin-specific options here -->
                </ul>
            <?php } elseif ($role === 'instructor') { ?>
                <div class="alert alert-success" role="alert">
                    <h3>Instructor Dashboard</h3>
                </div>
                <!-- Display instructor-specific options and functionalities -->
                <ul class="list-unstyled">
                    <li><a class="dashboard-link" href="assign_grade.php">Assign Grades</a></li>
                    <li><a class="dashboard-link view-grades" href="view_grades.php">View Grades</a></li>
                    <!-- Add more instructor-specific options here -->
                </ul>
            <?php } else { ?>
                <div class="alert alert-info" role="alert">
                    <h3>Student Dashboard</h3>
                </div>
                <!-- Display student-specific options and functionalities -->
                <ul class="list-unstyled">
                    <li><a class="dashboard-link view-courses" href="view_courses.php">View Courses</a></li>
                    <li><a class="dashboard-link view-grades" href="view_grades.php">View Grades</a></li>
                    <!-- Add more student-specific options here -->
                </ul>
            <?php } ?>

            <!-- Logout link -->
            <a class="logout-link" href="logout.php">Logout</a>
        </div>
        
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>