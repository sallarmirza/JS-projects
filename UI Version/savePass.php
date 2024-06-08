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

// Function to insert Password data into the database
function insertPassData($website, $pass, $conn) {

	$hashedpass = $_POST['pass'];

    // Use prepared statements to prevent SQL injection
    $checkSql = "SELECT * FROM password WHERE pass = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $hashedpass);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Password already exists in the database
        return ['success' => false, 'message' => 'Password already exists.'];
    } else {
        // Password does not exist, proceed to insert
        $insertSql = "INSERT INTO password (website, pass) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ss", $website, $hashedpass);

        if ($insertStmt->execute()) {
            // Password saved successfully
            return ['success' => true, 'message' => 'Password saved successfully.'];
        } else {
            // Failed to save password
            return ['success' => false, 'message' => 'Failed to save password.'];
        }
    }
}

// Get Password data from the registration form
$website = $_POST['website'];
$pass = $_POST['pass'];

// Insert Password data into the database
$savePassSuccess = insertPassData($website, $pass, $conn);

// Send a response back to the JavaScript code
echo json_encode($savePassSuccess);

// Close the database connection
$conn->close();
?>





