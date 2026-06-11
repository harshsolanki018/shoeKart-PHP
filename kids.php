<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';

try {
    $kids_products = [];
    $result = $conn->query("SELECT * FROM products WHERE is_sale=0 ORDER BY id ASC LIMIT 8");
    if ($result) {
        $kids_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log('DB error: ' . $e->getMessage());
    $kids_products = [];
}

include 'header.php';

$catalogKicker = 'Kids';
$catalogHeadline = "Kids' Footwear";
$catalogDescription = 'Comfort-first footwear for active days, presented in a simple layout that stays easy to scan.';
$catalogImage = 'https://media.istockphoto.com/id/1464974518/photo/childrens-sports-shoes-with-laces-on-a-white-background-top-view.jpg?s=612x612&w=0&k=20&c=EtO6e7cikX2L4rDcnv7LQqK5a3K7jvC0P5n0Qz0jQ8g=';
$catalogSectionTitle = "Kids' Collection";
$catalogSectionText = 'Lightweight picks made for comfort, movement, and easy browsing.';
$catalogProducts = $kids_products;
$catalogInitialLimit = 8;

include 'catalog-page.php';
include 'footer.php';
