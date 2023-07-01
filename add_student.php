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
$student_name = '';
$student_email = '';
$student_phone = '';
$date_of_birth = '';
$address = '';
$error = '';

// Process the add student form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the input values
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
        // Prepare and execute the database query to insert the new student
        $stmt = $mysqli->prepare('INSERT INTO students (student_name, student_email, student_phone, date_of_birth, address) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $student_name, $student_email, $student_phone, $date_of_birth, $address);
        
        if ($stmt->execute()) {
            // Redirect to the student list page or show a success message
            header('Location: view_students.php');
            exit();
        } else {
            // Display an error message
            $error = 'Failed to add the student.';
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
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

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .date-input {
            position: relative;
            display: inline-block;
            width: 100%;
            margin-bottom: 20px;
        }

        .date-input input[type="date"] {
            width: calc(100% - 20px); /* Subtract the width of the arrow */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .date-input::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
        }

        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }

        .add-student-btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-student-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Add Student</h1>

    <!-- Display any error messages -->
    <?php if (!empty($error)) { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <!-- Add student form -->
    <form method="POST" action="">
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
        <div class="date-input">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea id="address" name="address"><?php echo $address; ?></textarea>
        </div>
        <div>
            <input type="submit" value="Add Student" class="add-student-btn">
        </div>
    </form>
</body>
</html>

