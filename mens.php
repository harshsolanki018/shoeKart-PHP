<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';

try {
    $men_products = [];
    $result = $conn->query("SELECT * FROM products WHERE is_sale=0 ORDER BY id ASC LIMIT 8");
    if ($result) {
        $men_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log('DB error: ' . $e->getMessage());
    $men_products = [];
}

include 'header.php';

$catalogKicker = 'Men';
$catalogHeadline = "Men's Footwear";
$catalogDescription = 'Premium shoes for everyday wear, built around a simple layout that keeps browsing fast and clear.';
$catalogImage = 'https://media.istockphoto.com/id/1336201997/photo/brown-boots-on-beige-background-trendy-autumn-accessories.jpg?s=612x612&w=0&k=20&c=AtIOraXbJRo_s6Mic1CxiEYhY1C1HU9hMdrGz8R8yGI=';
$catalogSectionTitle = "Men's Collection";
$catalogSectionText = 'A focused selection of shoes for work, travel, and casual use.';
$catalogProducts = $men_products;
$catalogInitialLimit = 8;

include 'catalog-page.php';
include 'footer.php';
