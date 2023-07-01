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

// Check if the course ID is provided
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Prepare and execute the database query to delete the course
    $stmt = $mysqli->prepare('DELETE FROM courses WHERE course_id = ?');
    $stmt->bind_param('i', $course_id);

    if ($stmt->execute()) {
        // Redirect to the course list page or show a success message
        header('Location: view_courses.php');
        exit();
    } else {
        // Display an error message
        $error = 'Failed to delete the course.';
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the course list page or display an error message
    header('Location: view_courses.php');
    exit();
}
