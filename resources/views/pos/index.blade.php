<x-guest-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --pos-bg: #f8fafc;
            --sidebar-width: 100px;
            --cart-width: 440px;
            --primary-accent: #6366f1;
            --success-color: #22c55e;
            --surface-card: #ffffff;
            --text-main: #1e293b;
            --border-color: #e2e8f0;
        }

        body { 
            background-color: var(--pos-bg); 
            font-family: 'Inter', sans-serif;
            overflow: hidden; 
            height: 100vh;
            color: var(--text-main);
        }

        /* Layout Structure */
        .pos-wrapper { display: flex; height: 100vh; }

        /* Sidebar Glassmorphism */
        .cat-sidebar { 
            width: var(--sidebar-width); 
            background: #ffffff; 
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 0;
            z-index: 10;
        }
        
        .cat-item {
            width: 70px;
            height: 70px;
            border: none;
            background: transparent;
            margin-bottom: 20px;
            border-radius: 18px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .cat-item i { font-size: 1.5rem; margin-bottom: 5px; }
        
        .cat-item.active { 
            background: var(--primary-accent); 
            color: white; 
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            transform: scale(1.05);
        }

        /* Product Grid Area */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; background: #fbfcfe; }
        
        .search-container .form-control {
            border: 1px solid var(--border-color);
            padding: 12px 20px;
            font-size: 0.95rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }

        .product-card {
            background: var(--surface-card);
            border-radius: 24px;
            border: 1px solid transparent;
            padding: 24px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .product-card:hover { 
            transform: translateY(-8px); 
            border-color: var(--primary-accent);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }

        .product-card img {
            filter: drop-shadow(0 10px 10px rgba(0,0,0,0.1));
            transition: transform 0.3s;
        }
        
        .product-card:active img { transform: scale(0.9); }

        .stock-badge { 
            position: absolute; top: 15px; right: 15px; 
            font-size: 10px; font-weight: 700; 
            background: #f1f5f9; color: #475569; 
            padding: 5px 10px; border-radius: 20px; 
        }

        /* Cart Refinement */
        .cart-sidebar { 
            width: var(--cart-width); 
            background: white; 
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .cart-items-area { 
            flex: 1; 
            overflow-y: auto; 
            padding: 25px;
            scrollbar-width: thin;
        }
        
        .cart-item-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 15px;
            transition: background 0.2s;
        }

        .cart-item-row:hover { background: #f8fafc; }

        .loyalty-card {
            background: linear-gradient(135deg, #fff9f2 0%, #fff1e6 100%);
            border: 1px dashed #fdba74;
            border-radius: 20px;
            padding: 20px;
        }

        /* Better Controls */
        .qty-controls { 
            background: #f1f5f9; 
            border-radius: 12px; 
            display: inline-flex;
            align-items: center;
        }
        
        .qty-btn { 
            border: none; background: none; 
            width: 32px; height: 32px; 
            font-weight: bold; color: var(--primary-accent);
            border-radius: 10px;
        }
        
        .qty-btn:hover { background: #e2e8f0; }

        /* Payment Section */
        .payment-method {
            border: 2px solid #f1f5f9;
            border-radius: 18px;
            padding: 15px;
            background: white;
            transition: 0.3s;
            color: #64748b;
            text-align: center;
        }

        .payment-method.active { 
            border-color: var(--primary-accent); 
            background: #f5f3ff; 
            color: var(--primary-accent);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }
        
        .btn-order {
            background: var(--primary-accent);
            color: white;
            height: 70px;
            border-radius: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            width: 100%;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-order:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
    </style>

    <div class="pos-wrapper">
        <aside class="cat-sidebar">
            <div class="mb-5" style="color: var(--primary-accent)">
                <i class="fa-solid fa-rocket fs-2"></i>
            </div>
            <div id="pos-categories" class="w-100 d-flex flex-column align-items-center">
                </div>
        </aside>

        <main class="main-content">
            <header class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-800 text-dark mb-1">Store Front</h2>
                    <p class="text-muted small mb-0"><i class="fa-regular fa-calendar me-2"></i><span id="live-date"></span></p>
                </div>
                <div class="d-flex gap-3">
                    <div class="input-group search-container" style="width: 350px;">
                        <span class="input-group-text bg-white border-end-0 rounded-start-4"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="search-input" class="form-control border-start-0 rounded-end-4" placeholder="Search product name or SKU...">
                    </div>
                    <button class="btn btn-outline-dark rounded-4 px-4 border-0 bg-white shadow-sm"><i class="fa-solid fa-sliders me-2"></i> Filters</button>
                </div>
            </header>

            <div id="product-grid" class="row g-4">
                </div>
        </main>

        <aside class="cart-sidebar">
            <div class="p-4 border-bottom">
                <div class="d-flex gap-2 mb-4">
                    <div class="flex-grow-1">
                        <label class="small fw-bold text-muted mb-1 ms-1">Customer</label>
                        <select class="form-select border-0 bg-light rounded-3 py-2">
                            <option>Walk In Customer</option>
                            <option>VIP Member (James)</option>
                        </select>
                    </div>
                    <div class="align-self-end d-flex gap-1">
                        <button class="btn btn-primary rounded-3 py-2"><i class="fa-solid fa-plus"></i></button>
                        <button class="btn btn-dark rounded-3 py-2" onclick="simulateScan()"><i class="fa-solid fa-barcode"></i></button>
                    </div>
                </div>

                <div class="loyalty-card d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 fw-bold small text-dark">Loyalty Reward Available</p>
                        <span class="text-primary fw-bold" style="font-size: 11px;">Redeemable: $48.00</span>
                    </div>
                    <button class="btn btn-dark btn-sm rounded-pill px-3 fw-bold small">APPLY</button>
                </div>
            </div>

            <div class="cart-items-area" id="cart-list">
                </div>

            <div class="p-4 bg-white border-top shadow-lg">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Service Tax (10%)</span>
                    <span id="tax-amount" class="fw-bold small">$0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-end mb-4 pt-2 border-top">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Payable</span>
                        <h1 class="fw-800 mb-0" id="total-usd" style="color: var(--text-main);">$0.00</h1>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success-subtle text-success rounded-pill px-3">
                            <span id="total-riel">0</span> ៛
                        </span>
                    </div>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <button class="payment-method active w-100" onclick="setPayment('CASH', this)">
                            <i class="fa-solid fa-money-bill-wave d-block mb-2 fs-4"></i>
                            <span class="small fw-bold">CASH</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="payment-method w-100" onclick="setPayment('CARD', this)">
                            <i class="fa-solid fa-credit-card d-block mb-2 fs-4"></i>
                            <span class="small fw-bold">CARD</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="payment-method w-100" onclick="setPayment('SCAN', this)">
                            <i class="fa-solid fa-qrcode d-block mb-2 fs-4"></i>
                            <span class="small fw-bold">SCAN</span>
                        </button>
                    </div>
                </div>

                <button class="btn-order" onclick="processOrder()">
                    COMPLETE ORDER <i class="fa-solid fa-chevron-right ms-2 small"></i>
                </button>
            </div>
        </aside>
    </div>

    <script>
        // ... (Keep your existing script logic, it works perfectly) ...
        // Note: Ensure the `renderProducts` and `updateCartUI` templates match the new HTML structure above.
        
        const KHR_RATE = 4100;
        const TAX_RATE = 0.10;
        let cart = {};
        let selectedPayment = 'CASH';

        const categories = @json($categories);

        const products = @json($products);

        function init() {
            renderCategories();
            renderProducts();
            updateDate();
        }

        function renderCategories() {
            let html = '';
            categories.forEach(cat => {
                html += `
                    <button class="cat-item ${cat.id === 'all' ? 'active' : ''}" onclick="filterCat('${cat.id}', this)">
                        <i class="fa-solid ${cat.icon}"></i>
                        <span>${cat.name}</span>
                    </button>
                `;
            });
            $('#pos-categories').html(html);
        }

        function renderProducts(catId = 'all', search = '') {
            let html = '';
            products.forEach(p => {
                if ((catId === 'all' || p.cat_id === catId) && p.name.toLowerCase().includes(search.toLowerCase())) {
                    html += `
                        <div class="col-md-4 col-lg-3">
                            <div class="product-card" onclick="addToCart(${p.id})">
                                <span class="stock-badge">${p.stock} IN STOCK</span>
                                <div class="text-center py-4"><img src="${p.img}" style="height:100px; object-fit:contain;"></div>
                                <h6 class="fw-bold mb-1">${p.name}</h6>
                                <p class="text-primary fw-800 mb-0">$${p.price.toLocaleString()}</p>
                            </div>
                        </div>
                    `;
                }
            });
            $('#product-grid').html(html);
        }

        function addToCart(id) {
            const product = products.find(p => p.id === id);
            if (cart[id]) {
                cart[id].qty++;
            } else {
                cart[id] = { ...product, qty: 1 };
            }
            updateCartUI();
        }

        function updateQty(id, delta) {
            if (cart[id]) {
                cart[id].qty += delta;
                if (cart[id].qty <= 0) delete cart[id];
                updateCartUI();
            }
        }

        function updateCartUI() {
            let html = '';
            let subtotal = 0;

            Object.values(cart).forEach(item => {
                let lineTotal = item.price * item.qty;
                subtotal += lineTotal;
                html += `
                    <div class="cart-item-row">
                        <div class="bg-light rounded-4 p-2 me-3" style="width:60px; height:60px;">
                            <img src="${item.img}" class="img-fluid">
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-bold small text-truncate" style="max-width:140px;">${item.name}</p>
                            <span class="text-muted extra-small fw-bold text-primary">$${item.price}</span>
                        </div>
                        <div class="qty-controls me-3">
                            <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                            <span class="px-2 small fw-800">${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                        </div>
                        <div class="text-end fw-800 small" style="width:70px;">$${lineTotal.toLocaleString()}</div>
                    </div>
                `;
            });

            $('#cart-list').html(html || '<div class="text-center mt-5"><i class="fa-solid fa-cart-shopping fs-1 text-light mb-3 d-block"></i><p class="text-muted">Your cart is empty</p></div>');
            
            let tax = subtotal * TAX_RATE;
            let total = subtotal + tax;
            
            $('#tax-amount').text('$' + tax.toFixed(2));
            $('#total-usd').text('$' + total.toLocaleString(undefined, {minimumFractionDigits: 2}));
            $('#total-riel').text((total * KHR_RATE).toLocaleString());
        }

        function filterCat(id, el) {
            $('.cat-item').removeClass('active');
            $(el).addClass('active');
            renderProducts(id, $('#search-input').val());
        }

        $('#search-input').on('input', function() {
            const activeCat = $('.cat-item.active').data('id') || 'all';
            renderProducts(activeCat, $(this).val());
        });

        function setPayment(method, el) {
            selectedPayment = method;
            $('.payment-method').removeClass('active');
            $(el).addClass('active');
        }

        function simulateScan() {
            const p = products[Math.floor(Math.random() * products.length)];
            addToCart(p.id);
        }

        function processOrder() {
            if (Object.keys(cart).length === 0) return alert("Cart is empty");
            alert(`✅ Order successful!\nTotal: ${$('#total-usd').text()}\nPayment: ${selectedPayment}`);
            cart = {};
            updateCartUI();
        }

        function updateDate() {
            $('#live-date').text(new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));
        }

        $(document).ready(init);
    </script>
</x-guest-layout>
