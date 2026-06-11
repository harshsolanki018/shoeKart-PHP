<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';

try {
    $trending_products = [];
    $result = $conn->query("SELECT * FROM products WHERE trending=1 ORDER BY id ASC LIMIT 8");
    if ($result) {
        $trending_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log('DB error: ' . $e->getMessage());
    $trending_products = [];
}

include 'header.php';

$catalogKicker = 'Trending';
$catalogHeadline = 'Trending Shoes';
$catalogDescription = 'A clean snapshot of shoes people are looking at most right now.';
$catalogImage = 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=1200&auto=format&fit=crop&q=80';
$catalogSectionTitle = 'Trending shoes';
$catalogSectionText = 'These picks are kept simple so the product details stay easy to compare.';
$catalogProducts = $trending_products;
$catalogInitialLimit = 8;

include 'catalog-page.php';
include 'footer.php';
