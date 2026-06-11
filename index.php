<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';

try {
    $all_products = [];
    $result = $conn->query("SELECT * FROM products WHERE is_sale=0 ORDER BY id ASC LIMIT 90");
    if ($result) {
        $all_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log('DB error: ' . $e->getMessage());
    $all_products = [];
}

include 'header.php';

$catalogKicker = 'Fresh arrivals';
$catalogHeadline = 'Step into style';
$catalogDescription = 'Discover premium footwear with a cleaner layout, faster browsing, and simple actions that stay out of the way.';
$catalogImage = 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=1600&auto=format&fit=crop&q=80';
$catalogSectionTitle = 'Featured footwear';
$catalogSectionText = 'A straightforward selection of everyday trainers, casual shoes, and simple statement styles.';
$catalogProducts = $all_products;
$catalogInitialLimit = 12;

include 'catalog-page.php';
include 'footer.php';
