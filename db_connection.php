<?php

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'school';

// Create a new MySQLi instance
$mysqli = new mysqli($host, $username, $password, $database);

// Check the connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Set the character set to UTF-8
$mysqli->set_charset('utf8mb4');

?>
