<?php
session_start();
include('db.php');
header('Content-Type: application/json');

if(!isset($_SESSION['username'])){
    echo json_encode(['success'=>false,'message'=>'Please login first']);
    exit;
}

$user = $_SESSION['username'];
$product_id = $_POST['product_id'] ?? null;

if(!$product_id){
    echo json_encode(['success'=>false,'message'=>'Product ID missing']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE username=? AND product_id=?");
    $stmt->bind_param("si",$user,$product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE username=? AND product_id=?");
        $stmt->bind_param("si",$user,$product_id);
        $stmt->execute();
        echo json_encode(['success'=>true,'action'=>'removed']);
    } else {
        $stmt = $conn->prepare("INSERT INTO wishlist (username, product_id) VALUES (?,?)");
        $stmt->bind_param("si",$user,$product_id);
        $stmt->execute();
        echo json_encode(['success'=>true,'action'=>'added']);
    }
} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
?>
