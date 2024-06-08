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

// Function to insert user data into the database
function insertUserData($name, $username, $email, $pin, $conn) {
    
	$hashedPin = $_POST['pin'];

    // Use prepared statements to prevent SQL injection
    $checkSql = "SELECT * FROM user WHERE username = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // User already exists in the database
        return ['success' => false, 'message' => 'User already exists.'];
    } else {
        // PIN does not exist, proceed to insert
        $insertSql = "INSERT INTO user (name, username, email, pin) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ssss", $name, $username, $email, $hashedPin);

        if ($insertStmt->execute()) {
            // User data saved successfully
            return ['success' => true, 'message' => 'Registration successfully.'];
        } else {
            // Failed to save user data
            return ['success' => false, 'message' => 'Failed to save user data.'];
        }
    }
}

// Get user data from the registration form
$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$pin = $_POST['pin'];

// Insert user data into the database
$registrationSuccess = insertUserData($name, $username, $email, $pin, $conn);

// Send a response back to the JavaScript code
echo json_encode($registrationSuccess);

// Close the database connection
$conn->close();
?>