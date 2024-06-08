<?php
// Database connection parameters
$host = "localhost";
$website = "root";
$password = "";
$database = "userdata";

// Create a connection
$conn = new mysqli($host, $website, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to delete Password data from the database
function deletePass($website, $conn) {
    // SQL query to delete Password by website
    $sql = "DELETE FROM password WHERE website = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $website);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Rows affected, Password deleted successfully
            return ['success' => true, 'message' => 'Password deleted successfully.'];
        } else {
            // No rows affected, Password not found
            return ['success' => false, 'message' => 'Website not found.'];
        }
    } else {
        // Execution failed
        return ['success' => false, 'message' => 'Error: ' . $stmt->error];
    }
}

// Get website from the POST request
$website = $_POST['website'];

// Delete Password data from the database
$result = deletePass($website, $conn);

// Send a response back to the JavaScript code
echo json_encode($result);

// Close the database connection
$conn->close();
?>
