<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>FlowPOS – Point of Sale</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg:        #f4f3ef;
    --surface:   #ffffff;
    --surface2:  #f8f7f4;
    --border:    rgba(0,0,0,0.08);
    --border2:   rgba(0,0,0,0.14);
    --text:      #1a1917;
    --muted:     #6b6a66;
    --faint:     #aeada8;
    --accent:    #2d2a26;
    --accent2:   #4e4a44;
    --purple:    #5b4fcf;
    --purple-lt: #eeecfb;
    --green:     #1a7a4a;
    --green-lt:  #e3f5ec;
    --red:       #c0392b;
    --red-lt:    #fdecea;
    --amber:     #b45309;
    --amber-lt:  #fef3e2;
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 18px;
    --sidebar:   90px;
    --cart:      400px;
    --shadow:    0 1px 3px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
  }

  html, body { height: 100%; overflow: hidden; }
  body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); font-size: 14px; }

  .pos { display: flex; height: 100vh; }

  /* ── CATEGORY SIDEBAR ── */
  .sidebar {
    width: var(--sidebar);
    background: var(--surface);
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column; align-items: center;
    padding: 20px 0; gap: 6px; overflow-y: auto; flex-shrink: 0;
  }
  .logo {
    width: 46px; height: 46px; background: var(--accent);
    border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 22px; margin-bottom: 14px; flex-shrink: 0;
  }
  .cat-btn {
    width: 66px; height: 64px; border: 1px solid transparent;
    background: transparent; border-radius: var(--radius-md);
    color: var(--faint); font-size: 11px; font-weight: 500;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 4px; cursor: pointer; transition: all 0.18s ease; flex-shrink: 0;
    line-height: 1.2; text-align: center;
  }
  .cat-btn i { font-size: 22px; }
  .cat-btn:hover { background: var(--surface2); color: var(--muted); }
  .cat-btn.active { background: var(--purple-lt); color: var(--purple); border-color: rgba(91,79,207,0.2); }

  /* ── MAIN ── */
  .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }

  .topbar {
    padding: 18px 24px 16px;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    flex-shrink: 0;
  }
  .topbar-left h2 { font-size: 17px; font-weight: 600; }
  .topbar-left p  { font-size: 12px; color: var(--muted); margin-top: 2px; }
  .topbar-right   { display: flex; gap: 8px; align-items: center; }

  .search-box {
    display: flex; align-items: center; gap: 8px;
    background: var(--surface2); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 0 12px; height: 36px; width: 240px;
    transition: border-color 0.15s;
  }
  .search-box:focus-within { border-color: var(--purple); }
  .search-box i { color: var(--faint); font-size: 16px; }
  .search-box input { border: none; background: transparent; outline: none; font-size: 13px; font-family: inherit; color: var(--text); flex: 1; }
  .search-box input::placeholder { color: var(--faint); }

  .icon-btn {
    width: 36px; height: 36px; border-radius: var(--radius-sm);
    border: 1px solid var(--border); background: var(--surface2);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    color: var(--muted); font-size: 17px; transition: all 0.15s;
  }
  .icon-btn:hover { background: var(--surface); border-color: var(--border2); color: var(--text); }

  /* ── PRODUCT GRID ── */
  .products-wrap { flex: 1; overflow-y: auto; padding: 20px 24px; }
  .section-label { font-size: 11px; font-weight: 600; color: var(--faint); text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 12px; }
  .product-grid  { display: grid; grid-template-columns: repeat(auto-fill, minmax(148px, 1fr)); gap: 10px; }

  .product-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 16px 12px 14px;
    cursor: pointer; transition: all 0.2s ease; position: relative; user-select: none;
  }
  .product-card:hover { border-color: var(--purple); transform: translateY(-2px); box-shadow: var(--shadow); }
  .product-card:active { transform: scale(0.97); }
  .product-card.out   { opacity: 0.42; pointer-events: none; }

  .card-badge { position: absolute; top: 10px; right: 10px; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px; }
  .badge-stock { background: var(--surface2); color: var(--muted); }
  .badge-out   { background: var(--red-lt);   color: var(--red);   }
  .badge-low   { background: var(--amber-lt); color: var(--amber); }

  .card-emoji { font-size: 34px; text-align: center; margin-bottom: 10px; line-height: 1; }
  .card-name  { font-size: 13px; font-weight: 500; color: var(--text); margin-bottom: 4px; line-height: 1.3; }
  .card-price { font-size: 15px; font-weight: 600; color: var(--purple); }
  .card-sku   { font-size: 10px; color: var(--faint); margin-top: 2px; font-family: 'DM Mono', monospace; }

  @keyframes flash-add {
    0%   { background: var(--purple-lt); }
    100% { background: var(--surface); }
  }
  .product-card.added { animation: flash-add 0.4s ease; }

  .empty-products { grid-column: 1/-1; text-align: center; padding: 48px 0; color: var(--faint); }
  .empty-products i { font-size: 40px; display: block; margin-bottom: 10px; }

  /* ── CART ── */
  .cart { width: var(--cart); background: var(--surface); border-left: 1px solid var(--border); display: flex; flex-direction: column; flex-shrink: 0; }

  .cart-head { padding: 16px 18px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
  .cart-head-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
  .cart-head-row h3 { font-size: 15px; font-weight: 600; }
  .order-id { font-size: 11px; font-family: 'DM Mono', monospace; color: var(--faint); background: var(--surface2); padding: 3px 8px; border-radius: 20px; }

  /* selling type toggle */
  .selling-type-row { display: flex; gap: 6px; margin-bottom: 10px; }
  .selling-type-btn {
    flex: 1; height: 30px; border-radius: var(--radius-sm);
    border: 1px solid var(--border); background: var(--surface2);
    cursor: pointer; font-size: 11px; font-weight: 600; font-family: inherit;
    color: var(--muted); transition: all 0.15s;
  }
  .selling-type-btn.active { border-color: var(--purple); background: var(--purple-lt); color: var(--purple); }

  .customer-row { display: flex; gap: 8px; }
  .customer-select-wrap { flex: 1; position: relative; }
  .customer-select-wrap i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 16px; color: var(--faint); pointer-events: none; }
  .customer-select-wrap select {
    width: 100%; height: 34px; padding: 0 10px 0 32px;
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface2); font-family: inherit; font-size: 13px;
    color: var(--text); cursor: pointer; outline: none; appearance: none;
    transition: border-color 0.15s;
  }
  .customer-select-wrap select:focus { border-color: var(--purple); }
  .small-btn {
    height: 34px; padding: 0 12px; border-radius: var(--radius-sm);
    border: 1px solid var(--border); background: var(--surface2);
    cursor: pointer; font-size: 13px; font-family: inherit; color: var(--muted);
    display: flex; align-items: center; gap: 5px; white-space: nowrap; transition: all 0.15s;
  }
  .small-btn:hover { border-color: var(--border2); color: var(--text); background: var(--surface); }

  .cart-items { flex: 1; overflow-y: auto; padding: 12px 18px; }
  .empty-cart { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; gap: 8px; color: var(--faint); }
  .empty-cart i { font-size: 42px; }
  .empty-cart p { font-size: 13px; }

  .cart-row { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--border); }
  .cart-row:last-child { border-bottom: none; }
  .cart-thumb { width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--surface2); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
  .cart-info { flex: 1; min-width: 0; }
  .cart-info p    { font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .cart-info span { font-size: 11px; color: var(--muted); }

  .qty-ctrl { display: flex; align-items: center; gap: 2px; background: var(--surface2); border-radius: var(--radius-sm); border: 1px solid var(--border); padding: 2px; }
  .qty-ctrl button { width: 26px; height: 26px; border: none; background: none; cursor: pointer; font-size: 15px; font-weight: 600; color: var(--purple); border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: background 0.13s; }
  .qty-ctrl button:hover { background: var(--purple-lt); }
  .qty-ctrl span { min-width: 26px; text-align: center; font-size: 13px; font-weight: 600; font-family: 'DM Mono', monospace; }

  .cart-line { font-size: 13px; font-weight: 600; width: 56px; text-align: right; flex-shrink: 0; }
  .remove-btn { width: 26px; height: 26px; border: none; background: none; cursor: pointer; color: var(--faint); font-size: 16px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: all 0.13s; flex-shrink: 0; }
  .remove-btn:hover { background: var(--red-lt); color: var(--red); }

  .discount-row {
    display: flex; align-items: center; gap: 8px;
    background: var(--surface2); border: 1px dashed var(--border2);
    border-radius: var(--radius-sm); padding: 8px 12px; margin: 0 18px 12px;
    flex-shrink: 0;
  }
  .discount-row i { color: var(--amber); font-size: 16px; }
  .discount-row span { font-size: 12px; color: var(--muted); flex: 1; }
  .discount-input { width: 60px; height: 28px; border: 1px solid var(--border2); border-radius: 6px; text-align: center; font-size: 13px; font-family: 'DM Mono', monospace; outline: none; background: var(--surface); color: var(--text); transition: border-color 0.15s; }
  .discount-input:focus { border-color: var(--amber); }
  .disc-unit { font-size: 12px; font-weight: 600; color: var(--muted); }

  /* note row */
  .note-row {
    display: flex; align-items: center; gap: 8px;
    background: var(--surface2); border: 1px dashed var(--border2);
    border-radius: var(--radius-sm); padding: 8px 12px; margin: 0 18px 12px;
    flex-shrink: 0;
  }
  .note-row i { color: var(--muted); font-size: 16px; }
  .note-input {
    flex: 1; border: none; background: transparent; outline: none;
    font-size: 12px; font-family: inherit; color: var(--text);
  }
  .note-input::placeholder { color: var(--faint); }

  .cart-foot { padding: 14px 18px; border-top: 1px solid var(--border); flex-shrink: 0; }
  .summary { margin-bottom: 14px; }
  .sum-row { display: flex; justify-content: space-between; font-size: 13px; padding: 3px 0; color: var(--muted); }
  .sum-row.discount { color: var(--green); }
  .sum-row.total { font-size: 19px; font-weight: 700; color: var(--text); padding-top: 10px; border-top: 1px solid var(--border); margin-top: 8px; }
  .khr-tag { font-size: 12px; font-family: 'DM Mono', monospace; background: var(--green-lt); color: var(--green); border-radius: 20px; padding: 2px 10px; }

  .pay-methods { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; margin-bottom: 10px; }
  .pay-btn { border: 1px solid var(--border); background: var(--surface2); border-radius: var(--radius-sm); padding: 9px 4px 7px; cursor: pointer; font-size: 11px; font-weight: 600; color: var(--muted); text-align: center; transition: all 0.15s; font-family: inherit; }
  .pay-btn i { display: block; font-size: 19px; margin-bottom: 3px; }
  .pay-btn:hover { border-color: var(--border2); color: var(--text); }
  .pay-btn.active { border-color: var(--purple); background: var(--purple-lt); color: var(--purple); }

  .cash-tender { display: none; flex-direction: column; gap: 6px; margin-bottom: 10px; }
  .cash-tender.show { display: flex; }
  .tender-label { font-size: 11px; font-weight: 600; color: var(--muted); }
  .tender-row { display: flex; gap: 6px; }
  .tender-input-wrap { position: relative; flex: 1; }
  .tender-input-wrap span { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 13px; color: var(--muted); pointer-events: none; }
  .tender-input { width: 100%; height: 34px; padding: 0 10px 0 22px; border: 1px solid var(--border2); border-radius: var(--radius-sm); font-family: 'DM Mono', monospace; font-size: 14px; color: var(--text); background: var(--surface); outline: none; transition: border-color 0.15s; }
  .tender-input:focus { border-color: var(--purple); }
  .quick-btns { display: flex; gap: 4px; flex-wrap: wrap; }
  .quick-btn { padding: 4px 10px; border-radius: var(--radius-sm); border: 1px solid var(--border2); background: var(--surface2); font-size: 11px; font-weight: 600; cursor: pointer; font-family: inherit; color: var(--muted); transition: all 0.13s; }
  .quick-btn:hover { background: var(--purple-lt); color: var(--purple); border-color: var(--purple); }
  .change-row { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; }
  .change-row.ok    { color: var(--green); font-weight: 600; }
  .change-row.short { color: var(--red); }

  .btn-order { width: 100%; height: 50px; border-radius: var(--radius-md); border: none; background: var(--accent); color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; letter-spacing: 0.01em; }
  .btn-order:hover:not(:disabled) { background: #111; transform: translateY(-1px); }
  .btn-order:active:not(:disabled) { transform: scale(0.98); }
  .btn-order:disabled { opacity: 0.38; cursor: not-allowed; }

  /* ── MODAL ── */
  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.38); display: flex; align-items: center; justify-content: center; z-index: 200; animation: fade-in 0.18s ease; }
  @keyframes fade-in  { from { opacity: 0; } to { opacity: 1; } }
  @keyframes slide-up { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

  .modal { background: var(--surface); border-radius: var(--radius-lg); width: 340px; max-height: 90vh; overflow-y: auto; padding: 28px 24px 24px; animation: slide-up 0.22s ease; box-shadow: 0 8px 40px rgba(0,0,0,0.18); }
  .modal-success { background: var(--green-lt); border-radius: var(--radius-sm); padding: 10px 14px; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
  .modal-success i    { color: var(--green); font-size: 20px; }
  .modal-success span { font-size: 13px; font-weight: 600; color: var(--green); }

  .receipt-header { text-align: center; margin-bottom: 20px; }
  .receipt-header h3 { font-size: 20px; font-weight: 700; }
  .receipt-header p  { font-size: 12px; color: var(--muted); margin-top: 4px; }
  .receipt-meta { display: flex; justify-content: space-between; font-size: 12px; color: var(--muted); margin-bottom: 6px; }
  .receipt-meta span b { color: var(--text); font-weight: 500; }
  .dashed { border: none; border-top: 1.5px dashed var(--border2); margin: 14px 0; }

  .receipt-item { display: flex; justify-content: space-between; font-size: 13px; padding: 3px 0; }
  .receipt-item .rname  { flex: 1; color: var(--muted); }
  .receipt-item .rqty   { width: 30px; text-align: center; color: var(--muted); }
  .receipt-item .rprice { width: 60px; text-align: right; font-weight: 500; }

  .receipt-totals { margin-top: 4px; }
  .rtotal-row          { display: flex; justify-content: space-between; font-size: 13px; padding: 3px 0; color: var(--muted); }
  .rtotal-row.discount { color: var(--green); }
  .rtotal-row.final    { font-size: 18px; font-weight: 700; color: var(--text); padding-top: 10px; margin-top: 4px; border-top: 1px solid var(--border); }

  .receipt-khr { text-align: center; font-family: 'DM Mono', monospace; color: var(--muted); font-size: 13px; margin: 8px 0 16px; }
  .receipt-pay { display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; color: var(--muted); margin-bottom: 16px; }
  .receipt-pay span { font-weight: 600; color: var(--text); }

  .change-display { background: var(--surface2); border-radius: var(--radius-sm); padding: 10px 14px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 13px; }
  .change-display .label { color: var(--muted); }
  .change-display .value { font-size: 16px; font-weight: 700; color: var(--green); }

  .modal-actions { display: flex; gap: 8px; }
  .btn-new-order { flex: 1; height: 42px; border-radius: var(--radius-md); border: none; background: var(--accent); color: #fff; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.15s; }
  .btn-new-order:hover { background: #111; }
  .btn-print { height: 42px; padding: 0 16px; border-radius: var(--radius-md); border: 1px solid var(--border2); background: var(--surface2); color: var(--muted); font-size: 14px; font-weight: 500; cursor: pointer; font-family: inherit; display: flex; align-items: center; gap: 6px; transition: all 0.15s; }
  .btn-print:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-lt); }

  /* ── TOAST ── */
  .toast { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%) translateY(8px); background: var(--accent); color: #fff; padding: 10px 18px; border-radius: var(--radius-md); font-size: 13px; font-weight: 500; opacity: 0; transition: all 0.25s; z-index: 300; white-space: nowrap; pointer-events: none; box-shadow: 0 4px 16px rgba(0,0,0,0.22); }
  .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

  /* ── LOADING SPINNER ── */
  .spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }

  ::-webkit-scrollbar { width: 4px; height: 4px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }

  @media print {
    body * { visibility: hidden; }
    #print-area, #print-area * { visibility: visible; }
    #print-area { position: fixed; top: 0; left: 0; width: 100%; font-size: 13px; padding: 20px; }
  }
</style>
</head>
<body>

<div class="pos" id="pos-app">
  <!-- CATEGORY SIDEBAR -->
  <aside class="sidebar">
    <div class="logo"><i class="ti ti-bolt"></i></div>
    <div id="cat-list">
      @foreach($categories as $cat)
        <button
          class="cat-btn {{ $cat['id'] === 'all' ? 'active' : '' }}"
          data-cat="{{ $cat['id'] }}"
          onclick="filterCat('{{ $cat['id'] }}', this)"
        >
          <i class="ti {{ $cat['icon'] }}"></i>
          <span>{{ $cat['name'] }}</span>
        </button>
      @endforeach
    </div>
  </aside>

  <!-- MAIN PRODUCT AREA -->
  <main class="main">
    <div class="topbar">
      <div class="topbar-left">
        <h2>FlowPOS</h2>
        <p id="live-date"></p>
      </div>
      <div class="topbar-right">
        <div class="search-box">
          <i class="ti ti-search"></i>
          <input type="text" id="search-input" placeholder="Search products…">
        </div>
        <button class="icon-btn" title="Toggle discount panel" onclick="toggleDiscount()"><i class="ti ti-tag"></i></button>
        <button class="icon-btn" title="Toggle order note" onclick="toggleNote()"><i class="ti ti-notes"></i></button>
        <button class="icon-btn" title="Simulate barcode scan" onclick="simulateScan()"><i class="ti ti-barcode"></i></button>
        <button class="icon-btn" title="Reload products" onclick="loadProducts()"><i class="ti ti-refresh"></i></button>
      </div>
    </div>

    <div class="products-wrap">
      <p class="section-label" id="section-label">All Products</p>
      <div class="product-grid" id="product-grid">
        <div class="empty-products"><i class="ti ti-loader"></i><p>Loading…</p></div>
      </div>
    </div>
  </main>

  <!-- CART SIDEBAR -->
  <aside class="cart">
    <div class="cart-head">
      <div class="cart-head-row">
        <h3>Current Order</h3>
        <span class="order-id" id="order-id">{{ $nextOrderNumber }}</span>
      </div>

      {{-- Selling Type toggle (maps to orders.selling_type ENUM) --}}
      <div class="selling-type-row">
        <button class="selling-type-btn active" id="btn-retail"  onclick="setSellingType('Retail',this)">Retail</button>
        <button class="selling-type-btn"        id="btn-wholesale" onclick="setSellingType('Wholesale',this)">Wholesale</button>
      </div>

      {{-- Customer (maps to orders.customer_id FK) --}}
      <div class="customer-row">
        <div class="customer-select-wrap">
          <i class="ti ti-user"></i>
          <select id="customer-select">
            @foreach($customers as $customer)
              <option value="{{ $customer->id }}">
                {{ $customer->type !== 'walk-in' ? ucfirst($customer->type) . ' – ' : '' }}{{ $customer->name }}
              </option>
            @endforeach
          </select>
        </div>
        <button class="small-btn" onclick="addNewCustomer()"><i class="ti ti-plus"></i> New</button>
      </div>
    </div>

    <div class="cart-items" id="cart-list"></div>

    {{-- DISCOUNT ROW (maps to orders.discount_amount) --}}
    <div class="discount-row" id="discount-row" style="display:none">
      <i class="ti ti-tag"></i>
      <span>Discount</span>
      <input type="number" class="discount-input" id="discount-val" value="0" min="0" max="100" oninput="renderCart()">
      <span class="disc-unit" id="disc-unit">%</span>
      <button class="small-btn" onclick="toggleDiscUnit()" title="Switch between % and $" style="padding:0 8px;">⇄</button>
    </div>

    {{-- NOTE ROW (maps to orders.note) --}}
    <div class="note-row" id="note-row" style="display:none">
      <i class="ti ti-notes"></i>
      <input type="text" class="note-input" id="order-note" placeholder="Order note…" maxlength="500">
    </div>

    <!-- CART FOOTER -->
    <div class="cart-foot">
      <div class="summary">
        <div class="sum-row"><span>Subtotal</span><span id="sum-subtotal">$0.00</span></div>
        <div class="sum-row discount" id="discount-line" style="display:none"><span>Discount</span><span id="sum-discount">-$0.00</span></div>
        {{-- Tax row label reflects order.tax_amount --}}
        <div class="sum-row"><span>Tax (10%)</span><span id="sum-tax">$0.00</span></div>
        <div class="sum-row total">
          <span>Total</span>
          <div style="text-align:right">
            {{-- maps to orders.total_amount --}}
            <div id="sum-total">$0.00</div>
            <div class="khr-tag" id="sum-khr">0 ៛</div>
          </div>
        </div>
      </div>

      {{-- Payment method maps to orders.payment_method --}}
      <div class="pay-methods">
        <button class="pay-btn active" data-method="CASH" onclick="setPayment('CASH',this)"><i class="ti ti-cash"></i>Cash</button>
        <button class="pay-btn" data-method="CARD" onclick="setPayment('CARD',this)"><i class="ti ti-credit-card"></i>Card</button>
        <button class="pay-btn" data-method="QR" onclick="setPayment('QR',this)"><i class="ti ti-qrcode"></i>QR Pay</button>
      </div>

      <div class="cash-tender show" id="cash-tender">
        <div class="tender-label">Cash Tendered</div>
        <div class="tender-row">
          <div class="tender-input-wrap">
            <span>$</span>
            {{-- cash_tendered still tracked locally for change calc — sent in payload --}}
            <input type="number" class="tender-input" id="tender-input" value="" placeholder="0.00" oninput="calcChange()">
          </div>
        </div>
        <div class="quick-btns" id="quick-btns"></div>
        <div class="change-row" id="change-display"></div>
      </div>

      <button class="btn-order" id="order-btn" onclick="processOrder()" disabled>
        <i class="ti ti-check"></i> Complete Order
      </button>
    </div>
  </aside>
</div>

<div class="toast" id="toast"></div>
<div id="print-area" style="display:none"></div>

<script>
/* ═══════════════════════════════════════════════
   CONFIG  (injected from Laravel)
═══════════════════════════════════════════════ */
const KHR_RATE = 4100;
const TAX_RATE = 0.10;

const ROUTES = {
  products:  '{{ route("sales.pos.products") }}',
  orders:    '{{ route("sales.pos.orders.store") }}',
  customers: '{{ route("sales.pos.customers.store") }}',
};

const CSRF = document.querySelector('meta[name="csrf-token"]').content;

/* ═══════════════════════════════════════════════
   STATE
═══════════════════════════════════════════════ */
let cart              = {};        // { productId: { ...product, qty } }
let allProducts       = [];        // full product list fetched from API
let selectedPayment   = 'CASH';    // maps to orders.payment_method
let selectedSellingType = 'Retail'; // maps to orders.selling_type ENUM
let activeCat         = 'all';
let discountIsPercent = true;      // true = % discount, false = fixed $ discount

/* ═══════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  renderDate();
  loadProducts();
  renderCart();

  let searchTimer;
  document.getElementById('search-input').addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(renderProducts, 200);
  });
});

function renderDate() {
  document.getElementById('live-date').textContent =
    new Date().toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
}

/* ═══════════════════════════════════════════════
   SELLING TYPE  (orders.selling_type)
═══════════════════════════════════════════════ */
function setSellingType(type, btn) {
  selectedSellingType = type;
  document.querySelectorAll('.selling-type-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

/* ═══════════════════════════════════════════════
   PRODUCTS — fetch from Laravel API
═══════════════════════════════════════════════ */
async function loadProducts() {
  const grid = document.getElementById('product-grid');
  grid.innerHTML = `<div class="empty-products"><span class="spinner" style="border-color:var(--border2);border-top-color:var(--purple)"></span></div>`;

  try {
    const params = new URLSearchParams();
    if (activeCat !== 'all') params.set('category', activeCat);
    const search = document.getElementById('search-input').value.trim();
    if (search) params.set('search', search);

    const res  = await fetch(`${ROUTES.products}?${params}`, { headers: { 'Accept': 'application/json' } });
    allProducts = await res.json();
    renderProducts();
  } catch (e) {
    grid.innerHTML = `<div class="empty-products"><i class="ti ti-alert-circle"></i><p>Failed to load products</p></div>`;
  }
}

function renderProducts() {
  const search   = document.getElementById('search-input').value.toLowerCase();
  const grid     = document.getElementById('product-grid');

  const filtered = allProducts.filter(p =>
    (activeCat === 'all' || p.category === activeCat) &&
    (p.product_name.toLowerCase().includes(search) || p.sku.toLowerCase().includes(search))
  );

  if (!filtered.length) {
    grid.innerHTML = `<div class="empty-products"><i class="ti ti-mood-sad"></i><p>No products found</p></div>`;
    return;
  }

  grid.innerHTML = filtered.map(p => {
    let badgeClass = 'badge-stock', badgeText = p.stock + ' left';
    if (p.stock === 0)    { badgeClass = 'badge-out'; badgeText = 'Out'; }
    else if (p.stock <= 5){ badgeClass = 'badge-low'; badgeText = p.stock + ' left'; }

    return `
      <div class="product-card ${p.stock === 0 ? 'out' : ''}"
           onclick="addToCart(${p.id})" id="pcard-${p.id}">
        <span class="card-badge ${badgeClass}">${badgeText}</span>
        <div class="card-emoji">${p.emoji}</div>
        <div class="card-name">${p.product_name}</div>
        <div class="card-price">$${parseFloat(p.price).toFixed(2)}</div>
        <div class="card-sku">${p.sku}</div>
      </div>`;
  }).join('');
}

/* ═══════════════════════════════════════════════
   CATEGORIES
═══════════════════════════════════════════════ */
function filterCat(id, btn) {
  activeCat = id;
  document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const labels = {
    all:'All Products', drinks:'Drinks Products', food:'Food Products',
    snacks:'Snacks Products', dessert:'Dessert Products'
  };
  document.getElementById('section-label').textContent = labels[id] || 'Products';
  renderProducts();
}

/* ═══════════════════════════════════════════════
   CART OPERATIONS
═══════════════════════════════════════════════ */
function addToCart(id) {
  const p = allProducts.find(x => x.id === id);
  if (!p || p.stock === 0) return;

  const inCart = cart[id] ? cart[id].qty : 0;
  if (inCart >= p.stock) { showToast('Max stock reached for ' + p.product_name); return; }

  if (cart[id]) cart[id].qty++;
  else cart[id] = { ...p, qty: 1 };

  const card = document.getElementById('pcard-' + id);
  if (card) { card.classList.add('added'); setTimeout(() => card.classList.remove('added'), 400); }

  renderCart();
}

function updateQty(id, delta) {
  if (!cart[id]) return;
  cart[id].qty += delta;
  if (cart[id].qty <= 0) delete cart[id];
  renderCart();
}

function removeItem(id) { delete cart[id]; renderCart(); }

function clearCart() {
  cart = {};
  document.getElementById('discount-val').value = 0;
  document.getElementById('order-note').value   = '';
  renderCart();
}

/* ═══════════════════════════════════════════════
   CART RENDER
═══════════════════════════════════════════════ */
function renderCart() {
  const list  = document.getElementById('cart-list');
  const items = Object.values(cart);

  if (!items.length) {
    list.innerHTML = `<div class="empty-cart"><i class="ti ti-shopping-cart-off"></i><p>Add items to get started</p></div>`;
    updateTotals(0, 0, 0, 0);
    document.getElementById('order-btn').disabled = true;
    return;
  }

  document.getElementById('order-btn').disabled = false;

  list.innerHTML = items.map(item => {
    // line subtotal per order_items.subtotal = unit_price * qty (before item-level disc/tax)
    const lineSubtotal = parseFloat(item.price) * item.qty;
    return `
      <div class="cart-row">
        <div class="cart-thumb">${item.emoji}</div>
        <div class="cart-info">
          <p>${item.product_name}</p>
          <span>$${parseFloat(item.price).toFixed(2)} × ${item.qty}</span>
        </div>
        <div class="qty-ctrl">
          <button onclick="updateQty(${item.id},-1)">−</button>
          <span>${item.qty}</span>
          <button onclick="updateQty(${item.id},1)">+</button>
        </div>
        <div class="cart-line">$${lineSubtotal.toFixed(2)}</div>
        <button class="remove-btn" onclick="removeItem(${item.id})"><i class="ti ti-x"></i></button>
      </div>`;
  }).join('');

  // orders.subtotal = sum of (unit_price * qty)
  const subtotal    = items.reduce((s, i) => s + parseFloat(i.price) * i.qty, 0);
  // orders.discount_amount
  const discountAmt = calcDiscountAmt(subtotal);
  const taxable     = subtotal - discountAmt;
  // orders.tax_amount
  const tax         = taxable * TAX_RATE;
  // orders.total_amount
  const total       = taxable + tax;

  updateTotals(subtotal, discountAmt, tax, total);
  renderQuickCash(total);
  calcChange();
}

function calcDiscountAmt(subtotal) {
  const val = parseFloat(document.getElementById('discount-val').value) || 0;
  return discountIsPercent
    ? Math.min(subtotal, subtotal * (val / 100))
    : Math.min(subtotal, val);
}

function updateTotals(sub, disc, tax, total) {
  document.getElementById('sum-subtotal').textContent = '$' + sub.toFixed(2);
  document.getElementById('sum-tax').textContent      = '$' + tax.toFixed(2);
  document.getElementById('sum-total').textContent    = '$' + total.toFixed(2);
  document.getElementById('sum-khr').textContent      = Math.round(total * KHR_RATE).toLocaleString() + ' ៛';

  const discLine = document.getElementById('discount-line');
  if (disc > 0) {
    discLine.style.display = '';
    document.getElementById('sum-discount').textContent = '-$' + disc.toFixed(2);
  } else {
    discLine.style.display = 'none';
  }
}

/* ═══════════════════════════════════════════════
   DISCOUNT & NOTE TOGGLES
═══════════════════════════════════════════════ */
function toggleDiscount() {
  const row = document.getElementById('discount-row');
  row.style.display = row.style.display === 'none' ? 'flex' : 'none';
}

function toggleNote() {
  const row = document.getElementById('note-row');
  row.style.display = row.style.display === 'none' ? 'flex' : 'none';
  if (row.style.display !== 'none') document.getElementById('order-note').focus();
}

function toggleDiscUnit() {
  discountIsPercent = !discountIsPercent;
  document.getElementById('disc-unit').textContent = discountIsPercent ? '%' : '$';
  document.getElementById('discount-val').max = discountIsPercent ? 100 : 99999;
  renderCart();
}

/* ═══════════════════════════════════════════════
   PAYMENT METHOD  (orders.payment_method)
═══════════════════════════════════════════════ */
function setPayment(method, btn) {
  selectedPayment = method;
  document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const tender = document.getElementById('cash-tender');
  method === 'CASH' ? tender.classList.add('show') : tender.classList.remove('show');
}

/* ═══════════════════════════════════════════════
   CASH / CHANGE
═══════════════════════════════════════════════ */
function renderQuickCash(total) {
  const qb = document.getElementById('quick-btns');
  if (!qb || !total) return;
  const suggested = [
    Math.ceil(total), Math.ceil(total/5)*5,
    Math.ceil(total/10)*10, Math.ceil(total/20)*20,
    Math.ceil(total/50)*50,
  ].filter((v, i, a) => v >= total && a.indexOf(v) === i).slice(0, 4);

  qb.innerHTML = suggested.map(v =>
    `<button class="quick-btn" onclick="setTender(${v})">$${v}</button>`
  ).join('');
}

function setTender(val) {
  document.getElementById('tender-input').value = val.toFixed(2);
  calcChange();
}

function calcChange() {
  const tender = parseFloat(document.getElementById('tender-input').value) || 0;
  const items  = Object.values(cart);
  const el     = document.getElementById('change-display');
  if (!items.length || tender === 0) { el.innerHTML = ''; return; }

  const sub    = items.reduce((s, i) => s + parseFloat(i.price) * i.qty, 0);
  const disc   = calcDiscountAmt(sub);
  const tax    = (sub - disc) * TAX_RATE;
  const total  = (sub - disc) + tax;
  const change = tender - total;

  if (change >= 0) {
    el.className = 'change-row ok';
    el.innerHTML = `<span>Change</span><span>$${change.toFixed(2)} / ${Math.round(change * KHR_RATE).toLocaleString()} ៛</span>`;
  } else {
    el.className = 'change-row short';
    el.innerHTML = `<span>Short by</span><span>$${Math.abs(change).toFixed(2)}</span>`;
  }
}

/* ═══════════════════════════════════════════════
   ORDER PROCESSING  — posts to Laravel
   Payload matches updated Order + OrderItem models
═══════════════════════════════════════════════ */
async function processOrder() {
  const items = Object.values(cart);
  if (!items.length) return;

  const btn = document.getElementById('order-btn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Processing…';

  // --- compute totals (mirror Order model fields) ---
  const subtotal      = items.reduce((s, i) => s + parseFloat(i.price) * i.qty, 0);
  const discountAmt   = calcDiscountAmt(subtotal);
  const taxAmount     = (subtotal - discountAmt) * TAX_RATE;
  const totalAmount   = (subtotal - discountAmt) + taxAmount;

  const tender        = parseFloat(document.getElementById('tender-input').value) || 0;
  const discVal       = parseFloat(document.getElementById('discount-val').value) || 0;
  const orderNote     = document.getElementById('order-note').value.trim();

  const custSel       = document.getElementById('customer-select');
  const customerId    = custSel.value;
  const customerName  = custSel.options[custSel.selectedIndex].text;

  /*
   * Payload keys match the updated Order $fillable:
   *   customer_id, selling_type, subtotal, discount_amount,
   *   tax_amount, total_amount, payment_method, payment_status,
   *   note, shipping_amount
   *
   * Each item maps to OrderItem $fillable:
   *   product_id, product_name, sku, quantity, unit_price,
   *   tax_type, tax_value, tax_amount, discount_amount, subtotal
   */
  const payload = {
    customer_id:      customerId,
    selling_type:     selectedSellingType,           // 'Retail' | 'Wholesale'
    subtotal:         subtotal.toFixed(2),
    discount_amount:  discountAmt.toFixed(2),
    tax_amount:       taxAmount.toFixed(2),
    total_amount:     totalAmount.toFixed(2),
    shipping_amount:  '0.00',
    payment_method:   selectedPayment,
    payment_status:   'Paid',
    note:             orderNote || null,
    items: items.map(i => {
      const lineSubtotal = parseFloat(i.price) * i.qty;
      return {
        product_id:      i.id,
        product_name:    i.product_name,                     // snapshot
        sku:             i.sku,                      // snapshot
        quantity:        i.qty,
        unit_price:      parseFloat(i.price).toFixed(2),
        tax_type:        i.tax_type  || 'Exclusive', // from product
        tax_value:       i.tax_value || TAX_RATE * 100,
        tax_amount:      (lineSubtotal * TAX_RATE).toFixed(2),
        discount_amount: '0.00',
        subtotal:        lineSubtotal.toFixed(2),
      };
    }),
    // Cash-drawer info (not stored in DB but useful for response/receipt)
    cash_tendered: selectedPayment === 'CASH' ? tender : null,
  };

  try {
    const res  = await fetch(ROUTES.orders, {
      method:  'POST',
      headers: {
        'Content-Type':  'application/json',
        'Accept':        'application/json',
        'X-CSRF-TOKEN':  CSRF,
      },
      body: JSON.stringify(payload),
    });
    const data = await res.json();

    if (!res.ok) {
      showToast(data.error || 'Order failed. Please try again.');
      btn.disabled = false;
      btn.innerHTML = '<i class="ti ti-check"></i> Complete Order';
      return;
    }

    // Update local stock from API response
    if (data.order && data.order.items) {
      data.order.items.forEach(item => {
        const p = allProducts.find(x => x.id === item.product_id);
        if (p) p.stock = Math.max(0, p.stock - item.quantity);
      });
    }

    // Bump order number display for next order
    const orderEl = document.getElementById('order-id');
    if (data.next_order_number) {
      orderEl.textContent = data.next_order_number;
    }

    showReceiptModal(data, totalAmount, tender, customerName, subtotal, discountAmt, taxAmount, discVal);
  } catch (e) {
    showToast('Network error. Please try again.');
    btn.disabled = false;
    btn.innerHTML = '<i class="ti ti-check"></i> Complete Order';
  }
}

/* ═══════════════════════════════════════════════
   RECEIPT MODAL
   Uses updated field names: total_amount, tax_amount, etc.
═══════════════════════════════════════════════ */
function showReceiptModal(data, totalAmount, tender, custName, subtotal, discountAmt, taxAmount, discVal) {
  const order   = data.order;
  // Use server-confirmed values when available
  const sub     = parseFloat(order.subtotal        ?? subtotal);
  const disc    = parseFloat(order.discount_amount ?? discountAmt);
  const tax     = parseFloat(order.tax_amount      ?? taxAmount);
  const tot     = parseFloat(order.total_amount    ?? totalAmount);
  const change  = selectedPayment === 'CASH' ? (tender - tot) : 0;

  const discStr = disc > 0
    ? (discountIsPercent ? discVal + '%' : '$' + discVal.toFixed(2))
    : null;

  const now     = new Date();
  const dateStr = now.toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
  const timeStr = now.toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
  const orderNote = document.getElementById('order-note').value.trim();

  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal">
      <div class="modal-success">
        <i class="ti ti-circle-check"></i>
        <span>Order Completed Successfully</span>
      </div>
      <div class="receipt-header">
        <h3>FlowPOS</h3>
        <p>Tax Invoice / Receipt</p>
      </div>
      <div class="receipt-meta">
        <span>Date: <b>${dateStr}</b></span>
        <span>Time: <b>${timeStr}</b></span>
      </div>
      <div class="receipt-meta">
        <span>Order: <b style="font-family:'DM Mono',monospace">${order.order_number}</b></span>
        <span>Type: <b>${selectedSellingType}</b></span>
      </div>
      <div class="receipt-meta">
        <span>Customer: <b>${custName}</b></span>
        <span>Status: <b style="color:var(--green)">${order.payment_status ?? 'Paid'}</b></span>
      </div>
      ${orderNote ? `<div class="receipt-meta"><span>Note: <b>${orderNote}</b></span></div>` : ''}
      <hr class="dashed">
      <div>
        ${Object.values(cart).map(i => `
          <div class="receipt-item">
            <span class="rname">${i.emoji} ${i.product_name}</span>
            <span class="rqty">×${i.qty}</span>
            <span class="rprice">$${(parseFloat(i.price) * i.qty).toFixed(2)}</span>
          </div>`).join('')}
      </div>
      <hr class="dashed">
      <div class="receipt-totals">
        <div class="rtotal-row"><span>Subtotal</span><span>$${sub.toFixed(2)}</span></div>
        ${discStr ? `<div class="rtotal-row discount"><span>Discount (${discStr})</span><span>-$${disc.toFixed(2)}</span></div>` : ''}
        <div class="rtotal-row"><span>Tax (10%)</span><span>$${tax.toFixed(2)}</span></div>
        <div class="rtotal-row final"><span>TOTAL</span><span>$${tot.toFixed(2)}</span></div>
      </div>
      <div class="receipt-khr">${(data.khr_total ?? Math.round(tot * KHR_RATE)).toLocaleString()} រៀល</div>
      <div class="receipt-pay">
        <i class="ti ${selectedPayment==='CASH'?'ti-cash':selectedPayment==='CARD'?'ti-credit-card':'ti-qrcode'}"></i>
        Paid via <span>${selectedPayment}</span>
        ${selectedPayment === 'CASH' ? `— Tendered: $${tender.toFixed(2)}` : ''}
      </div>
      ${selectedPayment === 'CASH' ? `
        <div class="change-display">
          <span class="label">Change to return</span>
          <span class="value">$${change.toFixed(2)} <small style="font-weight:400;font-size:12px">/ ${Math.round(change*KHR_RATE).toLocaleString()} ៛</small></span>
        </div>` : ''}
      <div class="modal-actions">
        <button class="btn-print" onclick="printReceipt('${order.order_number}','${dateStr} ${timeStr}','${custName}','${selectedPayment}',${sub},${disc},${tax},${tot},'${discStr||''}',${tender})">
          <i class="ti ti-printer"></i> Print
        </button>
        <button class="btn-new-order" onclick="closeModal(this)">New Order</button>
      </div>
    </div>`;

  document.getElementById('pos-app').appendChild(modal);
}

function closeModal(btn) {
  btn.closest('.modal-overlay').remove();
  clearCart();
  document.getElementById('order-btn').disabled = false;
  document.getElementById('order-btn').innerHTML = '<i class="ti ti-check"></i> Complete Order';
  document.getElementById('tender-input').value  = '';
  document.getElementById('change-display').innerHTML = '';
  renderProducts();
  showToast('Order saved — ready for next customer');
}

/* ═══════════════════════════════════════════════
   PRINT
═══════════════════════════════════════════════ */
function printReceipt(ordId, datetime, customer, method, sub, disc, tax, total, discStr, tender) {
  const items  = Object.values(cart);
  const area   = document.getElementById('print-area');
  const change = method === 'CASH' ? (tender - total) : 0;
  area.style.display = 'block';
  area.innerHTML = `
    <div style="font-family:monospace;max-width:300px;margin:0 auto;font-size:13px">
      <div style="text-align:center;margin-bottom:12px">
        <h2 style="font-size:18px">FlowPOS</h2>
        <p>Tax Invoice / Receipt</p>
        <p>${datetime}</p>
        <p>Order: ${ordId}</p>
        <p>Customer: ${customer}</p>
        <p>Type: ${selectedSellingType}</p>
      </div>
      <hr>
      ${items.map(i => `
        <div style="display:flex;justify-content:space-between">
          <span>${i.emoji} ${i.product_name} ×${i.qty}</span>
          <span>$${(parseFloat(i.price)*i.qty).toFixed(2)}</span>
        </div>`).join('')}
      <hr>
      <div style="display:flex;justify-content:space-between"><span>Subtotal</span><span>$${parseFloat(sub).toFixed(2)}</span></div>
      ${discStr ? `<div style="display:flex;justify-content:space-between"><span>Discount (${discStr})</span><span>-$${parseFloat(disc).toFixed(2)}</span></div>` : ''}
      <div style="display:flex;justify-content:space-between"><span>Tax 10%</span><span>$${parseFloat(tax).toFixed(2)}</span></div>
      <div style="display:flex;justify-content:space-between;font-weight:bold;font-size:15px"><span>TOTAL</span><span>$${parseFloat(total).toFixed(2)}</span></div>
      <div style="text-align:center;margin-top:8px">${Math.round(parseFloat(total)*KHR_RATE).toLocaleString()} រៀល</div>
      <div style="text-align:center;margin-top:4px">Payment: ${method}</div>
      ${method === 'CASH' ? `<div style="text-align:center">Change: $${change.toFixed(2)}</div>` : ''}
      <hr>
      <div style="text-align:center;font-size:11px">Thank you for your purchase!<br>Please come again.</div>
    </div>`;
  window.print();
  area.style.display = 'none';
}

/* ═══════════════════════════════════════════════
   BARCODE SCAN SIMULATION
═══════════════════════════════════════════════ */
function simulateScan() {
  const inStock = allProducts.filter(p => p.stock > 0);
  if (!inStock.length) { showToast('All products out of stock'); return; }
  const p = inStock[Math.floor(Math.random() * inStock.length)];
  addToCart(p.id);
  showToast('Scanned: ' + p.emoji + ' ' + p.product_name);
}

/* ═══════════════════════════════════════════════
   ADD CUSTOMER  — posts to Laravel
   Returns customer with id (maps to orders.customer_id FK)
═══════════════════════════════════════════════ */
async function addNewCustomer() {
  const name = window.prompt('Enter customer name:');
  if (!name || !name.trim()) return;

  try {
    const res  = await fetch(ROUTES.customers, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body:    JSON.stringify({ name: name.trim() }),
    });
    const data = await res.json();

    if (!res.ok) { showToast('Could not add customer'); return; }

    const sel = document.getElementById('customer-select');
    const opt = document.createElement('option');
    opt.value       = data.id;
    opt.textContent = data.name;
    sel.appendChild(opt);
    sel.value = data.id;
    showToast('Customer added: ' + data.name);
  } catch {
    showToast('Network error');
  }
}

/* ═══════════════════════════════════════════════
   TOAST
═══════════════════════════════════════════════ */
let toastTimer;
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 2400);
}
</script>
</body>
</html>