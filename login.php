<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "hiking_community"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process the login form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the email exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, log the user in (set session or cookie)
            session_start(); // Start a session
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['email'] = $email; // Save email in session
            header("Location: login_success.html"); // Redirect to the success page
            exit();
        } else {
            echo "<div class='alert alert-danger'>Invalid password!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No account found with that email!</div>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
