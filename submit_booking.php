<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root"; // Change this if you have a different username
$password = ""; // Change this if you have a different password
$dbname = "hiking_community"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into the database when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $age = isset($_POST['age']) ? (int)$_POST['age'] : 0; // Cast to int for safety
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO hiking_community (name, age, phone) VALUES (?, ?, ?)");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sis", $name, $age, $phone); // Change "sss" to "sis" to match data types

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the success page
        header("Location: hiking1.html");
        exit(); // Ensure no further code is executed
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
