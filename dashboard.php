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

        .logout-link {
            display: block;
            margin-top: 20px;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .logout-link:hover {
            color: #000;
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
                <!-- Display admin-specific options and functionalities -->
                <ul class="list-unstyled">
                    <li><a class="dashboard-link add" href="add_student.php">Add Student</a></li>
                    <li><a class="dashboard-link view" href="view_students.php">View Students</a></li>
                    <li><a class="dashboard-link add" href="add_course.php">Add Course</a></li>
                    <li><a class="dashboard-link view" href="view_courses.php">View Courses</a></li>
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

