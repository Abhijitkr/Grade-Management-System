<?php
// Start a session
session_start();

// Check if the user is not logged in or is not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page or access denied page
    header('Location: index.php');
    exit();
}

// Include the db_connection.php file
require_once 'db_connection.php';

// Define variables to store the input values and error messages
$course_id = '';
$course_code = '';
$course_name = '';
$error = '';

// Process the edit course form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the input values
    $course_id = $_POST['course_id'];
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];

    // Validate the input values (perform additional validation if necessary)

    // Check if any required fields are empty
    if (empty($course_code) || empty($course_name)) {
        $error = 'Please fill in all the required fields.';
    } else {
        // Prepare and execute the database query to update the course details
        $stmt = $mysqli->prepare('UPDATE courses SET course_code = ?, course_name = ? WHERE course_id = ?');
        $stmt->bind_param('ssi', $course_code, $course_name, $course_id);

        if ($stmt->execute()) {
            // Redirect to the course list page or show a success message
            header('Location: view_courses.php');
            exit();
        } else {
            // Display an error message
            $error = 'Failed to update the course details.';
        }

        // Close the statement
        $stmt->close();
    }
} else {
    // Retrieve the course ID from the query string parameter
    if (isset($_GET['id'])) {
        $course_id = $_GET['id'];

        // Retrieve the course details from the database
        $stmt = $mysqli->prepare('SELECT * FROM courses WHERE course_id = ?');
        $stmt->bind_param('i', $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();

        if ($course) {
            // Populate the input fields with the course details
            $course_code = $course['course_code'];
            $course_name = $course['course_name'];

            // Close the statement
            $stmt->close();
        } else {
            // Redirect to the course list page or display an error message
            header('Location: view_courses.php');
            exit();
        }
    } else {
        // Redirect to the course list page or display an error message
        header('Location: view_courses.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include your CSS stylesheets -->
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            background-color: #f9f9f9;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
            color: #555555;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #4e8cff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3d73e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Course</h1>

        <!-- Display any error messages -->
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Edit course form -->
        <form method="POST" action="">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <div class="form-group">
                <label for="course_code">Course Code:</label>
                <input type="text" id="course_code" name="course_code" class="form-control" value="<?php echo $course_code; ?>">
            </div>
            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" class="form-control" value="<?php echo $course_name; ?>">
            </div>
            <div class="form-group text-center">
                <input type="submit" value="Update Course" class="btn btn-primary btn-lg">
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
