<?php
if (!isset($layout)) {
    $layout = 'site';
}
?>
<?php if ($layout === 'site'): ?>
<footer class="mt-8 border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-3">
            <div>
                <a href="index.php" class="text-2xl font-bold text-slate-900">ShoeMart</a>
                <p class="mt-3 max-w-sm text-sm leading-6 text-slate-600">Simple footwear shopping with a clean layout, easy browsing, and a responsive experience on every device.</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Quick Links</p>
                <div class="mt-3 grid gap-2 text-sm text-slate-600">
                    <a href="index.php" class="hover:text-slate-900">Home</a>
                    <a href="sale.php" class="hover:text-slate-900">Sale</a>
                    <a href="wishlist.php" class="hover:text-slate-900">Wishlist</a>
                    <a href="cart.php" class="hover:text-slate-900">Cart</a>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Support</p>
                <div class="mt-3 grid gap-2 text-sm text-slate-600">
                    <a href="privacy.php" class="hover:text-slate-900">Privacy Policy</a>
                    <a href="terms.php" class="hover:text-slate-900">Terms of Service</a>
                    <a href="contact.php" class="hover:text-slate-900">Contact Us</a>
                </div>
            </div>
        </div>
        <div class="mt-8 flex flex-col gap-2 border-t border-slate-200 pt-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; <?= date('Y') ?> ShoeMart. All rights reserved.</p>
            <p>Built for a simple and responsive shopping flow.</p>
        </div>
    </div>
</footer>
<?php elseif ($layout === 'admin'): ?>
<footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-4 text-center text-sm text-slate-500 sm:px-6 lg:px-8">
        Admin dashboard
    </div>
</footer>
<?php else: ?>
<footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-4 text-center text-sm text-slate-500 sm:px-6 lg:px-8">
        &copy; <?= date('Y') ?> EasyKart.
    </div>
</footer>
<?php endif; ?>
<script>
(function () {
    function hidePreloader() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.classList.add('hidden');
            preloader.style.display = 'none';
            preloader.setAttribute('aria-hidden', 'true');
        }
    }

    if (document.readyState === 'complete') {
        hidePreloader();
    } else {
        window.addEventListener('load', hidePreloader, { once: true });
    }
})();
</script>
</body>
</html>
