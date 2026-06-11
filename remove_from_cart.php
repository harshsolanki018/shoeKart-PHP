<?php
session_start();
include('db.php');

$user = $_SESSION['username'] ?? '';
if(!$user){
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $id, $user);
    $stmt->execute();
}

header('Location: cart.php');
exit;
