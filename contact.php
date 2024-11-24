<?php
// Database connection variables
$host = "localhost";
$db_name = 'ecommerce';
$db_user = "root";
$db_pass = "";

// Create a connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check the connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the form fields are not empty
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }

    // Sanitize form inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Prepare SQL statement using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    
    // Check if the prepare statement is successful
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error preparing the SQL statement.']);
        exit();
    }

    // Bind parameters
    $stmt->bind_param("sss", $name, $email, $message); // 'sss' denotes three string parameters

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Your message has been successfully sent.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'There was an error submitting your message. Please try again later.']);
    }

    // Close the prepared statement and the database connection
    $stmt->close();
    $conn->close();
}
?>
