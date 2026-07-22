<x-app-layout>
    <style>
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
                    <h2 class="mb-0 fw-bold h4">{{ $title ?? __('add_adjustment') }}</h2>
                    <p class="text-muted mb-0 small">Correct inventory stock levels</p>
                </div>
            </div>
        </x-slot>

        <div class="header-actions me-2">
            <a href="{{ route('stocks.adjustment.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content mt-3">
        <x-basic.form action="{{ $action }}" novalidate enctype="multipart/form-data" class="ajax-form">

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <x-forms.input
                                label="{{ __('global.date') }}"
                                name="date"
                                type="datetime-local"
                                :value="old('date', $form?->date ? $form->date->timezone('Asia/Phnom_Penh')->format('Y-m-d\TH:i') : now('Asia/Phnom_Penh')->format('Y-m-d\TH:i'))"
                                required
                            />
                        </div>
                        <div class="col-md-3">
                            <x-forms.input
                                label="{{ __('global.reference_no') }}"
                                name="reference_no"
                                :value="old('reference_no', $form?->reference_no ?? '')"
                                placeholder="ADJ-100234"
                            />
                        </div>
                        <div class="col-md-3">
                            <x-forms.select
                                name="branch_id"
                                label="{{ __('global.branch') }}"
                                :options="$branches->pluck('name', 'id')->toArray()"
                                placeholder="Select Branch"
                                :value="old('branch_id', $form?->branch_id ?? '')"
                            />
                        </div>
                        <div class="col-md-3">
                            <x-forms.select
                                name="warehouse_id"
                                label="{{ __('global.warehouse') }}"
                                :options="$warehouses->pluck('name', 'id')->toArray()"
                                placeholder="Select Warehouse"
                                :value="old('warehouse_id', $form?->warehouse_id ?? '')"
                            />
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
                        <div id="quickAddSuggestions" class="list-group position-absolute  shadow-lg border-0 bg-white"
                             style="z-index:1060; display:none; top: 100%; border-radius: 0 0 10px 10px; overflow: hidden;"></div>
                    </div>

                    <div class="table-responsive pb-5 ">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th width="28%">{{ __('global.product_name') }}</th>
                                    <th width="10%">{{ __('global.qoh') }}</th>
                                    <th width="17%">{{ __('global.type') }}</th>
                                    <th width="12%">{{ __('global.quantity') }}</th>
                                    <th width="15%">{{ __('global.unit') }}</th>
                                    <th width="13%">{{ __('global.new_qoh') }}</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="productRows">
                                <tr id="emptyRow">
                                    <td colspan="7" class="text-center py-5">
                                        <div class="opacity-25 mb-2"><i class="ph-package" style="font-size: 4rem;"></i></div>
                                        <h6 class="text-muted fw-normal">Scan or search for products to adjust stock</h6>
                                    </td>
                                </tr>
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
                            <textarea name="note" class="form-control bg-light border-0" rows="7"
                                placeholder="Describe why this adjustment is being made...">{{ old('note', $form?->note ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <label class="form-label">{{ __('global.attachment') }}</label>
                                @php
                                    $hasImage  = !empty($form->attachment ?? null);
                                    $imageUrl  = $hasImage ? $form->attachment : '';
                                @endphp

                                <div class="d-flex justify-content-center w-100"> 
                                    <x-basic.uploader
                                        input-name="document"
                                        :url="old('document', $imageUrl)"
                                        :path="old('document', $form->attachment ?? '')"
                                        folder="stocks/adjustment"
                                        width="240px"
                                        height="130px"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body p-3 text-end">
                        <a href="{{ route('stocks.adjustment.index') }}" class="btn btn-light px-4 me-2">
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

    @php
        $existingItems = [];

        if ($form && $form->exists) {
            foreach ($form->items as $it) {
                $existingItems[] = [
                    'id'                => $it->id,
                    'product_id'        => $it->product_id,
                    'qoh'               => $it->qoh,
                    'type'              => $it->type,
                    'quantity'          => abs($it->quantity),
                    'product_unit_id'   => $it->product_unit_id,
                    'product_unit_code' => $it->product_unit_code,
                    'new_qoh'           => $it->new_qoh,
                ];
            }
        }
    @endphp
    @push('scripts')
        <script>
            window.EXISTING_ITEMS = @json($existingItems);
            $(function () {
                const URL_PRODUCTS      = "{{ route('stocks.adjustment.ajaxProducts') }}";
                const URL_PRODUCT_UNITS = "{{ route('stocks.adjustment.ajaxProductUnits') }}";
                let PRODUCTS = [];
                let rowCounter = 0;

                function loadProducts(callback) {
                    $.getJSON(URL_PRODUCTS, { q: '', limit: 1000 })
                        .done(function (list) {
                            PRODUCTS = Array.isArray(list) ? list : [];
                        })
                        .fail(function (xhr) {
                            console.error('ajaxProducts failed:', xhr.status, xhr.responseText);
                            PRODUCTS = [];
                        })
                        .always(function () {
                            if (callback) callback();
                        });
                }

                function productOptionsObject() {
                    const opts = {};
                    $.each(PRODUCTS, function (_, p) {
                        opts[p.id] = `${p.product_name} (${p.sku})`;
                    });
                    return opts;
                }

                function customProductSelectHtml(idx) {
                    const id = `product_select_${idx}`;
                    return `
                        <div class="form-field">
                            <input type="hidden" name="products[${idx}][product_id]" id="${id}-value" class="product-select-value" value="">
                            <div class="csel-wrap" id="csel-${id}">
                                <div class="csel-trigger placeholder" onclick="cselToggle('${id}')">
                                    <span>Select Product</span>
                                    <span class="csel-chevron">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="csel-dropdown">
                                    <div class="csel-search">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                        <input type="text" placeholder="Search..." oninput="cselFilter('${id}', this.value)">
                                    </div>
                                    <div class="csel-options">
                                        <div class="csel-empty">No options available</div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                }

                function cselSetSelectedSilently(id, value, label) {
                    const wrap = document.getElementById('csel-' + id);
                    if (!wrap) return;

                    document.getElementById(id + '-value').value = value;

                    const trigger = wrap.querySelector('.csel-trigger');
                    trigger.querySelector('span').textContent = label;
                    trigger.classList.remove('placeholder');

                    wrap.querySelectorAll('.csel-option').forEach(function (o) {
                        o.classList.toggle('selected', String(o.dataset.value) === String(value));
                    });
                }

                function recalcRow($tr) {
                    const qoh = parseFloat($tr.find('.qoh-input').val()) || 0;
                    const qty = parseFloat($tr.find('.quantity-input').val()) || 0;
                    const $typeSelect = $tr.find('.type-input');
                    const type = $typeSelect.val();
                    let newQ = qoh;

                    if (type === 'increase') {
                        newQ = qoh + qty;
                        $typeSelect.css({ 'border-color': '#10b981', 'background-color': '#ecfdf5' });
                    } else if (type === 'decrease') {
                        newQ = Math.max(0, qoh - qty);
                        $typeSelect.css({ 'border-color': '#ef4444', 'background-color': '#fef2f2' });
                    } else {
                        $typeSelect.css({ 'border-color': '', 'background-color': '' });
                    }

                    $tr.find('.new-qoh').val(newQ.toFixed(2));
                }

                // ajaxProductUnits returns { id, name, code, qty, is_base }
                function loadUnitsForRow($tr, productId, selectedUnitId, fallbackUnitCode) {
                    $.getJSON(URL_PRODUCT_UNITS, { product_id: productId })
                        .done(function (units) {
                            units = Array.isArray(units) ? units : [];
                            let h = '<option value="">Unit</option>';
                            let foundSelected = false;

                            $.each(units, function (_, u) {
                                const sel = selectedUnitId && String(u.id) === String(selectedUnitId) ? 'selected' : '';
                                if (sel) foundSelected = true;
                                h += `<option value="${u.id}" data-code="${u.code}" ${sel}>${u.name}${u.is_base ? ' (base)' : ''}</option>`;
                            });

                            if (selectedUnitId && !foundSelected) {
                                h += `<option value="${selectedUnitId}" data-code="${fallbackUnitCode || ''}" selected>${fallbackUnitCode || 'Unit #' + selectedUnitId}</option>`;
                            }

                            $tr.find('.unit-select').html(h);
                            $tr.find('.unit-code-input').val($tr.find('.unit-select option:selected').data('code') || '');
                        })
                        .fail(function (xhr) {
                            console.error('ajaxProductUnits failed:', xhr.status, xhr.responseText);

                            if (selectedUnitId) {
                                $tr.find('.unit-select').html(
                                    `<option value="${selectedUnitId}" data-code="${fallbackUnitCode || ''}" selected>${fallbackUnitCode || 'Unit #' + selectedUnitId}</option>`
                                );
                                $tr.find('.unit-code-input').val(fallbackUnitCode || '');
                            } else {
                                $tr.find('.unit-select').html('<option value="">Unit</option>');
                            }
                        });
                }

                function addRow(item = null) {
                    $('#emptyRow').remove();
                    const idx = rowCounter++;
                    const pid = item && item.product_id ? item.product_id : null;

                    const rowHtml = `
                        <tr>
                            <td>
                                <input type="hidden" name="products[${idx}][id]" class="item-id-input" value="${item ? (item.id || '') : ''}">
                                ${customProductSelectHtml(idx)}
                            </td>
                            <td>
                                <input type="number" name="products[${idx}][qoh]" class="form-control qoh-input bg-transparent border-0 text-muted" value="${item?.qoh ?? 0}" readonly>
                            </td>
                            <td>
                                <select name="products[${idx}][type]" class="form-select type-input fw-semibold" required>
                                    <option value="">-- Type --</option>
                                    <option value="increase" ${item?.type === 'increase' ? 'selected' : ''}>Addition (+)</option>
                                    <option value="decrease" ${item?.type === 'decrease' ? 'selected' : ''}>Subtraction (-)</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="products[${idx}][quantity]" class="form-control quantity-input fw-bold shadow-sm" min="0.01" step="any" value="${item?.quantity ?? ''}" required>
                            </td>
                            <td>
                                <select name="products[${idx}][product_unit_id]" class="form-select unit-select" required>
                                    <option value="">Unit</option>
                                </select>
                                <input type="hidden" name="products[${idx}][product_unit_code]" class="unit-code-input" value="${item?.product_unit_code ?? ''}">
                            </td>
                            <td>
                                <input type="text" name="products[${idx}][new_qoh]" class="form-control new-qoh new-qoh-field" value="${item?.new_qoh ?? ''}" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-remove-row"><i class="fa-regular fa-trash-can"></i></button>
                            </td>
                        </tr>`;

                    const $row = $(rowHtml).appendTo('#productRows');

                    const selectId = `product_select_${idx}`;
                    cselSetOptions(selectId, productOptionsObject());

                    if (pid) {
                        const product = PRODUCTS.find(p => String(p.id) === String(pid));
                        const label = product
                            ? `${product.product_name} (${product.sku})`
                            : `Product #${pid}`;

                        cselSetSelectedSilently(selectId, pid, label);

                        loadUnitsForRow($row, pid, item?.product_unit_id ?? null, item?.product_unit_code ?? null);

                        if (item?.qoh === undefined || item?.qoh === null) {
                            if (product) $row.find('.qoh-input').val(product.quantity || 0);
                        }

                        recalcRow($row);
                    }

                    return $row;
                }

                $(document).on('change', '.product-select-value', function () {
                    const pid = $(this).val();
                    const $tr = $(this).closest('tr');
                    const product = PRODUCTS.find(p => String(p.id) === String(pid));

                    if (product) {
                        $tr.find('.qoh-input').val(product.quantity || 0);
                        loadUnitsForRow($tr, pid, null);
                        recalcRow($tr);
                    } else {
                        $tr.find('.unit-select').html('<option value="">Unit</option>');
                        $tr.find('.unit-code-input').val('');
                    }
                });

                $(document).on('change', '.unit-select', function () {
                    const code = $(this).find('option:selected').data('code') || '';
                    $(this).closest('tr').find('.unit-code-input').val(code);
                });

                $(document).on('input change', '.quantity-input, .type-input, .qoh-input', function () {
                    recalcRow($(this).closest('tr'));
                });

                $(document).on('click', '.btn-remove-row', function () {
                    $(this).closest('tr').remove();
                    if ($('#productRows tr').length === 0) {
                        $('#productRows').html(`
                            <tr id="emptyRow">
                                <td colspan="7" class="text-center py-5">
                                    <div class="opacity-25 mb-2"><i class="ph-package" style="font-size: 4rem;"></i></div>
                                    <h6 class="text-muted fw-normal">Scan or search for products to adjust stock</h6>
                                </td>
                            </tr>`);
                    }
                });

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
                                                <strong>${p.product_name}</strong>
                                                <span class="text-muted small">${p.sku}</span>
                                            </div>
                                        </button>`;
                            });
                            $('#quickAddSuggestions').html(html).show();
                        });
                    }, 200);
                });

                $(document).on('click', '#quickAddSuggestions .list-group-item', function () {
                    addRow({ product_id: $(this).data('id') });
                    $('#quickAdd').val('').focus();
                    $('#quickAddSuggestions').hide();
                });

                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#quickAdd, #quickAddSuggestions').length) {
                        $('#quickAddSuggestions').hide();
                    }
                });

                loadProducts(function () {
                    if (Array.isArray(window.EXISTING_ITEMS) && window.EXISTING_ITEMS.length > 0) {
                        $('#emptyRow').remove();
                        window.EXISTING_ITEMS.forEach(item => addRow(item));
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>