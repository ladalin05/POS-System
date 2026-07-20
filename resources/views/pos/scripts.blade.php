<script>
    $(function () {
        const routes = {
            products: '{{ route('sales.pos.products') }}',
            barcode: (code) => `{{ route('sales.pos.barcode', ['code' => 'CODE_PLACEHOLDER']) }}`.replace('CODE_PLACEHOLDER', encodeURIComponent(code)),
            checkout: '{{ route('sales.pos.checkout') }}',
            hold: '{{ route('sales.pos.hold') }}',
            holds: '{{ route('sales.pos.holds') }}',
            resume: (id) => `{{ route('sales.pos.holds.resume', ':id') }}`.replace(':id', id),
        };

        const DEFAULT_TAX_RATE = 0;

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let cart = [];
        let activeCategory = 'all';
        let searchTerm = '';
        let searchDebounce = null;

        function warehouseId() {
            return $('#warehouse-select').val() || 1;
        }

        function unwrap(res) {
            return (res && res.data) ? res.data : {};
        }

        // ---------- Catalog ----------

        function loadProducts() {
            $('#product-grid').html('<div class="text-muted small p-3">Loading products…</div>');

            $.get(routes.products, {
                category_id: activeCategory,
                search: searchTerm,
                warehouse_id: warehouseId(),
            })
            .done(function (res) {
                renderProducts(unwrap(res).products || []);
            })
            .fail(function () {
                $('#product-grid').html('<div class="text-danger small p-3">Failed to load products.</div>');
            });
        }

        function renderProducts(products) {
            if (!products.length) {
                $('#product-grid').html('<div class="text-muted small p-3">No products found.</div>');
                return;
            }

            const html = products.map(function (p) {
                const stockLabel = p.stock > 0 ? (p.stock + ' left') : 'Out of stock';
                const dim = p.stock <= 0 ? 'opacity-50' : '';
                const thumb = p.image
                            ? `<img src="${p.image}" alt="${escapeHtml(p.product_name)}" class="w-100 rounded-2 mb-2" style="height:200px;object-fit:cover;" onerror="this.replaceWith(Object.assign(document.createElement('div'), {className:'d-flex align-items-center justify-content-center bg-light rounded-2 mb-2 text-muted', style:'height:82px;', innerHTML:'<i class=\\'bi bi-image\\'></i>'}))">`
                            : `<div class="d-flex align-items-center justify-content-center bg-light rounded-2 mb-2 text-muted" style="height:200px;"><i class="bi bi-image"></i></div>`;
                return `
                <div class="col">
                    <div class="product-item d-flex flex-column h-100 justify-content-between ${dim}"
                        data-id="${p.id}"
                        data-name="${escapeHtml(p.product_name)}"
                        data-price="${p.price}"
                        data-unit-id="${p.unit_id ?? ''}"
                        data-tax="${p.tax_value ?? DEFAULT_TAX_RATE}"
                        data-tax-type="${p.tax_type ?? 'Exclusive'}"
                        data-sku="${escapeHtml(p.sku)}">
                        <div class="w-100">
                            ${thumb}
                            <div class="fw-semibold text-dark text-truncate mb-1" style="font-size:0.875rem;">${escapeHtml(p.product_name)}</div>
                            <div class="text-muted font-monospace" style="font-size:0.75rem;">${escapeHtml(p.sku)}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-light">
                            <span class="fw-bold text-dark">$${parseFloat(p.price).toFixed(2)}</span>
                            <span class="small text-muted bg-light px-2 py-0.5 rounded border" style="font-size:0.7rem;">${stockLabel}</span>
                        </div>
                    </div>
                </div>`;
            }).join('');

            $('#product-grid').html(html);
        }

        $('.category-pill').on('click', function () {
            $('.category-pill').removeClass('active');
            $(this).addClass('active');
            activeCategory = $(this).data('category-id') || 'all';
            loadProducts();
        });

        $('#search-input').on('input', function () {
            clearTimeout(searchDebounce);
            const val = $(this).val();
            searchDebounce = setTimeout(function () {
                searchTerm = val;
                loadProducts();
            }, 300);
        });

        $('#product-grid').on('click', '.product-item', function () {
            const $el = $(this);
            if ($el.hasClass('opacity-50')) return;
            addToCart({
                product_id: $el.data('id'),
                name: $el.data('name'),
                sku: $el.data('sku'),
                price: parseFloat($el.data('price')),
                unit_id: $el.data('unit-id') || null,
                tax_rate: parseFloat($el.data('tax')) || DEFAULT_TAX_RATE,
                tax_type: $el.data('tax-type') || 'Exclusive',
            });
        });

        // ---------- Barcode / SKU input ----------

        $('#sku-input').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                pushSku($(this).val().trim());
                $(this).val('');
            }
        });

        $('#sku-push-btn').on('click', function () {
            pushSku($('#sku-input').val().trim());
            $('#sku-input').val('');
        });

        $(document).on('click', '.sim-trigger', function () {
            pushSku($(this).data('barcode'));
        });

        function pushSku(code) {
            if (!code) return;

            $.get(routes.barcode(code), { warehouse_id: warehouseId() })
                .done(function (res) {
                    const p = unwrap(res).product;
                    if (!p) {
                        showToast(`No product found for "${code}"`, 'danger');
                        return;
                    }
                    addToCart({
                        product_id: p.id,
                        name: p.product_name,
                        sku: p.sku,
                        price: parseFloat(p.price),
                        unit_id: p.unit_id,
                        tax_rate: parseFloat(p.tax_value) || DEFAULT_TAX_RATE,
                        tax_type: p.tax_type || 'Exclusive',
                    });
                })
                .fail(function (xhr) {
                    showToast(xhr.responseJSON?.message || `No product found for "${code}"`, 'danger');
                });
        }

        // ---------- Cart ----------

        function addToCart(product) {
            const existing = cart.find(i => i.product_id === product.product_id);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ ...product, qty: 1 });
            }
            renderCart();
        }

        function changeQty(productId, delta) {
            const item = cart.find(i => i.product_id === productId);
            if (!item) return;
            item.qty += delta;
            if (item.qty <= 0) cart = cart.filter(i => i.product_id !== productId);
            renderCart();
        }

        function removeItem(productId) {
            cart = cart.filter(i => i.product_id !== productId);
            renderCart();
        }

        function lineTax(item) {
            const lineSubtotal = item.qty * item.price;
            if (item.tax_type === 'Inclusive') {
                return lineSubtotal - (lineSubtotal / (1 + item.tax_rate / 100));
            }
            return lineSubtotal * (item.tax_rate / 100);
        }

        function renderCart() {
            $('#cart-count').text(cart.length + ' Items');

            if (!cart.length) {
                $('#cart-body').html('<div class="text-muted small p-3 text-center">Cart is empty. Tap a product to add it.</div>');
            } else {
                const html = cart.map(function (item) {
                    const lineTotal = item.qty * item.price;
                    return `
                        <div class="p-3 rounded-3 border mb-2 bg-light bg-opacity-30 d-flex align-items-center justify-content-between" data-id="${item.product_id}">
                            <div class="flex-grow-1 min-w-0 pe-2">
                                <div class="fw-semibold text-dark text-truncate" style="font-size:0.875rem;">${escapeHtml(item.name)}</div>
                                <small class="text-muted font-monospace">$${item.price.toFixed(2)} / unit</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="qty-btn cart-qty-minus" type="button"><i class="fa-solid fa-minus text-danger"></i></button>
                                <span class="fw-bold px-1 text-dark" style="min-width:20px;text-align:center;">${item.qty}</span>
                                <button class="qty-btn cart-qty-plus" type="button"><i class="fa-solid fa-plus text-primary"></i></button>
                            </div>
                            <div class="fw-bold text-dark text-end ms-3" style="width:65px;font-size:0.9rem;">$${lineTotal.toFixed(2)}</div>
                            <button class="btn text-danger-emphasis bg-danger bg-opacity-10 border-0 rounded p-1.5 ms-2 d-flex align-items-center cart-remove"><i class="fa-solid fa-trash-can text-danger small"></i></button>
                        </div>`;
                }).join('');
                $('#cart-body').html(html);
            }

            updateTotals();
        }

        $('#cart-body').on('click', '.cart-qty-plus', function () {
            changeQty($(this).closest('[data-id]').data('id'), 1);
        });
        $('#cart-body').on('click', '.cart-qty-minus', function () {
            changeQty($(this).closest('[data-id]').data('id'), -1);
        });
        $('#cart-body').on('click', '.cart-remove', function () {
            removeItem($(this).closest('[data-id]').data('id'));
        });

        $('#reset-btn, #void-btn').on('click', function () {
            cart = [];
            $('#discount-input').val('0.00');
            renderCart();
        });

        $('#discount-input').on('input', updateTotals);

        function updateTotals() {
            const subtotal = cart.reduce((sum, i) => sum + (i.qty * i.price), 0);
            const discount = parseFloat($('#discount-input').val()) || 0;
            const tax = cart.reduce((sum, i) => sum + lineTax(i), 0);
            const total = Math.max(subtotal - discount, 0) + tax;

            $('#subtotal-amount').text('$' + subtotal.toFixed(2));
            $('#tax-amount').text('$' + tax.toFixed(2));
            $('#total-amount').text('$' + total.toFixed(2));
            $('#checkout-btn .checkout-total').text('$' + total.toFixed(2));
        }

        // ---------- Hold / resume ----------

        $('#hold-btn').on('click', function () {
            if (!cart.length) {
                showToast('Cart is empty.', 'danger');
                return;
            }

            const payload = {
                warehouse_id: warehouseId(),
                customer_id: $('#customer-select').val() || null,
                biller_id: $('#biller-select').val() || null,
                discount: parseFloat($('#discount-input').val()) || 0,
                items: cart.map(i => ({
                    product_id: i.product_id, qty: i.qty, unit_price: i.price, unit_id: i.unit_id,
                })),
            };

            $.ajax({
                url: routes.hold, method: 'POST',
                data: JSON.stringify(payload), contentType: 'application/json',
            })
            .done(function () {
                showToast('Order held.', 'success');
                cart = [];
                $('#discount-input').val('0.00');
                renderCart();
            })
            .fail(function (xhr) {
                showToast(xhr.responseJSON?.message || 'Could not hold order.', 'danger');
            });
        });

        $('#held-orders-btn').on('click', function () {
            $.get(routes.holds).done(function (res) {
                const holds = unwrap(res).holds || [];
                if (!holds.length) {
                    showToast('No held orders.', 'success');
                    return;
                }
                const list = holds.map(h => `#${h.id} — ${h.items.length} item(s) — $${parseFloat(h.total).toFixed(2)}`).join('\n');
                const pick = prompt(`Held orders:\n${list}\n\nEnter an order # to resume:`);
                if (pick) resumeHold(pick.replace('#', '').trim());
            });
        });

        function resumeHold(id) {
            $.get(routes.resume(id)).done(function (res) {
                cart = (unwrap(res).items || []).map(i => ({
                    product_id: i.product_id,
                    name: i.name,
                    sku: i.code,
                    price: parseFloat(i.price),
                    unit_id: i.unit_id,
                    qty: parseFloat(i.qty),
                    tax_rate: DEFAULT_TAX_RATE,
                    tax_type: 'Exclusive',
                }));
                renderCart();
                showToast('Order resumed into cart.', 'success');
            }).fail(function () {
                showToast('Could not resume that order.', 'danger');
            });
        }

        // ---------- Checkout ----------

        $('#checkout-btn').on('click', function () {
            if (!cart.length) {
                showToast('Cart is empty.', 'danger');
                return;
            }

            const discount = parseFloat($('#discount-input').val()) || 0;
            const subtotal = cart.reduce((sum, i) => sum + (i.qty * i.price), 0);
            const tax = cart.reduce((sum, i) => sum + lineTax(i), 0);
            const total = Math.max(subtotal - discount, 0) + tax;

            const payload = {
                warehouse_id: warehouseId(),
                customer_id: $('#customer-select').val() || null,
                biller_id: $('#biller-select').val() || null,
                cash_account_id: $('#cash-account-select').val() || null,
                discount: discount,
                paid_amount: total,
                items: cart.map(i => ({
                    product_id: i.product_id, qty: i.qty, unit_price: i.price, unit_id: i.unit_id,
                })),
            };

            $('#checkout-btn').prop('disabled', true);

            $.ajax({
                url: routes.checkout, method: 'POST',
                data: JSON.stringify(payload), contentType: 'application/json',
            })
            .done(function (res) {
                const sale = unwrap(res).sale;
                showToast('Sale completed: ' + (sale ? sale.reference_no : ''), 'success');
                cart = [];
                $('#discount-input').val('0.00');
                renderCart();
                loadProducts();
            })
            .fail(function (xhr) {
                showToast(xhr.responseJSON?.message || 'Checkout failed.', 'danger');
            })
            .always(function () {
                $('#checkout-btn').prop('disabled', false);
            });
        });

        // ---------- Helpers ----------

        function escapeHtml(str) {
            return $('<div>').text(str ?? '').html();
        }

        function showToast(message, type) {
            const $alert = $(`<div class="alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3 shadow" style="z-index:2000;">${escapeHtml(message)}</div>`);
            $('body').append($alert);
            setTimeout(() => $alert.fadeOut(300, () => $alert.remove()), 3000);
        }

        // ---------- Init ----------
        loadProducts();
        renderCart();
    });
</script>