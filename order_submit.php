<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $product_id = intval($_POST['product_id']);
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';
    $size = $_POST['size'] ?? '';
    $color = $_POST['color'] ?? '';

    if($user && $product_id && $email && $contact && $address && $size && $color){
        $stmt = $conn->prepare("INSERT INTO orders (username, product_id, gender, email, contact, address, size, color, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sissssss", $user, $product_id, $gender, $email, $contact, $address, $size, $color);
        if($stmt->execute()){
            // ✅ Redirect to order page immediately after successful order
            header("Location: order.php");
            exit();
        } else {
            die("Order failed: ".$conn->error);
        }
    } else {
        die("All fields are required.");
    }
} else {
    header("Location: index.php");
    exit();
}
