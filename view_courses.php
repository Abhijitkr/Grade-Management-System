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

// Retrieve the course details from the database
$stmt = $mysqli->prepare('SELECT * FROM courses');
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Courses</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include your CSS stylesheets -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>View Courses</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <?php if ($_SESSION['role'] === 'admin') { ?>
                        <th>Action</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course) { ?>
                    <tr>
                        <td><?php echo $course['course_code']; ?></td>
                        <td><?php echo $course['course_name']; ?></td>
                        <?php if ($_SESSION['role'] === 'admin') { ?>
                            <td>
                                <a href="edit_course.php?id=<?php echo $course['course_id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete_course.php?id=<?php echo $course['course_id']; ?>" onclick="return confirm('Are you sure you want to delete this course?')" class="btn btn-danger">Delete</a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
