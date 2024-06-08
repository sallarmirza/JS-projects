<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Database connection parameters
$host = "localhost";
$usernameDB = "root";
$passwordDB = "";
$database = "userdata";

// Create a connection
$conn = new mysqli($host, $usernameDB, $passwordDB, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data based on the username
$sql = "SELECT * FROM user WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check for query error
if ($result === false) {
    // Handle query error
    echo json_encode(['error' => $conn->error]);
} else {
    if ($result->num_rows > 0) {
        // User found, fetch user data
        $userData = $result->fetch_assoc();

        // Check if userData is not empty before echoing JSON
        if (!empty($userData)) {
            // Return user data as JSON
            echo json_encode(['userData' => $userData]);
        } else {
            // User data is empty
            echo json_encode(['error' => 'User data is empty']);
        }
    } else {
        // User not found
        echo json_encode(['error' => 'User not found']);
    }
}

// Close the database connection
$conn->close();
?>
