{{-- resources/views/reports/product_sales_report.blade.php --}}
<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>
            @can('purchases.add')
                <a href="{{ route('purchases.add') }}" class="dropdown-item">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.add_new') }}
                </a>
            @endcan
        </x-basic.option>
    </x-basic.breadcrumb>

    <!-- Content area -->
    <div class="content">

        {{-- Filter form --}}
        <div class="card mb-3">
            <div class="card-body">

                <form id="filterForm" method="get" action="{{ route('reports.product_sales') }}" class="row g-2 mb-3">

                    <div class="col-md-4">
                        <label class="form-label">Product</label>
                        <input type="text" name="product" class="form-control"
                            value="{{ old('product', $product ?? '') }}" placeholder="Name or code">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Branch</label>
                        <select name="branch" class="form-select">
                            <option value="">Select Branch</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ (string) ($branch ?? '') === (string) $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ (string) ($category ?? '') === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer" class="form-select">
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ (string) ($customer ?? '') === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Warehouse</label>
                        <select name="warehouse" class="form-select">
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->id }}" {{ (string) ($warehouse ?? '') === (string) $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="from" class="form-control" value="{{ old('from', $from ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="to" class="form-control" value="{{ old('to', $to ?? '') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" type="submit">Search</button>
                    </div>





                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('reports.product_sales') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>


                </form>


            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Product Sales Report</h5>
                <form id="exportXlsxForm" method="get" action="{{ route('reports.product_sales.export') }}"
                    class="d-inline " style="float: right;"> 

                    <input type="hidden" name="product">
                    <input type="hidden" name="branch">
                    <input type="hidden" name="category">
                    <input type="hidden" name="customer">
                    <input type="hidden" name="warehouse">
                    <input type="hidden" name="from">
                    <input type="hidden" name="to">

                    <button type="submit" class="btn btn-outline-success" title="Export Excel">
                        <i class="ph ph-download"></i>
                    </button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="table-primary text-center">
                            <tr>
                                <th style="width:40px;">No</th>
                                <th style="width:200px;">Warehouse » Category » Product</th>
                                <th style="width:140px;">Product Type</th>
                                <th style="width:140px;">Unit Quantity</th>
                                <th style="width:110px;">Unit Price</th>
                                <th style="width:110px;">Total Discount</th>
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
                                    $productName = ($r->product_name ?? '') . ' - ' . ($r->product_code ?? '-');
                                    $saleQty = isset($r->sale_qty) ? (float) $r->sale_qty : 0;
                                    $saleUnit = $r->sale_unit_name ?? '';
                                    $unitPrice = isset($r->unit_price) ? (float) $r->unit_price : 0.0;
                                    $discount = isset($r->discount) ? (float) $r->discount : 0.0;
                                    $totalCost = isset($r->total_cost) ? (float) $r->total_cost : 0.0;
                                    $totalPrice = isset($r->total_price) ? (float) $r->total_price : ($saleQty * $unitPrice);
                                    $profit = isset($r->gross_profit) ? (float) $r->gross_profit : ($totalPrice - $totalCost);

                                    $qtyLabel = rtrim(rtrim(number_format($saleQty, 8, '.', ''), '0'), '.');
                                    $qtyWithUnit = trim($qtyLabel . ' ' . $saleUnit);
                                    if (isset($r->converted_qty_to_base) && $r->converted_qty_to_base != $saleQty) {
                                        $converted = rtrim(rtrim(number_format($r->converted_qty_to_base, 8, '.', ''), '0'), '.');
                                        // $qtyWithUnit .= '<br><small class="text-muted">≈ ' . $converted . ' ' . ($r->base_unit_name ?? '') . '</small>';
                                    }

                                    $categoryChanged = ($currentCategory === null || $currentCategory !== $category);
                                @endphp

                                @if($categoryChanged)
                                    @if($currentCategory !== null)
                                        <tr class="table-secondary fw-bold">
                                            <td></td>
                                            <td colspan="3" class="text-end"> <span
                                                    class="text-primary">{{ $currentCategory }}</span> subtotal:</td>
                                            <td class="text-end">{{ number_format($catQty, 0) }}</td>
                                            <td class="text-end">{{ number_format($catDiscount, 2) }}</td>
                                            <td class="text-end">{{ number_format($catCost, 2) }}</td>
                                            <td class="text-end">{{ number_format($catPrice, 2) }}</td>
                                            <td class="text-end">{{ number_format($catProfit, 2) }}</td>
                                        </tr>
                                        @php $catQty = $catDiscount = $catCost = $catPrice = $catProfit = 0; @endphp
                                    @endif

                                    <tr class="bg-light">
                                        <td colspan="9" class="fw-bold">
                                            <i class="ph ph-folder-open"></i>&nbsp; {{ $category }}
                                        </td>
                                    </tr>

                                    @php $currentCategory = $category; @endphp
                                @endif

                                @php $rowNo++; @endphp
                                <tr>
                                    <td>{{ $rowNo }}</td>
                                    <td>{!! e($productName) !!}</td>
                                    <td>Standard</td>
                                    <td>{!! $qtyWithUnit !!}</td>
                                    <td class="text-end">{{ number_format($unitPrice, 2) }}</td>
                                    <td class="text-end">{{ number_format($discount, 2) }}</td>
                                    <td class="text-end">{{ number_format($totalCost, 2) }}</td>
                                    <td class="text-end">{{ number_format($totalPrice, 2) }}</td>
                                    <td class="text-end {!! $profit < 0 ? 'text-danger fw-bold' : '' !!}">
                                        {{ number_format($profit, 2) }}
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
                                    <td colspan="9" class="text-center">No records found.</td>
                                </tr>
                            @endforelse

                            {{-- final category subtotal --}}
                            @if($currentCategory !== null)
                                <tr class="table-secondary fw-bold">
                                    <td></td>
                                    <td colspan="3" class="text-end"> <span
                                            class="text-primary">{{ $currentCategory }}</span> subtotal:</td>
                                    <td class="text-end">{{ number_format($catQty, 0) }}</td>
                                    <td class="text-end">{{ number_format($catDiscount, 2) }}</td>
                                    <td class="text-end">{{ number_format($catCost, 2) }}</td>
                                    <td class="text-end">{{ number_format($catPrice, 2) }}</td>
                                    <td class="text-end">{{ number_format($catProfit, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>

                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end">Total</td>
                                <td class="text-end">{{ number_format($grandQty, 0) }}</td>
                                <td class="text-end">{{ number_format($grandDiscount, 2) }}</td>
                                <td class="text-end">{{ number_format($grandCost, 2) }}</td>
                                <td class="text-end">{{ number_format($grandPrice, 2) }}</td>
                                <td class="text-end">{{ number_format($grandProfit, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div> {{-- .table-responsive --}}
            </div> {{-- .card-body --}}
        </div> {{-- .card --}}
    </div> {{-- .content --}}

    {{-- modal kept as in your original --}}
    <div class="modal fade" id="purchasesModal" tabindex="-1" aria-labelledby="purchasesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchasesModalLabel">{{ __('global.purchases') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('global.close') }}"></button>
                </div>
                <div class="modal-body" id="purchasesModalBody">
                    <div class="text-center">{{ __('global.loading') }}...</div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .table thead th {
                vertical-align: middle;
            }

            .table-secondary {
                background: #f4f7fb !important;
            }

            .table-primary {
                background: #2b84c0;
                color: white;
            }
        </style>
    @endpush
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // IDs used in the view
            const FILTER_FORM_ID = 'filterForm';
            const EXPORT_FORM_ID = 'exportXlsxForm';
            const EXPORT_BTN_SELECTOR = '#exportXlsxForm button[type="submit"]';

            const filterForm = document.getElementById(FILTER_FORM_ID);
            const exportForm = document.getElementById(EXPORT_FORM_ID);
            const exportBtn = document.querySelector(EXPORT_BTN_SELECTOR);

            // safe no-op if neither form exists
            if (!filterForm && !exportForm) {
                console.warn('Export script: no filterForm or exportForm found.');
                return;
            }

            const names = ['product', 'branch', 'category', 'customer', 'warehouse', 'from', 'to'];

            // copy values from filterForm to exportForm inputs
            function copyValues() {
                if (!filterForm || !exportForm) return;
                names.forEach(name => {
                    const src = filterForm.querySelector(`[name="${name}"]`);
                    const dst = exportForm.querySelector(`[name="${name}"]`);
                    if (dst) {
                        dst.value = src ? src.value : '';
                    }
                });
            }

            // Preferred: intercept export form submit and copy values first
            if (exportForm && filterForm) {
                // attach to submit so it always runs (works for button or enter)
                exportForm.addEventListener('submit', function (e) {
                    copyValues();
                    // allow submit to continue
                });
                return;
            }

            // Fallback: if filterForm missing but export button exists, copy from inputs on click
            if (exportBtn && filterForm) {
                exportBtn.addEventListener('click', function () {
                    copyValues();
                    // form will submit naturally after click
                });
                return;
            }

            // If we get here, log helpful info for debugging
            console.warn('Export script: some elements missing. filterForm exists?', !!filterForm, 'exportForm exists?', !!exportForm);
        });
    </script>



</x-app-layout>