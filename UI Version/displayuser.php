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

// Function to retrieve all user data from the database
function getAllUserData($conn) {
    // SQL query to retrieve all user data
    $sql = 'SELECT * FROM user';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = [];
        while ($row = $result->fetch_assoc()) {
            $userData[] = $row;
        }
        return $userData;
    } else {
        return null;
    }
}

// Get all user data from the database
$usersData = getAllUserData($conn);

// Send the user data back to the JavaScript code
echo json_encode(['usersData' => $usersData]);

// Close the database connection
$conn->close();
?>
