<?php
if (!isset($layout)) {
    $layout = 'site';
}
?>
<?php if ($layout === 'site'): ?>
<footer class="mt-8 border-t border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-5 text-sm text-slate-600 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <p>&copy; <?= date('Y') ?> ShoeMart. All rights reserved.</p>
        <div class="flex flex-wrap gap-4">
            <a href="privacy.php" class="hover:text-slate-900">Privacy Policy</a>
            <a href="terms.php" class="hover:text-slate-900">Terms of Service</a>
            <a href="contact.php" class="hover:text-slate-900">Contact Us</a>
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
