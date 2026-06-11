<?php
session_start();
include('db.php');

header('Content-Type: application/json');

$user = $_SESSION['username'] ?? '';
if(!$user){
    echo json_encode(['success' => false, 'message' => 'Please login first!']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $product_id = intval($_POST['product_id'] ?? 0);
    if(!$product_id){
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }

    // Check if product already in cart
    $check = $conn->prepare("SELECT id FROM cart WHERE username = ? AND product_id = ?");
    $check->bind_param("si", $user, $product_id);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        echo json_encode(['success' => false, 'message' => 'Product already in cart!']);
        exit;
    }

    // Fetch product details
    $stmt = $conn->prepare("SELECT name, brand, price, image_link, discount FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    $p = $result->fetch_assoc();

    // Insert into cart
    $insert = $conn->prepare("INSERT INTO cart (username, product_id, name, brand, price, image_link, discount, added_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $insert->bind_param("sissdsi", $user, $product_id, $p['name'], $p['brand'], $p['price'], $p['image_link'], $p['discount']);
    $insert->execute();

    echo json_encode(['success' => true, 'message' => 'Product added to cart!']);
}
