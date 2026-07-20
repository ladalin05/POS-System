<x-guest-layout>
    <style>
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }
        .classic-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease;
        }
        .category-pill {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 500;
            border: 1px solid transparent;
            padding: 6px 16px;
            border-radius: 30px;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .category-pill.active, .category-pill:hover {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .product-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .product-item:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .qty-btn {
            width: 28px; height: 28px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff;
            color: #475569; font-weight: 600; transition: all 0.15s ease;
        }
        .qty-btn:hover { background: #f8fafc; border-color: #94a3b8; color: #0f172a; }
        .camera-viewport {
            background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
            border-radius: 12px; height: 180px; position: relative; border: 1px solid #334155;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff; font-weight: 600; border: none; border-radius: 12px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); transition: all 0.2s ease;
        }
        .btn-checkout:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            color: #ffffff;
        }
    </style>

    <div class="container-fluid py-4 px-4">

        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 classic-card p-3 bg-white flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-wallet fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-slate-800" style="letter-spacing: -0.3px;">Loomis &amp; Co. Retail</h5>
                    <div class="text-muted small">Terminal 01</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select id="warehouse-select" class="form-select form-select-sm" style="width:auto;">
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
                <select id="biller-select" class="form-select form-select-sm" style="width:auto;">
                    <option value="">Cashier / Biller</option>
                    @foreach($billers as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
                <select id="customer-select" class="form-select form-select-sm" style="width:auto;">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <select id="cash-account-select" class="form-select form-select-sm" style="width:auto;">
                    @foreach($cashAccounts as $ca)
                        <option value="{{ $ca->id }}">{{ $ca->name }}</option>
                    @endforeach
                </select> 
                <button id="held-orders-btn" class="btn btn-outline-secondary btn-sm"><i class="fa-regular fa-clock me-1"></i>Held Orders</button>
            </div>
        </div>

        <!-- Main Grid Framework -->
        <div class="row g-4">

            <div class="col-xl-8 col-lg-8">
                <div class="classic-card p-3 h-100 d-flex flex-column">
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col">
                            <h6 class="fw-bold mb-0 text-secondary text-uppercase tracking-wider small">Product Catalog</h6>
                        </div>
                        <div class="col-7">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted py-2"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" id="search-input" class="form-control bg-light border-start-0 py-2" placeholder="Search by name, category, or barcode...">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 overflow-y-auto pb-3 mb-3 border-bottom custom-scrollbar">
                        <button class="category-pill active border-0" data-category-id="all">All Products</button>
                        @foreach($categories as $cat)
                            <button class="category-pill border-0" data-category-id="{{ $cat->id }}">{{ $cat->name }}</button>
                        @endforeach
                    </div>

                    <div class="custom-scrollbar pe-1" style="max-height: 560px; overflow-y: auto; overflow-x: hidden; scrollbar-width: none;">
                      <div id="product-grid" class="row row-cols-md-3 row-cols-2 g-2">
                          <div class="text-muted small p-3">Loading products…</div>
                      </div>
                  </div>
                </div>
            </div>

            <!-- Column 2: Active Cart -->
            <div class="col-xl-4 col-lg-4">
                <div class="classic-card p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-secondary text-uppercase tracking-wider small">Active Order</h6>
                                <span id="cart-count" class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2">0 Items</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button id="hold-btn" class="btn btn-link text-muted p-0 text-decoration-none small"><i class="fa-solid fa-pause me-1"></i>Hold</button>
                                <button id="reset-btn" class="btn btn-link text-muted p-0 text-decoration-none small"><i class="fa-solid fa-rotate me-1"></i>Reset</button>
                            </div>
                        </div>

                        <div id="cart-body" class="overflow-y-auto custom-scrollbar pe-1" style="max-height: 320px;">
                            <div class="text-muted small p-3 text-center">Cart is empty. Tap a product to add it.</div>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="bg-light p-3 rounded-4 border mt-3">
                        <div class="d-flex justify-content-between text-secondary mb-2" style="font-size: 0.85rem;">
                            <span>Subtotal</span>
                            <span id="subtotal-amount" class="fw-semibold text-dark">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-secondary mb-2" style="font-size: 0.85rem;">
                            <span>Apply Discount ($)</span>
                            <input type="text" id="discount-input" class="form-control form-control-sm text-end bg-white border" value="0.00" style="width: 80px; border-radius: 6px;">
                        </div>
                        <div class="d-flex justify-content-between text-secondary mb-3" style="font-size: 0.85rem;">
                            <span>Sales Tax</span>
                            <span id="tax-amount" class="fw-semibold text-dark">$0.00</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-baseline border-top pt-2 mb-3">
                            <span class="h6 fw-bold text-secondary text-uppercase mb-0">Total Due</span>
                            <span id="total-amount" class="h2 fw-bold text-dark mb-0" style="letter-spacing:-0.5px;">$0.00</span>
                        </div>

                        <div class="row g-2">
                            <div class="col-4">
                                <button id="void-btn" class="btn btn-outline-danger border w-100 py-2 fw-semibold text-uppercase rounded-3 btn-sm">Void</button>
                            </div>
                            <div class="col-8">
                                <button id="checkout-btn" class="btn btn-checkout w-100 py-2 text-uppercase btn-sm d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-cart-shopping"></i> Process Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        @include('pos.scripts')
    @endpush
    
</x-guest-layout>