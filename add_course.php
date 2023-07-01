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
$course_code = '';
$course_name = '';
$error = '';

// Process the add course form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the input values
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];

    // Validate the input values (perform additional validation if necessary)

    // Check if any required fields are empty
    if (empty($course_code) || empty($course_name)) {
        $error = 'Please fill in all the required fields.';
    } else {
        // Prepare and execute the database query to add the course
        $stmt = $mysqli->prepare('INSERT INTO courses (course_code, course_name) VALUES (?, ?)');
        $stmt->bind_param('ss', $course_code, $course_name);
        
        if ($stmt->execute()) {
            // Redirect to the course list page or show a success message
            header('Location: view_courses.php');
            exit();
        } else {
            // Display an error message
            $error = 'Failed to add the course.';
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group .error {
            color: #ff0000;
            margin-top: 5px;
        }

        .form-group .success {
            color: #008000;
            margin-top: 5px;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add Course</h1>

        <!-- Display any error messages -->
        <?php if (!empty($error)) { ?>
            <div class="form-group error"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Display success message if applicable -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) { ?>
            <div class="form-group success">Course added successfully!</div>
        <?php } ?>

        <!-- Add course form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="course_code">Course Code:</label>
                <input type="text" id="course_code" name="course_code" value="<?php echo $course_code; ?>">
            </div>
            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" value="<?php echo $course_name; ?>">
            </div>
            <div class="form-group">
                <input type="submit" value="Add Course">
            </div>
        </form>
    </div>
</body>
</html>
