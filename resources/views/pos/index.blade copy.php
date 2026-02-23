<x-app-layout>
    <x-basic.breadcrumb />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .product-item.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
    <div class="content">
        <x-basic.card :title="__('POS')">
            @if(session('selected_room_id'))
                <div class="alert alert-info text-center">
                    <strong>Room:</strong> {{ session('selected_room_id') }}
                </div>
            @endif
            @if(session('message'))
                <div class="alert alert-success text-center" id="room-message">
                    {{ session('message') }}
                </div>
            @endif

            <div class="row g-3">
                <!-- LEFT: Cart Section -->
                <div class="col-lg-7">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="col-md-12 mt-2 mt-md-0 d-flex justify-content-end">
                            <a href="{{ route('pos.table.view_table') }}" class="btn btn-outline-info btn-sm mb-2">
                                <i class="fa fa-plus"></i> {{ __('global.add_table') }}
                            </a>
                            <button class="btn btn-outline-info btn-sm mb-2" data-bs-toggle="modal"
                                data-bs-target="#suspendModal">
                                <i class="fa fa-clock"></i> View Suspended Orders
                            </button>
                        </div>

                        <div class="card-header bg-white">
                            <div class="col-md-4">
                                <x-basic.form.select label="{{ __('global.customer') }}" name="customer_id"
                                    :options="$customers" :selected="null" :required="true" />
                            </div>
                            <div class="col-md-4 mt-2 mt-md-0 col-lg-12">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="product-code-input"
                                        placeholder="{{ __('global.search_product') }}">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0" id="cart-section" style="overflow-y: auto; height: 420px;">
                            <table class="table table-hover align-middle m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Product') }}</th>
                                        <th class="text-end">{{ __('Price') }}</th>
                                        <th class="text-center">{{ __('Qty') }}</th>
                                        <th class="text-end">{{ __('Subtotal') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items"> </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="row mb-2">
                                <div class="col-4">
                                    <strong>{{ __('Items') }}:</strong> <span id="item-count">0</span>
                                </div>
                                <div class="col-4">
                                    <strong>{{ __('Tax') }}:</strong> 0.00
                                </div>
                                <div class="col-4 text-end">
                                    <strong>{{ __('Total') }}: </strong>
                                    <span id="cart-total">0.00</span>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md">
                                    <button class="btn btn-outline-warning w-100" onclick="clearCart()">
                                        <i class="fa fa-trash"></i> {{ __('Clear') }}
                                    </button>
                                </div>
                                <div class="col-md">
                                    <form action="{{ route('pos.clearRoom') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-outline-danger w-150">
                                            <i class="fa fa-times"></i> {{ __('Remove Room') }}
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md">
                                    <a href="{{ route('pos.moveRoom') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fa fa-random"></i> {{ __('Move Room') }}
                                    </a>
                                </div>
                                <div class="col-md">
                                    <button class="btn btn-outline-primary w-100" onclick="submitOrder()">
                                        <i class="fa fa-plus"></i> {{ __('Order') }}
                                    </button>
                                </div>
                                <div class="col-md">
                                    <button class="btn btn-outline-info w-100">
                                        <i class="fa fa-print"></i> {{ __('Bill') }}
                                    </button>
                                </div>
                                <div class="col-md">
                                    <button class="btn btn-outline-danger w-100">
                                        <i class="fa fa-credit-card"></i> {{ __('Payment') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Category & Products -->
                <div class="col-lg-5">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white">
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-sm btn-outline-primary category-filter"
                                    data-id="all">{{ __('All') }}</button>
                                @foreach($categories as $cat)
                                    <button class="btn btn-sm btn-outline-dark category-filter"
                                        data-id="{{ $cat->id }}">{{ $cat->name }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="{{ __('global.code') }}">
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="{{ __('global.name') }}">
                            </div>

                            <div id="product-list" class="row g-3">
                                @foreach($products as $p)
                                    <div class="col-4">
                                        <div class="card text-center p-2 product-item shadow-sm border-0"
                                            data-id="{{ $p->id }}" data-price="{{ $p->price ?? 0 }}"
                                            data-code="{{ $p->code }}" style="cursor: pointer;">
                                            <div class="mb-2">
                                                <img src="{{ asset($p->image ?? 'images/placeholder.png') }}"
                                                    class="img-fluid border" />
                                            </div>
                                            <div class="fw-bold small">{{ $p->name }}</div>
                                            <div class="text-muted small">{{ $p->code }}</div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('pos.suspend_modal')
        </x-basic.card>
    </div>
    <script>

        setTimeout(() => {
            const el = document.getElementById('room-message');
            if (el) {
                el.style.display = 'none';
            }
        }, 3000); // 5000ms = 5 seconds
        let addInProgress = false;








        document.querySelectorAll('.category-filter').forEach(button => {
            button.addEventListener('click', function () {
                const categoryId = this.dataset.id;

                // If "All" clicked, reload the page or fetch all
                if (categoryId === 'all') {
                    location.reload();
                    return;
                }

                fetch(`/pos/products/${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('product-list');
                        if (container) {
                            container.innerHTML = data.html;

                            // Re-bind click event to new product items
                            container.querySelectorAll('.product-item').forEach(item => {
                                item.addEventListener('click', () => {
                                    if (item.classList.contains('disabled')) return;
                                    item.classList.add('disabled');
                                    const productId = item.dataset.id;

                                });
                            });
                        }
                    });
            });
        });


        function showLoading() {
            const loading = document.getElementById('loading');
            if (loading) loading.style.display = 'inline';
        }

        function hideLoading() {
            const loading = document.getElementById('loading');
            if (loading) loading.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.product-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    const code = this.dataset.code;
                    if (code) {
                        fetchProductByCode(code);
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('product-code-input');
            if (input) {
                let timeout = null;

                input.addEventListener('input', function () {
                    const code = this.value.trim();

                    clearTimeout(timeout); // reset timer
                    timeout = setTimeout(() => {
                        if (code) {
                            fetchProductByCode(code);
                            this.value = ''; // clear input after fetch
                        }
                    }, 500); // delay to avoid firing on every keystroke
                });
            }
        });
        // document.addEventListener('DOMContentLoaded', () => {
        //     const input = document.getElementById('product-code-input');
        //     if (input) {
        //         input.addEventListener('change', function () {
        //             const code = this.value.trim();
        //             if (code) {
        //                 fetchProductByCode(code);
        //                 this.value = '';
        //             }
        //         });
        //     }
        // });


        function fetchProductByCode(code) {
            fetch(`{{ route('product.byCode') }}?code=${encodeURIComponent(code)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const product = data.product;
                        const rowId = Date.now(); // unique
                        const qty = 1;
                        const price = parseFloat(product.price);
                        const item = {
                            id: rowId,
                            productId: product.id,
                            name: product.name ?? 'Unknown',
                            price: price,
                            qty: qty,
                            subtotal: price * qty
                        };
                        // Save to localStorage
                        let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
                        cart.push(item);
                        localStorage.setItem('cartItems', JSON.stringify(cart));
                        appendCartItem(rowId, item);
                        updateCartTotals(cart);
                    } else {
                        alert('Product not found');
                    }
                })
                .catch(err => console.error('Error fetching product:', err));
        }

        function appendCartItem(rowId, item) {
            const row = document.createElement('tr');
            row.setAttribute('data-id', rowId);
            row.innerHTML = `
            <td>${item.name}</td>
            <td class="text-end">${item.price.toFixed(2)}</td>
            <td class="text-center">
                <input type="text" value="${item.qty}" class="form-control form-control-sm text-center" >
            </td>
            <td class="text-end">${item.subtotal.toFixed(2)}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart('${rowId}')">
                    <i class="ph ph-trash"></i>
                </button>
            </td>
        `;
            document.getElementById('cart-items').appendChild(row);
        }

        function removeFromCart(rowId) {
            const row = document.querySelector(`tr[data-id="${rowId}"]`);
            if (row) row.remove();
        }



        const sessionCart = @json(session('cart'));
        if (Object.keys(sessionCart).length > 0) {
            const cartArray = Object.entries(sessionCart).map(([key, item]) => {
                return {
                    id: key,
                    productId: item.product_id ?? null,
                    name: item.name,
                    price: parseFloat(item.price),
                    qty: parseFloat(item.qty),
                    subtotal: parseFloat(item.subtotal),
                };
            });
            localStorage.setItem('cartItems', JSON.stringify(cartArray));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const cart = JSON.parse(localStorage.getItem('cartItems')) || [];
            cart.forEach(item => appendCartItem(item.id, item));
            updateCartTotals(cart);

        });

        function clearCart() {
            localStorage.removeItem('cartItems');
            document.getElementById('cart-items').innerHTML = '';
            updateCartTotals({});
        }
        function updateCartTotals(cart) {
            let totalItems = cart.length;
            let totalPrice = cart.reduce((sum, item) => sum + item.subtotal, 0);

            document.getElementById('item-count').innerText = totalItems;
            document.getElementById('cart-total').innerText = totalPrice.toFixed(2);
        }






        function submitOrder() {
            showLoading();
            const cart = JSON.parse(localStorage.getItem('cartItems')) || [];

            fetch("{{ route('pos.suspend') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        localStorage.removeItem('cartItems');
                        document.getElementById('cart-items').innerHTML = '';
                        updateCartTotals([]);
                    }
                })
                .catch(console.error)
                .finally(hideLoading);
        }

        function deleteSuspend(id, button) {
            if (!confirm("Are you sure you want to delete this suspended order?")) return;

            fetch(`/pos/suspend/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row
                        const row = button.closest('tr');
                        if (row) row.remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(console.error);
        }
    </script>
</x-app-layout>