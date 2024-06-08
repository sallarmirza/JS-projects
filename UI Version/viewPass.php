<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = ''; // You might need to set a password here
$database = 'userdata';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Function to retrieve all password data from the database
function getAllPasswordData($conn) {
    // SQL query to retrieve all password data
    $sql = 'SELECT * FROM password';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $passwordData = [];
        while ($row = $result->fetch_assoc()) {
            $passwordData[] = $row;
        }
        return $passwordData;
    } else {
        return null;
    }
}

// Get all password data from the database
$passwordsData = getAllPasswordData($conn);

// Send the password data back to the JavaScript code
echo json_encode(['userData' => $passwordsData]);

// Close the database connection
$conn->close();
?>
