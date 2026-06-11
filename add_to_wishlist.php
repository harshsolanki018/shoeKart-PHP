<?php
session_start();
include('db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add to wishlist.']);
    exit;
}

$user = $_SESSION['username'];
$product_id = intval($_POST['product_id'] ?? 0);

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    exit;
}

try {
    // Check if already in wishlist
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE username=? AND product_id=?");
    $stmt->bind_param("si", $user, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Product already in wishlist.']);
        exit;
    }

    // Insert into wishlist
    $stmt = $conn->prepare("INSERT INTO wishlist (username, product_id) VALUES (?, ?)");
    $stmt->bind_param("si", $user, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
