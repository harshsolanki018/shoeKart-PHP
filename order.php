<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';
if (!$user) {
    header('Location: login.php');
    exit();
}

$orders = [];
$stmt = $conn->prepare("SELECT o.*, p.name, p.brand, p.price, p.discount, p.image_link FROM orders o JOIN products p ON o.product_id = p.id WHERE o.username = ? ORDER BY o.id DESC");
$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    $orders = $result->fetch_all(MYSQLI_ASSOC);
}
$stmt->close();

include 'header.php';
?>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Orders</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Your Orders</h1>
                <p class="mt-2 text-sm text-slate-500">A quick history of the orders you placed on the site.</p>
            </div>
            <a href="index.php" class="inline-flex rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Continue shopping</a>
        </div>
    </section>

    <?php if (isset($_GET['ordered'])): ?>
        <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">Order placed successfully.</div>
    <?php endif; ?>

    <?php if (count($orders) === 0): ?>
        <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <p class="text-lg font-semibold text-slate-900">No orders yet.</p>
            <p class="mt-2 text-sm text-slate-500">Your order history will appear here after checkout.</p>
            <a href="index.php" class="mt-6 inline-flex rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Browse products</a>
        </div>
    <?php else: ?>
        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($orders as $order):
                $final_price = $order['discount'] > 0 ? $order['price'] - ($order['price'] * $order['discount'] / 100) : $order['price'];
            ?>
                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="aspect-[4/3] bg-slate-100">
                        <img src="<?= htmlspecialchars($order['image_link']) ?>" alt="<?= htmlspecialchars($order['name']) ?>" class="h-full w-full object-cover">
                    </div>
                    <div class="p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600"><?= htmlspecialchars($order['brand']) ?></p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-900"><?= htmlspecialchars($order['name']) ?></h2>
                        <p class="mt-2 text-sm text-slate-500">Size: <?= htmlspecialchars($order['size']) ?> | Color: <?= htmlspecialchars($order['color']) ?></p>
                        <p class="mt-2 text-sm text-slate-500">Contact: <?= htmlspecialchars($order['contact']) ?></p>
                        <p class="mt-2 text-sm text-slate-500">Email: <?= htmlspecialchars($order['email']) ?></p>
                        <p class="mt-2 text-sm text-slate-500">Address: <?= htmlspecialchars($order['address']) ?></p>
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <p class="text-lg font-bold text-slate-900">₹<?= number_format($final_price, 2) ?></p>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700"><?= htmlspecialchars($order['status'] ?? 'pending') ?></span>
                        </div>
                        <p class="mt-3 text-xs text-slate-400">Placed on <?= htmlspecialchars($order['order_date'] ?? '') ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
