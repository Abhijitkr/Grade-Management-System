<?php
// Start a session
session_start();

// Check if the user is not logged in or is not an instructor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    // Redirect to the login page or access denied page
    header('Location: index.php');
    exit();
}

// Include the db_connection.php file
require_once 'db_connection.php';

// Define variables to store the input values and error messages
$student_id = '';
$course_id = '';
$grade = '';
$error = '';

// Retrieve the list of students and courses from the database
$stmt_students = $mysqli->prepare('SELECT * FROM students');
$stmt_students->execute();
$result_students = $stmt_students->get_result();
$students = $result_students->fetch_all(MYSQLI_ASSOC);
$stmt_students->close();

$stmt_courses = $mysqli->prepare('SELECT * FROM courses');
$stmt_courses->execute();
$result_courses = $stmt_courses->get_result();
$courses = $result_courses->fetch_all(MYSQLI_ASSOC);
$stmt_courses->close();

// Check if a grade ID is provided in the URL
if (isset($_GET['id'])) {
    $grade_id = $_GET['id'];

    // Retrieve the existing grade from the database
    $stmt_grade = $mysqli->prepare('SELECT * FROM grades WHERE grade_id = ?');
    $stmt_grade->bind_param('i', $grade_id);
    $stmt_grade->execute();
    $result_grade = $stmt_grade->get_result();
    $existing_grade = $result_grade->fetch_assoc();
    $stmt_grade->close();

    // Check if the grade exists
    if ($existing_grade) {
        // Populate the form fields with the existing grade values
        $student_id = $existing_grade['student_id'];
        $course_id = $existing_grade['course_id'];
        $grade = $existing_grade['grade'];
    } else {
        // Redirect to an error page or display an error message
        header('Location: error.php');
        exit();
    }
}

// Process the assign grade form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the input values
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];

    // Validate the input values (perform additional validation if necessary)

    // Check if any required fields are empty
    if (empty($student_id) || empty($course_id) || empty($grade)) {
        $error = 'Please fill in all the required fields.';
    } else {
        // Prepare and execute the database query to assign/update grade for the student
        if (isset($_GET['id'])) {
            // Update the existing grade
            $stmt = $mysqli->prepare('UPDATE grades SET student_id = ?, course_id = ?, grade = ? WHERE grade_id = ?');
            $stmt->bind_param('iisi', $student_id, $course_id, $grade, $grade_id);
        } else {
            // Assign a new grade
            $stmt = $mysqli->prepare('INSERT INTO grades (student_id, course_id, grade) VALUES (?, ?, ?)');
            $stmt->bind_param('iis', $student_id, $course_id, $grade);
        }

        if ($stmt->execute()) {
            // Redirect to the assigned grades page or show a success message
            header('Location: view_grades.php');
            exit();
        } else {
            // Display an error message
            $error = 'Failed to assign/update grade.';
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($_GET['id']) ? 'Update Grade' : 'Assign Grade'; ?></title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 30px;
            text-align: center;
            font-size: 30px;
        }

        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
        }

        select {
            padding-right: 30px; /* Adjust the padding to shift the arrows */
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            margin-bottom: 20px;
        }

        input[type="text"]::placeholder {
            color: #b0b0b0;
        }

        input[type="submit"] {
            width: auto;
            padding: 10px 30px;
            border-radius: 5px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo isset($_GET['id']) ? 'Update Grade' : 'Assign Grade'; ?></h1>

        <!-- Display any error messages -->
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <!-- Assign grade form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="student_id">Student:</label>
                <select id="student_id" name="student_id" class="form-control">
                    <option value="">Select Student</option>
                    <?php foreach ($students as $student) { ?>
                        <option value="<?php echo $student['student_id']; ?>" <?php if ($student_id == $student['student_id']) echo 'selected'; ?>><?php echo $student['student_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="course_id">Course:</label>
                <select id="course_id" name="course_id" class="form-control">
                    <option value="">Select Course</option>
                    <?php foreach ($courses as $course) { ?>
                        <option value="<?php echo $course['course_id']; ?>" <?php if ($course_id == $course['course_id']) echo 'selected'; ?>><?php echo $course['course_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="grade">Grade:</label>
                <input type="text" id="grade" name="grade" value="<?php echo $grade; ?>" class="form-control" placeholder="Enter grade">
            </div>
            <div>
                <input type="submit" value="<?php echo isset($_GET['id']) ? 'Update Grade' : 'Assign Grade'; ?>" class="btn btn-primary">
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
