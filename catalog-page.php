<?php
if (!isset($catalogTitle)) {
    $catalogTitle = 'ShoeMart';
}

if (!isset($catalogKicker)) {
    $catalogKicker = 'Fresh picks';
}

if (!isset($catalogHeadline)) {
    $catalogHeadline = 'Browse the collection';
}

if (!isset($catalogDescription)) {
    $catalogDescription = 'A simple, clean catalog layout with quick access to cart, wishlist, and checkout.';
}

if (!isset($catalogImage)) {
    $catalogImage = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1200&auto=format&fit=crop&q=80';
}

if (!isset($catalogSectionTitle)) {
    $catalogSectionTitle = 'Products';
}

if (!isset($catalogSectionText)) {
    $catalogSectionText = 'Choose a pair that fits your routine and keep the flow quick and simple.';
}

if (!isset($catalogProducts)) {
    $catalogProducts = [];
}

if (!isset($catalogInitialLimit)) {
    $catalogInitialLimit = 12;
}
?>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="flex flex-col justify-center p-6 sm:p-8 lg:p-10">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600"><?= htmlspecialchars($catalogKicker) ?></p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl"><?= htmlspecialchars($catalogHeadline) ?></h1>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600"><?= htmlspecialchars($catalogDescription) ?></p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="#products" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Browse products</a>
                    <a href="collections.php" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Collections</a>
                </div>
            </div>
            <div class="min-h-[260px] bg-slate-100">
                <img src="<?= htmlspecialchars($catalogImage) ?>" alt="<?= htmlspecialchars($catalogHeadline) ?>" class="h-full w-full object-cover">
            </div>
        </div>
    </section>

    <section id="products" class="mt-8">
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600"><?= htmlspecialchars($catalogKicker) ?></p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900"><?= htmlspecialchars($catalogSectionTitle) ?></h2>
            </div>
            <p class="max-w-xl text-sm text-slate-500"><?= htmlspecialchars($catalogSectionText) ?></p>
        </div>

        <div id="product-grid" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"></div>

        <?php if (count($catalogProducts) > $catalogInitialLimit): ?>
            <div class="mt-6 hidden justify-center" data-show-more-wrap>
                <button id="show-more-btn" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Show more</button>
            </div>
        <?php endif; ?>
    </section>

    <section class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-semibold text-blue-600">Fast delivery</p>
            <p class="mt-2 text-sm leading-6 text-slate-600">Simple, reliable delivery timelines that keep the checkout flow clear.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-semibold text-blue-600">Easy returns</p>
            <p class="mt-2 text-sm leading-6 text-slate-600">Return support stays straightforward so customers do not have to dig through extra steps.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-semibold text-blue-600">Secure checkout</p>
            <p class="mt-2 text-sm leading-6 text-slate-600">A clean checkout path with the same layout across every page.</p>
        </div>
    </section>
</main>

<?php include 'buy-now-modal.php'; ?>

<script>
window.catalogProducts = <?= json_encode($catalogProducts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
window.catalogConfig = <?= json_encode([
    'user' => $user ?? '',
    'initialLimit' => $catalogInitialLimit,
    'showMoreLabel' => 'Show more',
    'showLessLabel' => 'Show less',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>
<script src="catalog-actions.js"></script>
