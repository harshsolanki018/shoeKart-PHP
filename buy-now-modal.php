<div id="buy-now-modal" class="fixed inset-0 z-[1500] hidden items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-4xl rounded-3xl bg-white p-5 shadow-2xl lg:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Order now</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Place your order</h2>
            </div>
            <button type="button" id="close-buy-now" class="rounded-full border border-slate-200 px-3 py-2 text-2xl leading-none text-slate-500 hover:bg-slate-100">&times;</button>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="overflow-hidden rounded-2xl bg-slate-100">
                <img id="buy-now-image" src="" alt="Product" class="h-full w-full object-cover">
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                <h3 id="buy-now-name" class="text-xl font-bold text-slate-900"></h3>
                <p id="buy-now-brand" class="mt-1 text-sm text-slate-500"></p>
                <p id="buy-now-price" class="mt-3 text-2xl font-bold text-slate-900"></p>
                <p id="buy-now-discount" class="mt-1 text-sm font-medium text-emerald-600"></p>

                <form id="buy-now-form" method="post" action="order_submit.php" class="mt-4 grid gap-3">
                    <input type="hidden" name="product_id" id="product-id">

                    <label class="text-sm font-medium text-slate-700">Username</label>
                    <input type="text" name="username" id="order-username" value="<?= htmlspecialchars($user ?? '') ?>" required <?= !empty($user) ? 'readonly' : '' ?> class="rounded-xl border border-slate-200 bg-white px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">

                    <label class="text-sm font-medium text-slate-700">Gender</label>
                    <select name="gender" required class="rounded-xl border border-slate-200 bg-white px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                        <option value="">Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>

                    <label class="text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" required class="rounded-xl border border-slate-200 bg-white px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">

                    <label class="text-sm font-medium text-slate-700">Contact</label>
                    <input type="text" name="contact" required pattern="\d{10}" placeholder="10-digit number" class="rounded-xl border border-slate-200 bg-white px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">

                    <label class="text-sm font-medium text-slate-700">Address</label>
                    <textarea name="address" required class="min-h-24 rounded-xl border border-slate-200 bg-white px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Size</label>
                            <select name="size" required class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                                <option value="">Select size</option>
                                <?php for ($i = 6; $i <= 11; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Color</label>
                            <select name="color" required class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                                <option value="">Select color</option>
                                <?php foreach (['Black', 'White', 'Brown', 'Blue', 'Red'] as $color): ?>
                                    <option value="<?= $color ?>"><?= $color ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="mt-2 rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Confirm order</button>
                </form>
            </div>
        </div>
    </div>
</div>
