<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle different requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add item to cart
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $productImage = $_POST['product_image'];
        $userId = 1; // Assuming static user ID for now

        $stmt = $conn->prepare("INSERT INTO cart (product_name, product_price, product_image, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdsi", $productName, $productPrice, $productImage, $userId);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Item added to cart!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add item to cart."]);
        }
        $stmt->close();
    }
    // Remove item from cart
    elseif (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $itemId = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $itemId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Item removed from cart."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to remove item from cart."]);
        }
        $stmt->close();
    }
}
// Get cart items
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = 1; // Assuming static user ID for now
    $result = $conn->query("SELECT * FROM cart WHERE user_id = $userId");

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
    echo json_encode($cartItems);
}

$conn->close();
?>
