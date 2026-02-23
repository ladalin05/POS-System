<x-app-layout>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --border-color: #e2e8f0;
        }

        .product-container {
            padding: 1.5rem;
            background-color: #f8fafc;
        }

        /* Card Styling */
        .custom-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            background: #fff;
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header-custom h5 {
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.6rem 0.85rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Table Styling */
        .table-custom-wrapper {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .table-custom thead {
            background-color: #f1f5f9;
        }

        .table-custom th {
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            border: none;
        }

        /* Promotion Box */
        .promo-box {
            background: #fef2f2;
            border: 1px dashed #f87171;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .section-divider {
            height: 1px;
            background: var(--border-color);
            margin: 2rem 0;
        }

        .btn-generate {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
        }

        .btn-generate:hover {
            background: #e2e8f0;
        }
    </style>

    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center px-2">
                <div>
                    <h2 class="mb-0 fw-bold">Create Product</h2>
                    <p class="text-muted mb-0 small">Create new product</p>
                </div>
            </div>
        </x-slot>
        <div class="header-actions">
            <a href="{{ route('products.products.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="product-container">
        <x-basic.form action="{{ route('products.products.save', $form?->id) }}" novalidate>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card custom-card">
                        <div class="card-header-custom">
                            <i class="ph ph-info text-primary fs-5"></i>
                            <h5>General Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Product Type *</label>
                                    <select id="product_type" name="product_type" class="form-select">
                                        <option value="Standard">Standard Product</option>
                                        <option value="Service">Service / Digital</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Product Code / Barcode *</label>
                                    <div class="input-group">
                                        <input type="text" id="product_code" name="product_code" class="form-control" placeholder="Scan or generate code">
                                        <button type="button" id="generateCode" class="btn btn-generate" title="Generate Random Code">
                                            <i class="ph ph-arrows-clockwise"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <x-basic.form.text label="Product Name *" name="name" placeholder="Enter product name" />
                                </div>
                                <div class="col-md-6">
                                    <x-basic.form.select label="Brand" name="brand" :options="$branch ?? []" />
                                </div>
                                <div class="col-md-6">
                                    <x-basic.form.select label="Category *" name="category_id" :options="$categories ?? []" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card">
                        <div class="card-header-custom">
                            <i class="ph ph-currency-dollar text-success fs-5"></i>
                            <h5>Pricing & Inventory</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <x-basic.form.text label="Cost Price" name="cost" placeholder="0.00" />
                                </div>
                                <div class="col-md-4">
                                    <x-basic.form.text label="Sale Price *" name="price" placeholder="0.00" />
                                </div>
                                <div class="col-md-4 standard-only">
                                    <x-basic.form.text label="Alert Quantity" name="alert_quantity" placeholder="10" />
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-light rounded-3 standard-only" id="standardExtras">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">Multi-Unit Pricing</h6>
                                    <button type="button" class="btn btn-sm btn-primary" id="addUnitRow">
                                        <i class="ph ph-plus"></i> Add Unit
                                    </button>
                                </div>
                                <div class="table-custom-wrapper">
                                    <table class="table table-custom mb-0">
                                        <thead>
                                            <tr>
                                                <th>Unit</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="unitRows">
                                            <tr>
                                                <td>
                                                    <select name="product_units[0][unit_id]" class="form-select form-select-sm">
                                                        <option value="">Select</option>
                                                        @foreach($units as $u)
                                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="product_units[0][qty]" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="product_units[0][price]" class="form-control form-control-sm">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger remove-unit-row">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card custom-card">
                        <div class="card-header-custom">
                            <i class="ph ph-image text-info fs-5"></i>
                            <h5>Media</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label">Featured Image</label>
                                <div class="border rounded-3 p-3 text-center bg-light">
                                    <i class="ph ph-cloud-arrow-up fs-1 text-muted"></i>
                                    <input type="file" class="form-control mt-2" name="image">
                                    <small class="text-muted d-block mt-1">PNG, JPG up to 2MB</small>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Gallery Images</label>
                                <input type="file" class="form-control" name="product_gallery_images" multiple>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card">
                        <div class="card-body p-4">
                            <div class="form-check form-switch d-flex justify-content-between align-items-center p-0">
                                <label class="fw-bold h6 mb-0" for="enablePromotion">Flash Sale / Promo</label>
                                <input class="form-check-input ms-0" type="checkbox" id="enablePromotion" name="promotion" value="1">
                            </div>
                            
                            <div id="promotionFields" class="promo-box mt-3" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label">Promo Price</label>
                                    <input type="text" name="promo_price" class="form-control border-danger-subtle" placeholder="0.00">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <x-basic.form.date label="Starts" name="start_date" />
                                    </div>
                                    <div class="col-6">
                                        <x-basic.form.date label="Ends" name="end_date" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-basic.form.textarea label="Detailed Description" name="product_details" rows="4" />
                                </div>
                                <div class="col-md-6">
                                    <x-basic.form.textarea label="Invoice Notes (Internal)" name="product_invoice_details" rows="4" />
                                </div>
                            </div>
                            
                            <div class="text-end mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-light px-4 me-2">Clear Form</button>
                                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                    <i class="ph ph-floppy-disk me-2"></i> Save Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-basic.form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('generateCode');
            const field = document.getElementById('product_code');

            btn.addEventListener('click', function () {
                // Example: 12-digit random number
                const randomCode = Math.floor(100000000000 + Math.random() * 900000000000);
                field.value = randomCode;
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Toggles
            const promo = document.getElementById('enablePromotion');
            const promoFields = document.getElementById('promotionFields');
            if (promo && promoFields) {
                promo.addEventListener('change', () => promoFields.style.display = promo.checked ? 'block' : 'none');
            }
            const cf = document.getElementById('enableCustomFields');
            const cfBox = document.getElementById('customFields');
            if (cf && cfBox) {
                cf.addEventListener('change', () => cfBox.style.display = cf.checked ? 'block' : 'none');
            }

            // Standard vs Service
            const typeSelect = document.getElementById('product_type');
            const standardOnly = document.querySelectorAll('.standard-only');
            const standardExtras = document.getElementById('standardExtras');
            const unitRows = document.getElementById('unitRows');
            const addUnitRowBtn = document.getElementById('addUnitRow');
            const supplierWrap = document.getElementById('supplierRows');
            const addSupplierBtn = document.getElementById('addSupplierRow');

            function isService(v) { return String(v || '').toLowerCase() === 'service'; }

            function toggleStandard() {
                const hide = isService(typeSelect.value);
                // hide/show classic standard-only fields
                standardOnly.forEach(el => el.classList.toggle('d-none', hide));
                // hide/show the grid + supplier
                if (standardExtras) standardExtras.style.display = hide ? 'none' : '';

                if (hide && standardExtras) {
                    // Clear values so they don't submit for Service
                    standardExtras.querySelectorAll('input, select').forEach(el => el.value = '');
                    resetToSingleRow(unitRows);
                    resetToSingleRow(supplierWrap, '.supplier-row');
                    reindexRows();
                }
            }

            function resetToSingleRow(container, rowSelector) {
                if (!container) return;
                const rows = rowSelector ? container.querySelectorAll(rowSelector) : container.children;
                [...rows].slice(1).forEach(r => r.remove());
                const first = rows[0];
                if (first) first.querySelectorAll('input, select').forEach(el => el.value = '');
            }

            function reindexRows() {
                // Units
                if (unitRows) {
                    [...unitRows.children].forEach((tr, i) => {
                        tr.querySelectorAll('select, input').forEach(el => {
                            if (el.name) el.name = el.name.replace(/product_units\[\d+\]/, `product_units[${i}]`);
                        });
                    });
                }
                // Suppliers
                if (supplierWrap) {
                    const sRows = supplierWrap.querySelectorAll('.supplier-row');
                    sRows.forEach((row, i) => {
                        row.querySelectorAll('select, input').forEach(el => {
                            if (el.name) el.name = el.name.replace(/suppliers\[\d+\]/, `suppliers[${i}]`);
                        });
                    });
                }
            }

            // Clone helpers
            function cloneRow(container, rowSelector) {
                const last = rowSelector ? container.querySelector(`${rowSelector}:last-of-type`) : container.lastElementChild;
                const clone = last.cloneNode(true);
                clone.querySelectorAll('input, select').forEach(el => el.value = '');
                // avoid duplicate IDs if any appear later
                clone.querySelectorAll('[id]').forEach(el => el.removeAttribute('id'));
                container.appendChild(clone);
                reindexRows();
            }

            // Events for add/remove
            if (addUnitRowBtn && unitRows) addUnitRowBtn.addEventListener('click', () => cloneRow(unitRows));
            if (addSupplierBtn && supplierWrap) addSupplierBtn.addEventListener('click', () => cloneRow(supplierWrap, '.supplier-row'));

            if (unitRows) unitRows.addEventListener('click', (e) => {
                if (e.target.closest('.remove-unit-row')) {
                    const rows = unitRows.querySelectorAll('tr');
                    if (rows.length > 1) e.target.closest('tr').remove();
                    else rows[0].querySelectorAll('input, select').forEach(el => el.value = '');
                    reindexRows();
                }
            });

            if (supplierWrap) supplierWrap.addEventListener('click', (e) => {
                if (e.target.closest('.remove-supplier-row')) {
                    const rows = supplierWrap.querySelectorAll('.supplier-row');
                    if (rows.length > 1) e.target.closest('.supplier-row').remove();
                    else rows[0].querySelectorAll('input, select').forEach(el => el.value = '');
                    reindexRows();
                }
            });

            // Initial states
            toggleStandard();
            if (cf && cf.checked) cfBox.style.display = 'block';
            if (promo && promo.checked) promoFields.style.display = 'block';

            // Listen on change
            typeSelect.addEventListener('change', toggleStandard);
        });
    </script>
</x-app-layout>