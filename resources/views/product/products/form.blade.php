<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-body: #f8fafc;
            --card-shadow: 0 10px 15px -3px rgba(0,0,0,.05), 0 4px 6px -2px rgba(0,0,0,.02);
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }
        body { background: var(--bg-body); font-family: 'Inter', sans-serif; color: var(--text-main); }

        .custom-card { background:#fff; border:1px solid var(--border-color); border-radius:12px; box-shadow:var(--card-shadow); padding:2rem; margin-bottom:2rem; }
        .form-section-title { font-size:1.1rem; font-weight:700; color:var(--text-main); margin-bottom:1.5rem; display:flex; align-items:center; gap:12px; padding-bottom:.75rem; border-bottom:1px solid var(--border-color); }
        .form-section-title i { background:rgba(79,70,229,.1); color:var(--primary); padding:10px; border-radius:8px; font-size:1rem; }
        .form-label { font-weight:600; font-size:.85rem; color:#475569; margin-bottom:.5rem; display:block; }
        .form-control { border:1px solid #cbd5e1; padding:.65rem 1rem; border-radius:8px; font-size:.95rem; transition:all .2s; width:100%; box-sizing:border-box; }
        .form-control:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(79,70,229,.1); outline:none; }
        .btn-primary { background-color:var(--primary); border-color:var(--primary); color:#fff; }
        .btn-primary:hover { background-color:var(--primary-hover); border-color:var(--primary-hover); }
        .btn-generate { background:#f1f5f9; border:1px solid #cbd5e1; color:var(--primary); font-weight:600; font-size:.8rem; }

        .upload-box { width:120px; border:2px dashed #cbd5e1; border-radius:12px; padding:20px 10px; text-align:center; cursor:pointer; transition:all .3s ease; flex-shrink:0; }
        .upload-box:hover { border-color:var(--primary); background:#f0f7ff; }
        .upload-box i { font-size:2rem; color:var(--primary); margin-bottom:.5rem; display:block; }
        .preview-card { position:relative; width:120px; height:120px; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,.1); animation:fadeIn .4s ease; }
        @keyframes fadeIn { from{opacity:0;transform:scale(.9)} to{opacity:1;transform:scale(1)} }
        .preview-card img { width:100%; height:100%; object-fit:cover; }
        .delete-btn { position:absolute; top:5px; right:5px; background:rgba(239,68,68,.9); color:white; border:none; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:14px; transition:transform .2s; }
        .delete-btn:hover { transform:scale(1.1); background:#dc2626; }

        .product-type-selector { display:flex; gap:20px; padding:15px; background:#f1f5f9; border-radius:10px; margin-bottom:1.5rem; }

        /* Variant values checkbox list */
        #variant-values-list .variant-row { display:flex; align-items:center; gap:10px; padding:6px 10px; border-radius:6px; cursor:pointer; transition:background .15s; }
        #variant-values-list .variant-row:hover { background:#f1f5f9; }
        #variant-values-list .variant-row input[type=checkbox] { width:16px; height:16px; accent-color:var(--primary); cursor:pointer; flex-shrink:0; }
        #variant-values-list .variant-row label { cursor:pointer; margin:0; font-size:.9rem; }
    </style>

    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">Create New Product</h2>
                    <p class="text-muted mb-0 small">Create new Product</p>
                </div>
            </div>
        </x-slot>
        <div class="header-actions">
            <a href="{{ route('products.products.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i> {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content py-4">
        <div class="container-fluid">
            <form action="{{ route('products.products.add') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ===================== PRODUCT INFORMATION ===================== --}}
                <div class="custom-card">
                    <div class="form-section-title"><i class="fa-solid fa-box"></i> Product Information</div>
                    <div class="row g-4">

                        <div class="col-md-4">
                            <x-forms.select
                                name="warehouse_id"
                                label="Warehouse"
                                :options="getWarehouse()"
                                placeholder="Select Warehouse"
                                required
                            />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="Product URL Slug" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="sku" id="sku-input" class="form-control" placeholder="SKU-12345" required>
                                <button type="button" class="btn btn-generate" onclick="generateSKU()"><i class="fa-solid fa-rotate"></i></button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <x-forms.select
                                name="selling_type"
                                label="Selling Type"
                                :options="['Retail' => 'Retail', 'Wholesale' => 'Wholesale']"
                                placeholder="Select Selling Type"
                                required
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.select
                                name="category_id"
                                id="category_id"
                                label="Category"
                                :options="getCategory()"
                                placeholder="Select Category"
                                required
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.select
                                name="sub_category_id"
                                id="sub_category_id"
                                label="Sub Category"
                                :options="[]"
                                placeholder="Select Sub Category"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.select
                                name="brand_id"
                                label="Brand"
                                :options="getBrands()"
                                placeholder="Select Brand"
                                required
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.select
                                name="unit_id"
                                label="Unit"
                                :options="getUnit()"
                                placeholder="Select Unit (kg, pcs, box)"
                                required
                            />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Barcode <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="barcode" id="barcode-input" class="form-control" placeholder="Barcode" required>
                                <button type="button" class="btn btn-generate" onclick="generateBarcode()"><i class="fa-solid fa-barcode"></i></button>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Brief details about the product..."></textarea>
                        </div>

                    </div>
                </div>

                {{-- ===================== PRICING & INVENTORY ===================== --}}
                <div class="custom-card">
                    <div class="form-section-title"><i class="fa-solid fa-tags"></i> Pricing &amp; Inventory</div>

                    <div class="product-type-selector">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_type" id="typeSingle" value="Single" checked onclick="toggleProductType()">
                            <label class="form-check-label fw-bold" for="typeSingle">Single Product</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_type" id="typeVariable" value="Variable" onclick="toggleProductType()">
                            <label class="form-check-label fw-bold" for="typeVariable">Variable Product (Variants)</label>
                        </div>
                    </div>

                    {{-- ── Single Product ── --}}
                    <div id="single-product-section">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" class="form-control" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="price" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.select
                                    name="tax_type"
                                    label="Tax Type"
                                    :options="['Exclusive' => 'Exclusive', 'Inclusive' => 'Inclusive']"
                                    placeholder="Select Tax Type"
                                />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tax (%)</label>
                                <input type="number" name="tax_value" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <x-forms.select
                                    name="discount_type"
                                    label="Discount Type"
                                    :options="['Percentage' => 'Percentage', 'Fixed' => 'Fixed']"
                                    placeholder="Select Discount Type"
                                />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount Value</label>
                                <input type="number" name="discount_value" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Low Stock Alert</label>
                                <input type="number" name="alert_quantity" class="form-control" min="0">
                            </div>
                        </div>
                    </div>

                    {{-- ── Variable Product ── --}}
                    <div id="variable-product-section" style="display:none;">
                        <div class="row g-3">

                            {{-- Variant Attribute — single select --}}
                            <div class="col-md-6">
                                <x-forms.select
                                    name="variant_attribute"
                                    id="variant_attribute"
                                    label="Variant Attribute"
                                    :options="['Color' => 'Color', 'Size' => 'Size', 'Material' => 'Material', 'Weight' => 'Weight']"
                                    placeholder="Select attribute"
                                />
                            </div>

                            {{-- Variant Values — plain checkbox list, shown after attribute picked --}}
                            <div class="col-md-6" id="variant-values-wrap" style="display:none;">
                                <label class="form-label">Variant Values</label>
                                <div id="variant-values-list"
                                     style="border:1px solid #cbd5e1; border-radius:8px; max-height:220px; overflow-y:auto; padding:6px;">
                                    {{-- JS populates checkboxes here --}}
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="button" class="btn btn-primary" onclick="generateVariants()">
                                    <i class="fa-solid fa-layer-group me-2"></i> Generate Variant Table
                                </button>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="table-responsive">
                                    <table class="table table-hover border">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Variant</th>
                                                <th>SKU</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="variant-table-body"></tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ===================== PRODUCT GALLERY ===================== --}}
                <div class="custom-card">
                    <div class="form-section-title"><i class="fa-solid fa-images"></i> Product Gallery</div>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <div class="upload-box" id="uploadBox">
                            <i class="fa-solid fa-circle-plus"></i>
                            <p class="mb-0 fw-bold small">Add Image</p>
                            <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="d-none">
                        </div>
                        <div class="d-flex flex-wrap gap-3 align-items-center" id="imagePreview"></div>
                    </div>
                </div>

                {{-- ===================== CUSTOM FIELDS & DATES ===================== --}}
                <div class="custom-card">
                    <div class="form-section-title"><i class="fa-solid fa-circle-info"></i> Custom Fields &amp; Dates</div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <x-forms.select
                                name="warranty_id"
                                label="Warranty"
                                :options="[]"
                                placeholder="Select Warranty"
                            />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Manufacturer</label>
                            <input type="text" name="manufacturer" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">MFG Date</label>
                            <input type="date" name="mfg_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="exp_date" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- ===================== ACTIONS ===================== --}}
                <div class="custom-card justify-content-end d-flex mb-0">
                    <button type="button" class="btn btn-light me-3 px-5 border">Cancel</button>
                    <button type="submit" class="btn btn-success px-5 fw-bold">
                        <i class="fa-solid fa-check me-2"></i> Save Product
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
    const cselWrapperId   = n => `csel-${n}`;
    const cselHiddenId    = n => `${n}-value`;
    const cmultiWrapperId = n => `cmulti-${n}`;
    const cmultiOptId     = n => `cmulti-${n}-options`;
    const cmultiHiddenId  = n => `${n}-hidden`;

    /* ═══════════════════════════════════════════════════════════════
       SINGLE SELECT
    ═══════════════════════════════════════════════════════════════ */
    function cselToggle(id) {
        const wrap = document.getElementById(cselWrapperId(id));
        if (!wrap) return;
        const isOpen = wrap.classList.contains('open');

        document.querySelectorAll('.csel-wrap.open').forEach(w => {
            w.classList.remove('open');
            w.querySelector('.csel-trigger')?.classList.remove('open');
        });

        if (!isOpen) {
            wrap.classList.add('open');
            wrap.querySelector('.csel-trigger')?.classList.add('open');
            const si = wrap.querySelector('.csel-search input');
            if (si) { si.value = ''; si.focus(); }
            wrap.querySelectorAll('.csel-option').forEach(o => o.style.display = 'flex');
            wrap.querySelector('.csel-no-results')?.remove();
        }
    }

    function cselPick(id, value, el) {
        const wrap   = document.getElementById(cselWrapperId(id));
        const hidden = document.getElementById(cselHiddenId(id));
        if (!wrap || !hidden) return;

        hidden.value = value;

        const trigger = wrap.querySelector('.csel-trigger');
        trigger.querySelector('span').textContent = el.querySelector('span').textContent.trim();
        trigger.classList.remove('placeholder');

        wrap.querySelectorAll('.csel-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        wrap.classList.remove('open');
        trigger.classList.remove('open');

        hidden.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function cselFilter(id, term) {
        const q    = term.toLowerCase().trim();
        let   any  = false;
        const wrap = document.getElementById(cselWrapperId(id));
        if (!wrap) return;

        wrap.querySelectorAll('.csel-option').forEach(opt => {
            const match = opt.querySelector('span').textContent.toLowerCase().includes(q);
            opt.style.display = match ? 'flex' : 'none';
            if (match) any = true;
        });

        let empty = wrap.querySelector('.csel-no-results');
        if (!any && !empty) {
            empty = document.createElement('div');
            empty.className   = 'csel-empty csel-no-results';
            empty.textContent = 'No results found';
            wrap.querySelector('.csel-options')?.appendChild(empty);
        } else if (any && empty) { empty.remove(); }
    }

    function cselSetOptions(id, options) {
        const wrap = document.getElementById(cselWrapperId(id));
        if (!wrap) return;

        const container = document.getElementById(`csel-${id}-options`)
                       || wrap.querySelector('.csel-options');
        if (!container) return;

        container.innerHTML = '';
        const entries = Object.entries(options);
        if (entries.length === 0) {
            container.innerHTML = '<div class="csel-empty">No options available</div>';
            return;
        }
        entries.forEach(([val, label]) => {
            const div         = document.createElement('div');
            div.className     = 'csel-option';
            div.dataset.value = val;
            div.onclick       = function () { cselPick(id, val, this); };
            div.innerHTML     = `<span>${label}</span>
                <svg class="csel-check" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>`;
            container.appendChild(div);
        });
    }

    function cselReset(id, placeholder) {
        const wrap   = document.getElementById(cselWrapperId(id));
        const hidden = document.getElementById(cselHiddenId(id));
        if (!wrap) return;
        if (hidden) hidden.value = '';

        const trigger = wrap.querySelector('.csel-trigger');
        if (trigger) {
            trigger.querySelector('span').textContent = placeholder ?? 'Select an option';
            trigger.classList.add('placeholder');
            trigger.classList.remove('open');
        }
        wrap.querySelectorAll('.csel-option').forEach(o => o.classList.remove('selected'));
        wrap.classList.remove('open');
    }

    /* ═══════════════════════════════════════════════════════════════
       MULTI SELECT (used elsewhere, kept intact)
    ═══════════════════════════════════════════════════════════════ */
    const cmultiState = {};

    function cmultiToggle(id) {
        const wrap = document.getElementById(cmultiWrapperId(id));
        if (!wrap) return;
        const isOpen = wrap.classList.contains('open');

        document.querySelectorAll('.cmulti-wrap.open').forEach(w => {
            w.classList.remove('open');
            w.querySelector('.cmulti-trigger')?.classList.remove('open');
        });

        if (!isOpen) {
            wrap.classList.add('open');
            wrap.querySelector('.cmulti-trigger')?.classList.add('open');
            const si = wrap.querySelector('.cmulti-search input');
            if (si) { si.value = ''; si.focus(); }
            wrap.querySelectorAll('.cmulti-option').forEach(o => o.style.display = 'flex');
            wrap.querySelector('.cmulti-no-results')?.remove();
        }
    }

    function cmultiToggleOption(id, value) {
        if (!cmultiState[id]) cmultiState[id] = new Set();
        cmultiState[id].has(value)
            ? cmultiState[id].delete(value)
            : cmultiState[id].add(value);

        cmultiRenderTags(id);
        cmultiSyncOptions(id);
        cmultiRenderHidden(id);
    }

    function cmultiSyncOptions(id) {
        const wrap = document.getElementById(cmultiWrapperId(id));
        if (!wrap) return;
        const selected = cmultiState[id] || new Set();
        wrap.querySelectorAll('.cmulti-option').forEach(opt => {
            const sel = selected.has(opt.dataset.value);
            opt.classList.toggle('selected', sel);
            const chk = opt.querySelector('.cmulti-check');
            if (chk) chk.style.opacity = sel ? '1' : '0';
        });
    }

    function cmultiRenderTags(id) {
        const wrap = document.getElementById(cmultiWrapperId(id));
        if (!wrap) return;
        const trigger = wrap.querySelector('.cmulti-trigger');
        if (!trigger) return;
        const ph = trigger.querySelector('.cmulti-placeholder');

        trigger.querySelectorAll('.cmulti-tag').forEach(t => t.remove());

        const selected = cmultiState[id] || new Set();
        if (selected.size === 0) {
            if (ph) ph.style.display = 'inline';
        } else {
            if (ph) ph.style.display = 'none';
            selected.forEach(val => {
                const safe = val.replace(/'/g, "\\'");
                const tag  = document.createElement('span');
                tag.className = 'cmulti-tag';
                tag.innerHTML = `${val} <button type="button" onclick="event.stopPropagation();cmultiToggleOption('${id}','${safe}')">×</button>`;
                trigger.insertBefore(tag, trigger.lastElementChild);
            });
        }
    }

    function cmultiRenderHidden(id) {
        const container = document.getElementById(cmultiHiddenId(id));
        if (!container) return;
        container.innerHTML = '';
        (cmultiState[id] || new Set()).forEach(val => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = `${id}[]`;
            inp.value = val;
            container.appendChild(inp);
        });
    }

    function cmultiFilter(id, term) {
        const q         = term.toLowerCase().trim();
        let   any       = false;
        const container = document.getElementById(cmultiOptId(id));
        if (!container) return;

        container.querySelectorAll('.cmulti-option').forEach(opt => {
            const match = opt.querySelector('span').textContent.toLowerCase().includes(q);
            opt.style.display = match ? 'flex' : 'none';
            if (match) any = true;
        });

        let empty = container.querySelector('.cmulti-no-results');
        if (!any && !empty) {
            empty = document.createElement('div');
            empty.className   = 'cmulti-empty cmulti-no-results';
            empty.textContent = 'No results found';
            container.appendChild(empty);
        } else if (any && empty) { empty.remove(); }
    }

    function cmultiGetSelected(id) {
        return Array.from(cmultiState[id] || new Set());
    }

    function cmultiSetOptions(id, values) {
        const container = document.getElementById(cmultiOptId(id));
        if (!container) return;

        container.innerHTML = '';
        cmultiState[id] = new Set();
        cmultiRenderTags(id);
        cmultiRenderHidden(id);

        if (!values || values.length === 0) {
            container.innerHTML = '<div class="cmulti-empty">No options available</div>';
            return;
        }
        values.forEach(val => {
            const div         = document.createElement('div');
            div.className     = 'cmulti-option';
            div.dataset.value = val;
            div.onclick       = () => cmultiToggleOption(id, val);
            div.innerHTML     = `<span>${val}</span>
                <svg class="cmulti-check" style="opacity:0" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="#4f46e5" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>`;
            container.appendChild(div);
        });
    }

    /* Close all dropdowns on outside click */
    document.addEventListener('click', e => {
        if (!e.target.closest('.csel-wrap')) {
            document.querySelectorAll('.csel-wrap.open').forEach(w => {
                w.classList.remove('open');
                w.querySelector('.csel-trigger')?.classList.remove('open');
            });
        }
        if (!e.target.closest('.cmulti-wrap')) {
            document.querySelectorAll('.cmulti-wrap.open').forEach(w => {
                w.classList.remove('open');
                w.querySelector('.cmulti-trigger')?.classList.remove('open');
            });
        }
    });

    /* ═══════════════════════════════════════════════════════════════
       ATTRIBUTE VALUES MAP
    ═══════════════════════════════════════════════════════════════ */
    const ATTRIBUTE_VALUES = {
        Color:    ['Red','Blue','Green','Black','White','Yellow','Orange','Purple','Pink','Grey'],
        Size:     ['XS','S','M','L','XL','XXL','3XL','4XL'],
        Material: ['Cotton','Polyester','Wool','Leather','Silk','Linen','Nylon','Denim'],
        Weight:   ['100g','250g','500g','1kg','2kg','5kg','10kg'],
    };

    /* ═══════════════════════════════════════════════════════════════
       DOMContentLoaded
    ═══════════════════════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', function () {

        /* Auto-slug from product name */
        const productNameInput = document.getElementById('product_name');
        const slugInput        = document.getElementById('slug');
        if (productNameInput && slugInput) {
            productNameInput.addEventListener('input', function () {
                slugInput.value = this.value
                    .toLowerCase().trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-');
            });
        }

        /* Category → Sub-Category via AJAX */
        const categoryHidden = document.getElementById(cselHiddenId('category_id'));
        if (categoryHidden) {
            categoryHidden.addEventListener('change', function () {
                const categoryId = this.value;
                cselReset('sub_category_id', 'Select Sub Category');
                if (!categoryId) return;

                fetch("{{ route('get-subcategory') }}?category_id=" + encodeURIComponent(categoryId))
                    .then(r => r.json())
                    .then(data => cselSetOptions('sub_category_id', data))
                    .catch(() => cselSetOptions('sub_category_id', {}));
            });
        }

        /* ── Variant Attribute → populate plain checkbox list ── */
        const attrHidden = document.getElementById(cselHiddenId('variant_attribute'));
        if (attrHidden) {
            attrHidden.addEventListener('change', function () {
                const selectedAttr = this.value;
                const valuesWrap   = document.getElementById('variant-values-wrap');
                const valuesList   = document.getElementById('variant-values-list');

                if (!selectedAttr) {
                    valuesWrap.style.display = 'none';
                    valuesList.innerHTML = '';
                    return;
                }

                const allValues = ATTRIBUTE_VALUES[selectedAttr] || [];

                /* Build checkbox rows */
                valuesList.innerHTML = '';
                allValues.forEach(val => {
                    const uid = `vv_${val.replace(/[^a-z0-9]/gi, '_')}`;
                    const row = document.createElement('div');
                    row.className = 'variant-row';
                    row.innerHTML = `
                        <input type="checkbox" id="${uid}" value="${val}" class="variant-value-cb">
                        <label for="${uid}">${val}</label>`;
                    valuesList.appendChild(row);
                });

                valuesWrap.style.display = 'block';
            });
        }
    });

    /* ═══════════════════════════════════════════════════════════════
       PRODUCT TYPE TOGGLE
    ═══════════════════════════════════════════════════════════════ */
    function toggleProductType() {
        const selected = document.querySelector('input[name="product_type"]:checked').value;
        document.getElementById('single-product-section').style.display   = selected === 'Single'   ? 'block' : 'none';
        document.getElementById('variable-product-section').style.display = selected === 'Variable' ? 'block' : 'none';
    }

    /* ═══════════════════════════════════════════════════════════════
       GENERATE VARIANT TABLE
    ═══════════════════════════════════════════════════════════════ */
    function generateVariants() {
        const attr      = document.getElementById(cselHiddenId('variant_attribute')).value;
        const checked   = [...document.querySelectorAll('.variant-value-cb:checked')].map(c => c.value);
        const tableBody = document.getElementById('variant-table-body');
        tableBody.innerHTML = '';

        if (!attr || checked.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">
                Please select an attribute and at least one value.</td></tr>`;
            return;
        }

        checked.forEach(val => {
            const sku = 'SKU-' + Math.floor(100000 + Math.random() * 900000);
            tableBody.innerHTML += `
                <tr>
                    <td><input type="text"   name="variant_name[]"  class="form-control form-control-sm" value="${attr} - ${val}" readonly></td>
                    <td><input type="text"   name="variant_sku[]"   class="form-control form-control-sm" value="${sku}"></td>
                    <td><input type="number" name="variant_qty[]"   class="form-control form-control-sm" placeholder="0" min="0"></td>
                    <td><input type="number" name="variant_price[]" class="form-control form-control-sm" step="0.01" placeholder="0.00" min="0"></td>
                </tr>`;
        });
    }

    /* ═══════════════════════════════════════════════════════════════
       IMAGE UPLOAD & PREVIEW
    ═══════════════════════════════════════════════════════════════ */
    const imageInput       = document.getElementById('imageInput');
    const uploadBox        = document.getElementById('uploadBox');
    const previewContainer = document.getElementById('imagePreview');
    let   selectedFiles    = [];

    uploadBox.addEventListener('click', () => imageInput.click());
    imageInput.addEventListener('change', function () { addFiles(this.files); });

    function addFiles(files) {
        Array.from(files).forEach(file => selectedFiles.push(file));
        updatePreview();
        updateInputFiles();
    }

    function updatePreview() {
        previewContainer.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'preview-card';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="preview">
                    <button type="button" onclick="removeImage(${index})" class="delete-btn">
                        <i class="fa-solid fa-xmark"></i>
                    </button>`;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
        updateInputFiles();
    }

    function updateInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        imageInput.files = dt.files;
    }

    /* ═══════════════════════════════════════════════════════════════
       GENERATORS
    ═══════════════════════════════════════════════════════════════ */
    function generateSKU() {
        document.getElementById('sku-input').value = 'SKU-' + Math.floor(100000 + Math.random() * 900000);
    }

    function generateBarcode() {
        document.getElementById('barcode-input').value = Math.floor(100000000000 + Math.random() * 900000000000);
    }
    </script>
</x-app-layout>