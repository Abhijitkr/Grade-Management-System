<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Delete the associated grades first
    $stmt = $mysqli->prepare('DELETE FROM grades WHERE student_id = ?');
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $stmt->close();

    // Delete the student
    $stmt = $mysqli->prepare('DELETE FROM students WHERE student_id = ?');
    $stmt->bind_param('i', $student_id);

    if ($stmt->execute()) {
        header('Location: view_students.php');
        exit();
    } else {
        echo 'Failed to delete the student.';
    }

    $stmt->close();
} else {
    echo 'Invalid student ID.';
}
?>
