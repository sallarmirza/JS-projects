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

// Function to delete user data from the database
function deleteUser($username, $conn) {
    // SQL query to delete user by username
    $sql = "DELETE FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Rows affected, user deleted successfully
            return ['success' => true, 'message' => 'User deleted successfully.'];
        } else {
            // No rows affected, user not found
            return ['success' => false, 'message' => 'User not found.'];
        }
    } else {
        // Execution failed
        return ['success' => false, 'message' => 'Error: ' . $stmt->error];
    }
}

// Get username from the POST request
$username = $_POST['username'];

// Delete user data from the database
$result = deleteUser($username, $conn);

// Send a response back to the JavaScript code
echo json_encode($result);

// Close the database connection
$conn->close();
?>
