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
$student_id = '';
$student_name = '';
$student_email = '';
$student_phone = '';
$date_of_birth = '';
$address = '';
$error = '';

// Process the edit student form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the input values
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $student_phone = $_POST['student_phone'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];

    // Validate the input values (perform additional validation if necessary)

    // Check if any required fields are empty
    if (empty($student_name) || empty($student_email) || empty($student_phone) || empty($date_of_birth) || empty($address)) {
        $error = 'Please fill in all the required fields.';
    } else {
        // Prepare and execute the database query to update the student details
        $stmt = $mysqli->prepare('UPDATE students SET student_name = ?, student_email = ?, student_phone = ?, date_of_birth = ?, address = ? WHERE student_id = ?');
        $stmt->bind_param('sssssi', $student_name, $student_email, $student_phone, $date_of_birth, $address, $student_id);

        if ($stmt->execute()) {
            // Redirect to the student list page or show a success message
            header('Location: view_students.php');
            exit();
        } else {
            // Display an error message
            $error = 'Failed to update the student details.';
        }

        // Close the statement
        $stmt->close();
    }
} else {
    // Retrieve the student ID from the query string parameter
    if (isset($_GET['id'])) {
        $student_id = $_GET['id'];

        // Retrieve the student details from the database
        $stmt = $mysqli->prepare('SELECT * FROM students WHERE student_id = ?');
        $stmt->bind_param('i', $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($student) {
            // Populate the input fields with the student details
            $student_name = $student['student_name'];
            $student_email = $student['student_email'];
            $student_phone = $student['student_phone'];
            $date_of_birth = $student['date_of_birth'];
            $address = $student['address'];

            // Close the statement
            $stmt->close();
        } else {
            // Display an error message
            $error = 'Invalid student ID.';
        }
    } else {
        // Display an error message
        $error = 'Invalid student ID.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Edit Student</h1>

    <!-- Display any error messages -->
    <?php if (!empty($error)) { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <!-- Edit student form -->
    <form method="POST" action="">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
        <div>
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" value="<?php echo $student_name; ?>">
        </div>
        <div>
            <label for="student_email">Student Email:</label>
            <input type="email" id="student_email" name="student_email" value="<?php echo $student_email; ?>">
        </div>
        <div>
            <label for="student_phone">Student Phone:</label>
            <input type="text" id="student_phone" name="student_phone" value="<?php echo $student_phone; ?>">
        </div>
        <div>
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea id="address" name="address"><?php echo $address; ?></textarea>
        </div>
        <div>
            <input type="submit" value="Update Student">
        </div>
    </form>
</body>
</html>
