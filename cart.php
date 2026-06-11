<?php
session_start();
include 'db.php';

$user = $_SESSION['username'] ?? '';
if (!$user) {
    header('Location: login.php');
    exit();
}

$cart_items = [];
$stmt = $conn->prepare("SELECT c.id AS cart_id, p.id AS product_id, p.name, p.brand, p.price, p.discount, p.image_link FROM cart c JOIN products p ON c.product_id = p.id WHERE c.username = ?");
$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);
    $del = $conn->prepare("DELETE FROM cart WHERE id = ? AND username = ?");
    $del->bind_param('is', $remove_id, $user);
    if ($del->execute()) {
        header('Location: cart.php?removed=1');
        exit();
    }
    $del->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    $product_id = intval($_POST['product_id']);
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';
    $size = $_POST['size'] ?? '';
    $color = $_POST['color'] ?? '';

    $stmt = $conn->prepare("INSERT INTO orders (product_id, username, gender, email, contact, address, size, color) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('isssssss', $product_id, $user, $gender, $email, $contact, $address, $size, $color);
    if ($stmt->execute()) {
        $conn->query("DELETE FROM cart WHERE product_id=$product_id AND username='$user'");
        header('Location: order.php?ordered=1');
        exit();
    }
    $stmt->close();
}

include 'header.php';
?>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Cart</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Your Cart</h1>
                <p class="mt-2 text-sm text-slate-500">A simple review of the items you saved for checkout.</p>
            </div>
            <a href="index.php" class="inline-flex rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Continue shopping</a>
        </div>

        <?php if (isset($_GET['removed'])): ?>
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">Item removed successfully.</div>
        <?php endif; ?>
    </section>

    <?php if (count($cart_items) === 0): ?>
        <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <p class="text-lg font-semibold text-slate-900">Your cart is empty.</p>
            <p class="mt-2 text-sm text-slate-500">Add a pair from the home page to start checkout.</p>
            <a href="index.php" class="mt-6 inline-flex rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Shop now</a>
        </div>
    <?php else: ?>
        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($cart_items as $item):
                $final_price = $item['discount'] > 0 ? $item['price'] - ($item['price'] * $item['discount'] / 100) : $item['price'];
            ?>
                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="aspect-[4/3] bg-slate-100">
                        <img src="<?= htmlspecialchars($item['image_link']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-full w-full object-cover">
                    </div>
                    <div class="p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600"><?= htmlspecialchars($item['brand']) ?></p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-900"><?= htmlspecialchars($item['name']) ?></h2>
                        <div class="mt-3 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-lg font-bold text-slate-900">₹<?= number_format($final_price, 2) ?></p>
                                <?php if ($item['discount'] > 0): ?>
                                    <p class="text-xs text-slate-500 line-through">₹<?= number_format($item['price'], 2) ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($item['discount'] > 0): ?>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><?= htmlspecialchars($item['discount']) ?>% off</span>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <form method="post">
                                <input type="hidden" name="remove_id" value="<?= $item['cart_id'] ?>">
                                <button type="submit" class="rounded-full border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Remove</button>
                            </form>
                            <button type="button"
                                    class="rounded-full bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                                    data-open-buy
                                    data-product-id="<?= htmlspecialchars($item['product_id']) ?>"
                                    data-product-name="<?= htmlspecialchars($item['name']) ?>"
                                    data-product-brand="<?= htmlspecialchars($item['brand']) ?>"
                                    data-product-image="<?= htmlspecialchars($item['image_link']) ?>"
                                    data-product-price="<?= htmlspecialchars($item['price']) ?>"
                                    data-product-discount="<?= htmlspecialchars($item['discount']) ?>">
                                Buy now
                            </button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include 'buy-now-modal.php'; ?>
<script src="catalog-actions.js"></script>
<?php include 'footer.php'; ?>
