{{-- resources/views/reports/product_sales_report.blade.php --}}
<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>
            <a href="{{ route('reports.product_sales.export') }}?{{ http_build_query(request()->query()) }}" class="dropdown-item">
                <i class="ph ph-download-simple me-2"></i>
                {{ __('global.export') ?? 'Export to Excel' }}
            </a>
            <a href="{{ route('reports.product_sales') }}" class="dropdown-item">
                <i class="ph ph-arrow-counter-clockwise me-2"></i>
                {{ __('global.reset') ?? 'Reset Filters' }}
            </a>
        </x-basic.option>
    </x-basic.breadcrumb>

    <div class="content">

        {{-- ============ Filter Card ============ --}}
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-0 text-secondary fw-bold">
                    <i class="ph ph-funnel me-1"></i> Filters
                </h6>
            </div>
            <div class="card-body pt-2">
                <form id="filterForm" method="get" action="{{ route('reports.product_sales') }}" class="row g-3">

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Product</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                            <input type="text" name="product" class="form-control border-start-0"
                                value="{{ old('product', $product ?? '') }}" placeholder="Name or SKU">
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Branch</label>
                        <select name="branch" class="form-select">
                            <option value="">All Branches</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ (string) ($branch ?? '') === (string) $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ (string) ($category ?? '') === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Customer</label>
                        <select name="customer" class="form-select">
                            <option value="">All Customers</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ (string) ($customer ?? '') === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Warehouse</label>
                        <select name="warehouse" class="form-select">
                            <option value="">All Warehouses</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->id }}" {{ (string) ($warehouse ?? '') === (string) $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Start Date</label>
                        <input type="date" name="from" class="form-control" value="{{ old('from', $from ?? '') }}">
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">End Date</label>
                        <input type="date" name="to" class="form-control" value="{{ old('to', $to ?? '') }}">
                    </div>

                    <div class="col-md-4 col-lg-3 d-flex align-items-end gap-2">
                        <button class="btn btn-primary flex-fill" type="submit">
                            <i class="ph ph-magnifying-glass me-1"></i> Search
                        </button>
                        <a href="{{ route('reports.product_sales') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="ph ph-x"></i>
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- ============ Summary Cards ============ --}}
        @php
            $__qty = 0; $__cost = 0; $__price = 0; $__profit = 0;
            foreach ($rows as $__r) {
                $__qty += isset($__r->sale_qty) ? (float) $__r->sale_qty : 0;
                $__cost += isset($__r->total_cost) ? (float) $__r->total_cost : 0;
                $__price += isset($__r->total_price) ? (float) $__r->total_price : 0;
                $__profit += isset($__r->gross_profit) ? (float) $__r->gross_profit : 0;
            }
            $__margin = $__price > 0 ? ($__profit / $__price) * 100 : 0;
        @endphp
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="ph ph-package fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Units Sold</div>
                            <div class="fw-bold fs-5">{{ number_format($__qty, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="ph ph-coins fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Cost</div>
                            <div class="fw-bold fs-5">{{ number_format($__cost, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="ph ph-currency-dollar fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Revenue</div>
                            <div class="fw-bold fs-5">{{ number_format($__price, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle {{ $__profit < 0 ? 'bg-danger' : 'bg-success' }} bg-opacity-10 {{ $__profit < 0 ? 'text-danger' : 'text-success' }} d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="ph ph-chart-line-up fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Gross Profit</div>
                            <div class="fw-bold fs-5 {{ $__profit < 0 ? 'text-danger' : '' }}">
                                {{ number_format($__profit, 2) }}
                                <span class="fs-6 fw-normal text-muted">({{ number_format($__margin, 1) }}%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ Report Table ============ --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ph ph-chart-bar me-1 text-primary"></i>
                    Product Sales Report
                </h5>

                <form id="exportXlsxForm" method="get" action="{{ route('reports.product_sales.export') }}" class="d-inline">
                    <input type="hidden" name="product">
                    <input type="hidden" name="branch">
                    <input type="hidden" name="category">
                    <input type="hidden" name="customer">
                    <input type="hidden" name="warehouse">
                    <input type="hidden" name="from">
                    <input type="hidden" name="to">

                    <button type="submit" class="btn btn-outline-success btn-sm" title="Export Excel">
                        <i class="ph ph-download-simple me-1"></i> Export
                    </button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary text-center">
                            <tr>
                                <th style="width:40px;">No</th>
                                <th class="text-start" style="min-width:220px;">Product</th>
                                <th style="width:130px;">Qty</th>
                                <th style="width:110px;">Unit Price</th>
                                <th style="width:110px;">Discount</th>
                                <th style="width:120px;">Total Cost</th>
                                <th style="width:120px;">Total Price</th>
                                <th style="width:120px;">Gross Profit</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $currentCategory = null;
                                $rowNo = 0;

                                $catQty = $catDiscount = $catCost = $catPrice = $catProfit = 0;
                                $grandQty = $grandDiscount = $grandCost = $grandPrice = $grandProfit = 0;
                            @endphp

                            @forelse($rows as $r)
                                @php
                                    $category = $r->category_name ?? 'Uncategorized';
                                    $productName = ($r->product_name ?? '');
                                    $productCode = $r->product_code ?? '-';
                                    $saleQty = isset($r->sale_qty) ? (float) $r->sale_qty : 0;
                                    $saleUnit = $r->sale_unit_name ?? '';
                                    $unitPrice = isset($r->unit_price) ? (float) $r->unit_price : 0.0;
                                    $discount = isset($r->discount) ? (float) $r->discount : 0.0;
                                    $totalCost = isset($r->total_cost) ? (float) $r->total_cost : 0.0;
                                    $totalPrice = isset($r->total_price) ? (float) $r->total_price : ($saleQty * $unitPrice);
                                    $profit = isset($r->gross_profit) ? (float) $r->gross_profit : ($totalPrice - $totalCost);

                                    $qtyLabel = rtrim(rtrim(number_format($saleQty, 8, '.', ''), '0'), '.');
                                    $qtyWithUnit = trim($qtyLabel . ' ' . $saleUnit);

                                    $categoryChanged = ($currentCategory === null || $currentCategory !== $category);
                                @endphp

                                @if($categoryChanged)
                                    @if($currentCategory !== null)
                                        <tr class="table-light fw-semibold">
                                            <td></td>
                                            <td colspan="2" class="text-end text-primary">
                                                {{ $currentCategory }} subtotal
                                            </td>
                                            <td class="text-end">{{ number_format($catQty, 0) }}</td>
                                            <td class="text-end">{{ number_format($catDiscount, 2) }}</td>
                                            <td class="text-end">{{ number_format($catCost, 2) }}</td>
                                            <td class="text-end">{{ number_format($catPrice, 2) }}</td>
                                            <td class="text-end {{ $catProfit < 0 ? 'text-danger' : '' }}">{{ number_format($catProfit, 2) }}</td>
                                        </tr>
                                        @php $catQty = $catDiscount = $catCost = $catPrice = $catProfit = 0; @endphp
                                    @endif

                                    <tr class="bg-light">
                                        <td colspan="8" class="fw-bold text-secondary">
                                            <i class="ph ph-folder-open me-1"></i> {{ $category }}
                                        </td>
                                    </tr>

                                    @php $currentCategory = $category; @endphp
                                @endif

                                @php $rowNo++; @endphp
                                <tr>
                                    <td class="text-center text-muted">{{ $rowNo }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $productName }}</div>
                                        <div class="small text-muted">SKU: {{ $productCode }}</div>
                                    </td>
                                    <td class="text-center">{{ $qtyWithUnit }}</td>
                                    <td class="text-end">{{ number_format($unitPrice, 2) }}</td>
                                    <td class="text-end">{{ number_format($discount, 2) }}</td>
                                    <td class="text-end">{{ number_format($totalCost, 2) }}</td>
                                    <td class="text-end">{{ number_format($totalPrice, 2) }}</td>
                                    <td class="text-end">
                                        <span class="badge {{ $profit < 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                            {{ number_format($profit, 2) }}
                                        </span>
                                    </td>
                                </tr>

                                @php
                                    $catQty += $saleQty;
                                    $catDiscount += $discount;
                                    $catCost += $totalCost;
                                    $catPrice += $totalPrice;
                                    $catProfit += $profit;
                                    $grandQty += $saleQty;
                                    $grandDiscount += $discount;
                                    $grandCost += $totalCost;
                                    $grandPrice += $totalPrice;
                                    $grandProfit += $profit;
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="ph ph-tray fs-1 d-block mb-2"></i>
                                        No records found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse

                            {{-- final category subtotal --}}
                            @if($currentCategory !== null)
                                <tr class="table-light fw-semibold">
                                    <td></td>
                                    <td colspan="2" class="text-end text-primary">
                                        {{ $currentCategory }} subtotal
                                    </td>
                                    <td class="text-end">{{ number_format($catQty, 0) }}</td>
                                    <td class="text-end">{{ number_format($catDiscount, 2) }}</td>
                                    <td class="text-end">{{ number_format($catCost, 2) }}</td>
                                    <td class="text-end">{{ number_format($catPrice, 2) }}</td>
                                    <td class="text-end {{ $catProfit < 0 ? 'text-danger' : '' }}">{{ number_format($catProfit, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>

                        <tfoot class="table-dark">
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">Grand Total</td>
                                <td class="text-end">{{ number_format($grandQty, 0) }}</td>
                                <td class="text-end">{{ number_format($grandDiscount, 2) }}</td>
                                <td class="text-end">{{ number_format($grandCost, 2) }}</td>
                                <td class="text-end">{{ number_format($grandPrice, 2) }}</td>
                                <td class="text-end {{ $grandProfit < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($grandProfit, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .table thead th {
                vertical-align: middle;
                font-weight: 600;
                letter-spacing: .02em;
            }
            .table-primary {
                background: #2b84c0;
                color: #fff;
            }
            .table-hover tbody tr:hover {
                background-color: rgba(43, 132, 192, .05);
            }
            .card {
                border-radius: 12px;
            }
            .badge {
                font-size: .85rem;
                font-weight: 600;
                padding: .4em .7em;
            }
        </style>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const FILTER_FORM_ID = 'filterForm';
            const EXPORT_FORM_ID = 'exportXlsxForm';

            const filterForm = document.getElementById(FILTER_FORM_ID);
            const exportForm = document.getElementById(EXPORT_FORM_ID);

            if (!filterForm || !exportForm) {
                console.warn('Export script: filterForm or exportForm not found.');
                return;
            }

            const names = ['product', 'branch', 'category', 'customer', 'warehouse', 'from', 'to'];

            function copyValues() {
                names.forEach(name => {
                    const src = filterForm.querySelector(`[name="${name}"]`);
                    const dst = exportForm.querySelector(`[name="${name}"]`);
                    if (dst) {
                        dst.value = src ? src.value : '';
                    }
                });
            }

            exportForm.addEventListener('submit', copyValues);
        });
    </script>
</x-app-layout>