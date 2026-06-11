<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';

try {
    $women_products = [];
    $result = $conn->query("SELECT * FROM products WHERE is_sale=0 AND id > 12 ORDER BY id ASC LIMIT 8");
    if ($result) {
        $women_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log('DB error: ' . $e->getMessage());
    $women_products = [];
}

include 'header.php';

$catalogKicker = 'Women';
$catalogHeadline = "Women's Footwear";
$catalogDescription = 'Elegant and comfortable shoes for every occasion, shown in a clean and simple browsing layout.';
$catalogImage = 'https://media.istockphoto.com/id/1080279596/photo/brides-wedding-shoe.jpg?s=612x612&w=0&k=20&c=Mns7yMj0DwUKLwHe56yxJYBEe0Zrct0ZbLgCmu0-0ts=';
$catalogSectionTitle = "Women's Collection";
$catalogSectionText = 'Minimal, practical choices for everyday wear and special moments.';
$catalogProducts = $women_products;
$catalogInitialLimit = 8;

include 'catalog-page.php';
include 'footer.php';
