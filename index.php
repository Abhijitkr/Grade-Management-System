<?php
// Start a session
session_start();

// Include the db_connection.php file
require_once 'db_connection.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the dashboard or home page
    header('Location: dashboard.php');
    exit();
}

// Define variables to store the input values and error messages
$username = '';
$password = '';
$error = '';

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input values (perform additional validation if necessary)

    // Check if the username and password are not empty
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Prepare and execute the database query
        $stmt = $mysqli->prepare('SELECT user_id, role FROM users WHERE username = ? AND password = ?');
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $stmt->store_result();

        // Check if a user with the provided username and password exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $role);
            $stmt->fetch();
            
            // Store the user ID and role in the session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            // Redirect to the dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            // Display an error message
            $error = 'Invalid username or password.';
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f2f2;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .container .error {
            color: #ff0000;
            margin-bottom: 10px;
        }

        .container .login-form label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <!-- Display any error messages -->
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- Login form -->
        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
