(function () {
    const products = Array.isArray(window.catalogProducts) ? window.catalogProducts : [];
    const config = window.catalogConfig || {};
    const grid = document.getElementById('product-grid');
    const showMoreBtn = document.getElementById('show-more-btn');
    const buyNowModal = document.getElementById('buy-now-modal');
    const buyNowClose = document.getElementById('close-buy-now');
    const buyNowForm = document.getElementById('buy-now-form');
    const buyNowImage = document.getElementById('buy-now-image');
    const buyNowName = document.getElementById('buy-now-name');
    const buyNowBrand = document.getElementById('buy-now-brand');
    const buyNowPrice = document.getElementById('buy-now-price');
    const buyNowDiscount = document.getElementById('buy-now-discount');
    const productIdField = document.getElementById('product-id');
    const orderUsername = document.getElementById('order-username');

    const initialLimit = Number(config.initialLimit || 12);
    let showingAll = false;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function priceValue(product) {
        const price = Number(product.price || 0);
        const discount = Number(product.discount || 0);
        return discount > 0 ? price - (price * discount / 100) : price;
    }

    function priceMarkup(product) {
        const finalPrice = priceValue(product).toFixed(2);
        const basePrice = Number(product.price || 0).toFixed(2);
        if (Number(product.discount || 0) > 0) {
            return `
                <div class="flex flex-col gap-1">
                    <span class="text-lg font-bold text-slate-900">₹${finalPrice}</span>
                    <span class="text-sm text-slate-500 line-through">₹${basePrice}</span>
                </div>
            `;
        }

        return `<span class="text-lg font-bold text-slate-900">₹${finalPrice}</span>`;
    }

    function productCard(product, index) {
        const hiddenClass = !showingAll && index >= initialLimit ? ' hidden' : '';
        const badge = Number(product.discount || 0) > 0
            ? `<span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">${Number(product.discount)}% off</span>`
            : '';

        return `
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm${hiddenClass}">
                <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                    <img src="${escapeHtml(product.image_link)}" alt="${escapeHtml(product.name)}" class="h-full w-full object-cover transition duration-300 hover:scale-[1.03]">
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600">${escapeHtml(product.brand || 'ShoeMart')}</p>
                            <h3 class="mt-1 text-base font-semibold text-slate-900">${escapeHtml(product.name)}</h3>
                        </div>
                        ${badge}
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-3">
                        ${priceMarkup(product)}
                        <button type="button"
                                class="rounded-full border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100"
                                data-open-buy
                                data-product-id="${escapeHtml(product.id)}">
                            Buy now
                        </button>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button"
                                class="rounded-full bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                                data-add-cart
                                data-product-id="${escapeHtml(product.id)}">
                            Add to cart
                        </button>
                        <button type="button"
                                class="rounded-full border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100"
                                data-toggle-wishlist
                                data-product-id="${escapeHtml(product.id)}">
                            Wishlist
                        </button>
                    </div>
                </div>
            </article>
        `;
    }

    function renderProducts() {
        if (!grid) {
            return;
        }

        if (!products.length) {
            grid.innerHTML = `
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center text-slate-500">
                    No products available right now.
                </div>
            `;
            if (showMoreBtn) {
                showMoreBtn.closest('[data-show-more-wrap]')?.classList.add('hidden');
            }
            return;
        }

        grid.innerHTML = products.map((product, index) => productCard(product, index)).join('');

        if (showMoreBtn) {
            const wrap = showMoreBtn.closest('[data-show-more-wrap]');
            if (wrap) {
                wrap.classList.toggle('hidden', products.length <= initialLimit);
            }
            showMoreBtn.textContent = showingAll ? (config.showLessLabel || 'Show less') : (config.showMoreLabel || 'Show more');
        }
    }

    function setBuyNow(product) {
        if (!buyNowModal) {
            return;
        }

        if (buyNowImage) {
            buyNowImage.src = product.image_link || '';
            buyNowImage.alt = product.name || 'Product';
        }
        if (buyNowName) buyNowName.textContent = product.name || '';
        if (buyNowBrand) buyNowBrand.textContent = product.brand || '';

        const finalPrice = priceValue(product).toFixed(2);
        if (buyNowPrice) buyNowPrice.textContent = `₹${finalPrice}`;
        if (buyNowDiscount) {
            buyNowDiscount.textContent = Number(product.discount || 0) > 0 ? `${Number(product.discount)}% off` : '';
        }
        if (productIdField) productIdField.value = product.id || '';
        if (orderUsername && !orderUsername.value && config.user) {
            orderUsername.value = config.user;
        }

        buyNowModal.classList.remove('hidden');
        buyNowModal.classList.add('flex');
    }

    function closeBuyNow() {
        if (!buyNowModal) {
            return;
        }
        buyNowModal.classList.add('hidden');
        buyNowModal.classList.remove('flex');
    }

    function productFromButton(button) {
        const productId = button.dataset.productId;
        const found = products.find((item) => String(item.id) === String(productId));
        if (found) {
            return found;
        }

        return {
            id: productId,
            name: button.dataset.productName || '',
            brand: button.dataset.productBrand || '',
            image_link: button.dataset.productImage || '',
            price: button.dataset.productPrice || 0,
            discount: button.dataset.productDiscount || 0,
        };
    }

    async function postAction(url, productId) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: new URLSearchParams({ product_id: productId }),
        });

        return response.json();
    }

    document.addEventListener('click', async (event) => {
        const addCartBtn = event.target.closest('[data-add-cart]');
        if (addCartBtn) {
            const result = await postAction('add_to_cart.php', addCartBtn.dataset.productId);
            alert(result.message || 'Updated cart.');
            return;
        }

        const wishlistBtn = event.target.closest('[data-toggle-wishlist]');
        if (wishlistBtn) {
            const result = await postAction('toggle_wishlist.php', wishlistBtn.dataset.productId);
            alert(result.message || (result.action === 'added' ? 'Added to wishlist.' : 'Removed from wishlist.'));
            return;
        }

        const buyBtn = event.target.closest('[data-open-buy]');
        if (buyBtn) {
            setBuyNow(productFromButton(buyBtn));
            return;
        }

        if (event.target === buyNowModal) {
            closeBuyNow();
        }
    });

    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', () => {
            showingAll = !showingAll;
            renderProducts();
        });
    }

    if (buyNowClose) {
        buyNowClose.addEventListener('click', closeBuyNow);
    }

    if (buyNowForm) {
        buyNowForm.addEventListener('submit', closeBuyNow);
    }

    window.openBuyNow = function (productId, name, brand, image, price, discount) {
        setBuyNow({
            id: productId,
            name,
            brand,
            image_link: image,
            price,
            discount,
        });
    };

    renderProducts();
})();
