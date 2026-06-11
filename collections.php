<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';

try {
    $collection_products = [];
    $result = $conn->query("SELECT * FROM products WHERE category='collection' ORDER BY id ASC LIMIT 8");
    if ($result) {
        $collection_products = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log('DB error: ' . $e->getMessage());
    $collection_products = [];
}

include 'header.php';

$catalogKicker = 'Collections';
$catalogHeadline = 'Collections';
$catalogDescription = 'A curated selection of styles grouped for quick browsing and a calmer shopping flow.';
$catalogImage = 'https://media.istockphoto.com/id/1152952228/photo/indian-made-mens-shoes-with-box.jpg?s=612x612&w=0&k=20&c=Y8SzsBb_zrSE0UwBn4UuEg4tS5tEt0or9fQ1kPRbeHI=';
$catalogSectionTitle = 'Collections';
$catalogSectionText = 'Useful for shoppers who want a simple browse-first experience.';
$catalogProducts = $collection_products;
$catalogInitialLimit = 8;

include 'catalog-page.php';
include 'footer.php';
