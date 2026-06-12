<?php
if (!isset($layout)) {
    $layout = 'site';
}

if (!isset($currentPage)) {
    $currentPage = basename($_SERVER['PHP_SELF']);
}

if (!isset($pageTitle)) {
    switch ($currentPage) {
        case 'index.php':
            $pageTitle = 'ShoeMart - Premium Footwear Experience';
            break;
        case 'sale.php':
            $pageTitle = 'ShoeMart Sale - Grab Your Favorite Shoes';
            break;
        case 'mens.php':
            $pageTitle = "ShoeMart - Men's Footwear";
            break;
        case 'womens.php':
            $pageTitle = "ShoeMart - Women's Footwear";
            break;
        case 'kids.php':
            $pageTitle = 'ShoeMart - Kids Footwear';
            break;
        case 'collections.php':
            $pageTitle = 'ShoeMart - Collections';
            break;
        case 'trending.php':
            $pageTitle = 'ShoeMart - Trending Shoes';
            break;
        case 'cart.php':
            $pageTitle = 'Your Cart - ShoeMart';
            break;
        case 'wishlist.php':
            $pageTitle = 'Your Wishlist - ShoeMart';
            break;
        case 'order.php':
            $pageTitle = 'Your Orders - ShoeMart';
            break;
        case 'login.php':
            $pageTitle = 'User Login';
            break;
        case 'admin_login.php':
            $pageTitle = 'Admin Login';
            break;
        case 'admin_dashboard.php':
            $pageTitle = 'Admin Dashboard - ShoeMart';
            break;
        case 'contact.php':
            $pageTitle = 'Contact Us - EasyKart';
            break;
        case 'privacy.php':
            $pageTitle = 'Privacy Policy - EasyKart';
            break;
        case 'terms.php':
            $pageTitle = 'Terms & Conditions - EasyKart';
            break;
        default:
            $pageTitle = 'EasyKart';
            break;
    }
}

if (!isset($bodyClass)) {
    $bodyClass = 'min-h-screen bg-slate-50 text-slate-900';
}

if (!isset($activePage)) {
    switch ($currentPage) {
        case 'index.php':
            $activePage = 'home';
            break;
        case 'sale.php':
            $activePage = 'sale';
            break;
        case 'mens.php':
            $activePage = 'mens';
            break;
        case 'womens.php':
            $activePage = 'womens';
            break;
        case 'kids.php':
            $activePage = 'kids';
            break;
        case 'collections.php':
            $activePage = 'collections';
            break;
        case 'trending.php':
            $activePage = 'trending';
            break;
        case 'cart.php':
            $activePage = 'cart';
            break;
        case 'wishlist.php':
            $activePage = 'wishlist';
            break;
        case 'order.php':
            $activePage = 'order';
            break;
        default:
            $activePage = '';
            break;
    }
}

if (!isset($user) && isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
}

if (!isset($showPreloader)) {
    $showPreloader = $layout === 'site';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?= htmlspecialchars($pageDescription ?? '') ?>">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<?php if ($layout === 'admin'): ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<?php endif; ?>
<script src="https://cdn.tailwindcss.com"></script>
<?php if (!empty($headExtra)) echo $headExtra; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">
<?php if ($layout === 'site'): ?>
<?php if ($showPreloader): ?>
<div id="preloader" class="fixed inset-0 z-[2000] flex items-center justify-center bg-slate-50">
    <div class="h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600"></div>
</div>
<?php endif; ?>
<header class="sticky top-0 z-50 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <a href="index.php" class="text-2xl font-bold text-slate-900">ShoeMart</a>
            <?php if (!empty($user)): ?>
                <div class="flex items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 lg:hidden">
                    <i class="fa-solid fa-circle-user text-lg text-slate-700"></i>
                    <span class="text-sm font-semibold text-slate-700">Hi, <?= htmlspecialchars($user) ?></span>
                </div>
            <?php endif; ?>
        </div>
        <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-700">
            <a href="index.php" class="rounded px-3 py-2 hover:bg-slate-100 <?= $activePage === 'home' ? 'bg-slate-100 font-semibold text-slate-900' : '' ?>">Home</a>
            <a href="sale.php" class="rounded px-3 py-2 hover:bg-slate-100 <?= $activePage === 'sale' ? 'bg-slate-100 font-semibold text-slate-900' : '' ?>">Sale</a>
            <div class="group relative">
                <a href="#" class="rounded px-3 py-2 hover:bg-slate-100">Category</a>
                <div class="invisible absolute left-0 top-full mt-2 w-44 rounded border border-slate-200 bg-white p-1 opacity-0 shadow-sm transition group-hover:visible group-hover:opacity-100">
                    <a href="mens.php" class="block rounded px-3 py-2 hover:bg-slate-100">Men's</a>
                    <a href="womens.php" class="block rounded px-3 py-2 hover:bg-slate-100">Women's</a>
                    <a href="kids.php" class="block rounded px-3 py-2 hover:bg-slate-100">Kids</a>
                    <a href="collections.php" class="block rounded px-3 py-2 hover:bg-slate-100">Collections</a>
                    <a href="trending.php" class="block rounded px-3 py-2 hover:bg-slate-100">Trending</a>
                </div>
            </div>
            <a href="wishlist.php" class="rounded px-3 py-2 hover:bg-slate-100 <?= $activePage === 'wishlist' ? 'bg-slate-100 font-semibold text-slate-900' : '' ?>">Wishlist</a>
            <a href="cart.php" class="rounded px-3 py-2 hover:bg-slate-100 <?= $activePage === 'cart' ? 'bg-slate-100 font-semibold text-slate-900' : '' ?>">Cart</a>
            <a href="order.php" class="rounded px-3 py-2 hover:bg-slate-100 <?= $activePage === 'order' ? 'bg-slate-100 font-semibold text-slate-900' : '' ?>">Orders</a>
            <a href="admin_login.php" class="rounded px-3 py-2 hover:bg-slate-100">Admin</a>
        </nav>
        <div class="flex flex-wrap items-center gap-2">
            <?php if (!empty($user)): ?>
                <div class="hidden items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 lg:flex">
                    <i class="fa-solid fa-circle-user text-xl text-slate-700"></i>
                    <div class="leading-tight">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500">Welcome</p>
                        <p class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($user) ?></p>
                    </div>
                </div>
                <a href="logout.php" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Logout</a>
            <?php else: ?>
                <a href="login.php?mode=login" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Login</a>
                <a href="login.php?mode=register" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Sign up</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<?php elseif ($layout === 'admin'): ?>
<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Admin</p>
            <h1 class="text-2xl font-bold text-slate-900">ShoeMart Dashboard</h1>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <span class="text-slate-600"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
            <a href="./index.php" class="rounded border border-slate-300 px-3 py-2 font-semibold text-slate-700 hover:bg-slate-100">View Site</a>
            <a href="?action=logout" class="rounded bg-slate-900 px-3 py-2 font-semibold text-white hover:bg-slate-800">Logout</a>
        </div>
    </div>
</header>
<?php elseif ($layout === 'auth'): ?>
<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="index.php" class="text-xl font-bold text-slate-900">ShoeMart</a>
        <a href="index.php" class="rounded border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Back to site</a>
    </div>
</header>
<?php else: ?>
<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="index.php" class="text-xl font-bold text-slate-900">ShoeMart</a>
        <a href="index.php" class="rounded border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Home</a>
    </div>
</header>
<?php endif; ?>

