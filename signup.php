<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root"; // Change this if necessary
$password = ""; // Change this if necessary
$dbname = "hiking_community"; // Your database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';

    // Validate passwords match
    if ($password !== $confirm_password) {
        echo "<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL to insert data
        $stmt = $conn->prepare("INSERT INTO login (name, email, password) VALUES (?, ?, ?)");

        // Check if the statement was prepared correctly
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters to the SQL query
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to success page upon successful registration
            header("Location: signup_success.html");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
