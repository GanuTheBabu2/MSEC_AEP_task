<?php
$servername = "localhost";
$username = "root"; // default username for XAMPP
$password = ""; // default password for XAMPP
$dbname = "demo_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize user input
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM teacher WHERE T_mail = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful login
        $response['status'] = 'success';
    } else {
        // Failed login
        $response['status'] = 'error';
        $response['message'] = 'Invalid credentials. Please try again.';
    }

    $stmt->close();
}

$conn->close();

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
