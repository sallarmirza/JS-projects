<?php
// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "userdata";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

// Get user data from the login form
$username = $_POST['username'];
$pin = $_POST['pin'];

// Validate user credentials against the database
$sql = "SELECT * FROM user WHERE username='$username'";
$result = $conn->query($sql);

if ($result === false) {
    // Handle query error
    echo json_encode(['loginSuccess' => false, 'error' => $conn->error]);
} else {
    if ($result->num_rows > 0) {
        // User found, check the hashed PIN
        $row = $result->fetch_assoc();
        $storedHashedPin = $row['pin'];

        if ($pin === $storedHashedPin) {
            // PIN is correct, login successful
            // Start the session
            session_start();
            
            // Store the username in the session
            $_SESSION['username'] = $username;

            echo json_encode(['loginSuccess' => true]);
        } else {
            // PIN is incorrect, login failed
            echo json_encode(['loginSuccess' => false, 'error' => 'Incorrect PIN']);
        }
    } else {
        // User not found, login failed
        echo json_encode(['loginSuccess' => false]);
    }
}

// Close the database connection
$conn->close();
?>
