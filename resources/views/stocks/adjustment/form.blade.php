<x-app-layout>
    <style>
        /* Custom UI Variables & Refinements */
        :root {
            --primary-soft: #f0f4ff;
            --success-soft: #ecfdf5;
            --danger-soft: #fef2f2;
            --slate-100: #f1f5f9;
            --slate-500: #64748b;
        }

        .content { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .card { border-radius: 12px; border: none; }
        .form-label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; }
        
        /* Table Styling */
        .table thead th { 
            background-color: #f8fafc; 
            text-transform: uppercase; 
            font-size: 0.7rem; 
            letter-spacing: 0.05em;
            color: var(--slate-500);
            padding: 12px 15px;
            border-top: none;
        }
        .table tbody td { padding: 12px 15px; vertical-align: middle; }
        
        /* Quick Add Styling */
        .quick-add-container {
            background: var(--primary-soft);
            padding: 24px;
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
            transition: all 0.3s ease;
        }
        .quick-add-container:focus-within { border-color: #6366f1; background: #fff; }
        
        #quickAdd {
            height: 50px;
            border-radius: 10px;
            padding-left: 48px;
            font-size: 1rem;
            border: 1px solid #e2e8f0;
        }
        .search-icon-overlay {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.25rem;
            color: #6366f1;
            z-index: 10;
        }

        /* Logic Highlights */
        .new-qoh-field { background-color: #f8fafc !important; font-weight: 700; color: #1e293b; border: 1px solid #e2e8f0; }
        .btn-remove-row { color: #94a3b8; border: 1px solid #e2e8f0; border-radius: 8px; transition: all 0.2s; }
        .btn-remove-row:hover { color: #ef4444; background: var(--danger-soft); border-color: #fee2e2; }
        
        .list-group-item-action { border: none; padding: 12px 20px; border-bottom: 1px solid #f1f5f9; }
        .list-group-item-action:hover { background-color: var(--primary-soft); color: #6366f1; }
    </style>

    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">Add Adjustment</h2>
                    <p class="text-muted mb-0 small">Correct inventory stock levels</p>
                </div>
            </div>
        </x-slot>
        
        <div class="header-actions me-2">
            <a href="{{ route('stock.manage.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content mt-3">
        <x-basic.form action="{{ route('stock.manage.save', $form?->id) }}" novalidate enctype="multipart/form-data">
            
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <x-basic.form.text label="{{ __('global.date') }}" name="date" type="datetime-local" 
                                value="{{ $form?->date ? $form->date->timezone('Asia/Phnom_Penh')->format('Y-m-d\TH:i') : now('Asia/Phnom_Penh')->format('Y-m-d\TH:i') }}"
                                :required="true" />
                        </div>
                        <div class="col-md-3">
                            <x-basic.form.text label="{{ __('global.reference_no') }}" name="reference_no"
                                value="{{ $form?->reference_no }}" placeholder="ADJ-100234" />
                        </div>
                        <div class="col-md-3">
                            <x-basic.form.select label="{{ __('global.branch') }}" name="branch_id" :options="$branches"
                                :required="true" :selected="$form?->branch_id" />
                        </div>
                        <div class="col-md-3">
                            <x-basic.form.select label="{{ __('global.warehouse') }}" name="warehouse_id"
                                :options="$warehouses" :required="true" :selected="$form?->warehouse_id" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">Adjustment Details</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    
                    <div class="quick-add-container mb-4 position-relative mt-3">
                        <i class="ph-magnifying-glass search-icon-overlay"></i>
                        <input id="quickAdd" type="text" class="form-control shadow-none border-0" 
                               placeholder="Search product by name, code or scan barcode...">
                        <div id="quickAddSuggestions" class="list-group position-absolute w-100 shadow-lg border-0" 
                             style="z-index:1060; display:none; top: 100%; border-radius: 0 0 10px 10px; overflow: hidden;"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th width="30%">{{ __('global.product_name') }}</th>
                                    <th width="10%">{{ __('global.qoh') }}</th>
                                    <th width="15%">{{ __('global.type') }}</th>
                                    <th width="12%">{{ __('global.quantity') }}</th>
                                    <th width="15%">{{ __('global.unit') }}</th>
                                    <th width="13%">{{ __('global.new_qoh') }}</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="productRows">
                                @if($form && $form->products->count() > 0)
                                    @foreach($form->products as $index => $product)
                                        @endforeach
                                @else
                                    <tr id="emptyRow">
                                        <td colspan="7" class="text-center py-5">
                                            <div class="opacity-25 mb-2"><i class="ph-package" style="font-size: 4rem;"></i></div>
                                            <h6 class="text-muted fw-normal">Scan or search for products to adjust stock</h6>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <label class="form-label">Adjustment Reason / Notes</label>
                            <textarea name="note" class="form-control bg-light border-0" rows="4"
                                placeholder="Describe why this adjustment is being made...">{{ $form?->note }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <label class="form-label">{{ __('global.attachment') }}</label>
                                <input type="file" class="form-control shadow-none" name="document" accept=".pdf,.doc,.docx,.jpg,.png" />
                                <small class="text-muted mt-2 d-block">Upload proof of damage or count sheets.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body p-3 text-end">
                        <a href="{{ route('stock.manage.index') }}" class="btn btn-light px-4 me-2">
                            {{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-3 fw-bold shadow-sm">
                            <i class="ph ph-floppy-disk me-2"></i> Save Adjustment
                        </button>
                    </div>
                </div>
            </div>
        </x-basic.form>
    </div>

    <script>
    $(function () {
        const URL_PRODUCTS       = "{{ route('stock.manage.ajaxProducts') }}";
        const URL_PRODUCT_UNITS  = "{{ route('stock.manage.ajaxProductUnits') }}";
        let PRODUCTS = [];

        // 1. Initial Product Load
        function loadProducts(callback) {
            $.getJSON(URL_PRODUCTS, { q: '', limit: 1000 }, function (list) {
                PRODUCTS = Array.isArray(list) ? list : [];
                if (callback) callback();
            });
        }

        function productOptions(selectedId) {
            let html = '<option value="">Select Product</option>';
            $.each(PRODUCTS, function (_, p) {
                let sel = (String(p.id) === String(selectedId)) ? 'selected' : '';
                html += `<option value="${p.id}" ${sel}>${p.name} (${p.code})</option>`;
            });
            return html;
        }

        // 2. Calculation Engine with Visual UI states
        function recalcRow($tr) {
            const qoh  = parseFloat($tr.find('.qoh-input').val()) || 0;
            const qty  = parseFloat($tr.find('.quantity-input').val()) || 0;
            const $typeSelect = $tr.find('.type-input');
            const type = $typeSelect.val();
            let newQ = qoh;

            if (type === 'add') {
                newQ = qoh + qty;
                $typeSelect.css({'border-color': '#10b981', 'background-color': '#ecfdf5'});
            } else if (type === 'subtract') {
                newQ = Math.max(0, qoh - qty);
                $typeSelect.css({'border-color': '#ef4444', 'background-color': '#fef2f2'});
            } else {
                $typeSelect.css({'border-color': '', 'background-color': ''});
            }

            $tr.find('.new-qoh').val(newQ.toFixed(2));
        }

        // 3. Row Management
        function addRow(pid = null) {
            $('#emptyRow').remove();
            const idx = $('#productRows tr').length;
            const rowHtml = `
                <tr>
                    <td>
                        <select name="products[${idx}][product_id]" class="form-select product-select fw-bold border-0 bg-light" required>
                            ${productOptions(pid)}
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${idx}][qoh]" class="form-control qoh-input bg-transparent border-0 text-muted" readonly>
                    </td>
                    <td>
                        <select name="products[${idx}][type]" class="form-select type-input fw-semibold" required>
                            <option value="">-- Type --</option>
                            <option value="add">Addition (+)</option>
                            <option value="subtract">Subtraction (-)</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${idx}][quantity]" class="form-control quantity-input fw-bold shadow-sm" min="0.01" step="any" required>
                    </td>
                    <td>
                        <select name="products[${idx}][product_unit_id]" class="form-select unit-select" required>
                            <option value="">Unit</option>
                        </select>
                        <input type="hidden" name="products[${idx}][product_unit_code]" class="unit-code-input">
                    </td>
                    <td>
                        <input type="text" name="products[${idx}][new_qoh]" class="form-control new-qoh new-qoh-field" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-remove-row"><i class="ph-trash"></i></button>
                    </td>
                </tr>`;
            
            const $row = $(rowHtml).appendTo('#productRows');
            if (pid) $row.find('.product-select').trigger('change');
        }

        // 4. Events
        $(document).on('change', '.product-select', function () {
            const pid = $(this).val();
            const $tr = $(this).closest('tr');
            const product = PRODUCTS.find(p => String(p.id) === String(pid));
            
            if (product) {
                $tr.find('.qoh-input').val(product.quantity || 0);
                $.getJSON(URL_PRODUCT_UNITS, { product_id: pid }, function (units) {
                    let h = '<option value="">Unit</option>';
                    $.each(units, function (_, u) {
                        h += `<option value="${u.id}" data-code="${u.code}">${u.name}</option>`;
                    });
                    $tr.find('.unit-select').html(h);
                });
                recalcRow($tr);
            }
        });

        $(document).on('input change', '.quantity-input, .type-input, .qoh-input', function() {
            recalcRow($(this).closest('tr'));
        });

        $(document).on('click', '.btn-remove-row', function() { $(this).closest('tr').remove(); });

        // 5. Quick Add Autocomplete
        let typingTimer;
        $('#quickAdd').on('input', function () {
            const term = $(this).val();
            clearTimeout(typingTimer);
            if (!term) return $('#quickAddSuggestions').hide();

            typingTimer = setTimeout(() => {
                $.getJSON(URL_PRODUCTS, { q: term, limit: 8 }, function (list) {
                    let html = '';
                    $.each(list, function (_, p) {
                        html += `<button type="button" class="list-group-item list-group-item-action" data-id="${p.id}">
                                    <div class="d-flex justify-content-between">
                                        <strong>${p.name}</strong>
                                        <span class="text-muted small">${p.code}</span>
                                    </div>
                                 </button>`;
                    });
                    $('#quickAddSuggestions').html(html).show();
                });
            }, 200);
        });

        $(document).on('click', '#quickAddSuggestions .list-group-item', function() {
            addRow($(this).data('id'));
            $('#quickAdd').val('').focus();
            $('#quickAddSuggestions').hide();
        });

        // Initialize
        loadProducts();
    });
    </script>
</x-app-layout>