<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';

try {
    $all_products = [];
    $result = $conn->query("SELECT * FROM products WHERE is_sale=1 ORDER BY discount DESC, price ASC LIMIT 12");
    if ($result) {
        $all_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log('DB error: ' . $e->getMessage());
    $all_products = [];
}

include 'header.php';

$catalogKicker = 'Sale';
$catalogHeadline = 'Sale Shoes';
$catalogDescription = 'Simple sale listings with the focus on price, discount, and quick checkout actions.';
$catalogImage = 'https://images.unsplash.com/photo-1646139498425-5bceb422ba6a?w=1200&auto=format&fit=crop&q=80';
$catalogSectionTitle = 'Top sale picks';
$catalogSectionText = 'A quick view of the best discounted shoes without extra clutter.';
$catalogProducts = $all_products;
$catalogInitialLimit = 12;

include 'catalog-page.php';
include 'footer.php';
