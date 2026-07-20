<x-app-layout>

    <style>
        :root {
            --ink: #171a2e;
            --ink-soft: #5b5f78;
            --canvas: #f5f6fb;
            --surface: #ffffff;
            --line: #e6e7f2;
            --primary: #4f46e5;
            --primary-ink: #3730a3;
            --primary-tint: #eef0fd;
            --accent: #0d9488;
            --accent-tint: #e6f6f4;
            --radius-lg: 16px;
            --radius-md: 10px;
            --shadow-card: 0 1px 2px rgba(23,26,46,.04), 0 8px 24px -12px rgba(23,26,46,.10);
            --shadow-card-hover: 0 1px 2px rgba(23,26,46,.05), 0 16px 32px -14px rgba(23,26,46,.16);
        }

        /* ---------- Step rail (signature element) ---------- */
        .pf-rail { 
            position:relative; 
        }
        .pf-step { 
            position:relative; 
            display:flex; 
            gap:1.25rem; 
            z-index:1;
        }
        .pf-step-body { 
            flex:1; 
            min-width:0; 
        }

        /* ---------- Cards ---------- */
        .pf-card {
            background:var(--surface);
            border:1px solid var(--line);
            border-radius:var(--radius-lg);
            box-shadow:var(--shadow-card);
            margin-bottom:1.75rem;
            transition:box-shadow .25s ease, transform .25s ease;
            overflow:hidden;
        }
        .pf-card:hover { 
            box-shadow:var(--shadow-card-hover); 
        }
        .pf-card-head { 
            padding:1.35rem 1.5rem 1.1rem; 
            border-bottom:1px solid var(--line); 
        }
        .pf-card-head h2 { 
            font-size:1.05rem; 
            font-weight:700; 
            margin:0; 
            color:var(--ink); 
        }
        .pf-card-head p { 
            font-size:.83rem; 
            color:var(--ink-soft); 
            margin:.2rem 0 0; 
        }
        .pf-card-body { 
            padding:1.5rem; 
        }

        /* ---------- Form controls ---------- */
        .form-label { 
            font-weight:600; 
            font-size:.82rem; 
            color:var(--ink); 
            margin-bottom:.4rem; 
        }
        .form-control, .form-select {
            border:1.5px solid var(--line); 
            border-radius:var(--radius-md);
            padding:.6rem .85rem; 
            font-size:.92rem; 
            background:var(--surface);
            transition:border-color .15s ease, box-shadow .15s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color:var(--primary); 
            box-shadow:0 0 0 4px var(--primary-tint); 
            outline:none;
        }
        .input-group .btn-outline-secondary {
            border:1.5px solid var(--line); 
            border-left:none; 
            color:var(--ink-soft);
        }
        .input-group .btn-outline-secondary:hover { 
            background:var(--primary-tint); 
            color:var(--primary-ink); 
        }

        /* ---------- Buttons ---------- */
        .btn-primary { 
            background:var(--primary); 
            border-color:var(--primary); 
            font-weight:600; 
        }
        .btn-primary:hover { 
            background:var(--primary-ink); 
            border-color:var(--primary-ink); 
        }
        .btn-save {
            background:var(--accent); 
            border-color:var(--accent); 
            font-weight:700;
            box-shadow:0 8px 20px -8px rgba(13,148,136,.55);
        }
        .btn-save:hover { 
            background:#0b7d73; 
            border-color:#0b7d73; 
        }

        /* ---------- Product type segmented control ---------- */
        .pf-type-choice { 
            position:relative; 
        }
        .pf-type-choice .btn-check:checked + .pf-type-card {
            border-color:var(--primary); 
            background:var(--primary-tint);
        }
        .pf-type-choice .btn-check:checked + .pf-type-card .pf-type-icon {
            background:var(--primary); 
            color:#fff;
        }
        .pf-type-card {
            display:flex; 
            align-items:flex-start; 
            gap:.9rem; 
            cursor:pointer;
            border:1.5px solid var(--line); 
            border-radius:var(--radius-md);
            padding:1rem 1.1rem; 
            height:100%; 
            transition:all .15s ease; 
            margin:0;
        }
        .pf-type-card:hover { 
            border-color:var(--primary); 
        }
        .pf-type-icon {
            width:38px; 
            height:38px; 
            border-radius:9px; 
            background:var(--primary-tint); 
            color:var(--primary);
            display:flex; 
            align-items:center; 
            justify-content:center; 
            flex-shrink:0; 
            font-size:1rem;
        }
        .pf-type-title { 
            font-weight:700; 
            font-size:.92rem; 
            color:var(--ink); 
        }
        .pf-type-desc { 
            font-size:.78rem; 
            color:var(--ink-soft); 
            margin:0; 
        }

        /* ---------- Variant value chips ---------- */
        .pf-chip-list { 
            display:flex; 
            flex-wrap:wrap; 
            gap:.5rem; 
        }
        .pf-chip-list .btn-check:checked + .pf-chip {
            background:var(--primary); 
            border-color:var(--primary); 
            color:#fff;
        }
        .pf-chip {
            border:1.5px solid var(--line); 
            border-radius:999px; 
            padding:.4rem 1rem;
            font-size:.85rem; 
            font-weight:600; 
            color:var(--ink-soft); 
            cursor:pointer;
            transition:all .15s ease; 
            margin:0;
        }
        .pf-chip:hover { 
            border-color:var(--primary); 
            color:var(--primary-ink); 
        }

        /* ---------- Upload ---------- */
        .upload-box {
            width:120px; 
            height:120px; 
            border:2px dashed var(--line); 
            border-radius:var(--radius-md);
            display:flex; 
            flex-direction:column; 
            align-items:center; 
            justify-content:center;
            cursor:pointer; 
            transition:all .2s ease; 
            flex-shrink:0; 
            background:var(--canvas);
        }
        .upload-box:hover { 
            border-color:var(--accent); 
            background:var(--accent-tint); 
        }
        .upload-box i { 
            font-size:1.6rem; 
            color:var(--accent); 
            margin-bottom:.4rem; 
        }
        .upload-box span { 
            font-size:.72rem; 
            font-weight:700; 
            color:var(--ink-soft); 
        }

        .preview-card { 
            position:relative; 
            width:120px; 
            height:120px; 
            border-radius:var(--radius-md); 
            overflow:hidden; 
            box-shadow:var(--shadow-card); 
            animation:pf-fade .3s ease; 
        }
        .preview-card img { 
            width:100%; 
            height:100%; 
            object-fit:cover; 
        }
        @keyframes pf-fade { 
            from{
                opacity:0; 
                transform:scale(.92);
            } to{
                opacity:1; 
                transform:scale(1);
            } 
        }
        .preview-card .btn-close {
            position:absolute; 
            top:6px; 
            right:6px; 
            background-color:#fff; '
            opacity:.9;
            width:.55rem; 
            height:.55rem; 
            padding:.4rem; 
            border-radius:50%;
        }

        /* ---------- Sticky action bar ---------- */
        .pf-actions {
            position:sticky; 
            bottom:1rem; 
            z-index:5;
            background:rgba(255,255,255,.85); 
            backdrop-filter:blur(10px);
            border:1px solid var(--line); 
            border-radius:var(--radius-lg);
            box-shadow:var(--shadow-card-hover);
            padding:1rem 1.5rem; 
            display:flex; 
            justify-content:flex-end; 
            gap:.75rem;
        }

        @media (max-width: 767.98px) {
            .pf-rail::before { 
                display:none; 
            }
            .pf-node { 
                width:38px; 
                height:38px; 
                font-size:.8rem; 
            }
        }
    </style>

    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">{{ $title }}</h2>
                    <p class="text-muted mb-0 small">{{ $title }}</p>
                </div>
            </div>
        </x-slot>
        <div class="header-actions">
            <a href="{{ route('products.products.index') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="container-fluid px-0 py-4">
        <div class="container">

            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="pf-rail">
                    <div class="pf-step mb-4">
                        <div class="pf-step-body">
                            <div class="pf-card">
                                <div class="pf-card-head">
                                    <h2>Product information</h2>
                                    <p>The basics — name, identifiers and where it's stocked.</p>
                                </div>
                                <div class="pf-card-body">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <x-forms.select
                                                name="warehouse_id"
                                                label="Warehouse"
                                                :options="getWarehouse()"
                                                :value="($product->warehouse_id ?? '')"
                                                placeholder="Select Warehouse"
                                                required
                                            />
                                        </div>

                                        <div class="col-md-4">
                                            <x-forms.input
                                                label="Product Name"
                                                name="product_name"
                                                type="text"
                                                :value="old('product_name', $product->product_name ?? '')"
                                                placeholder="Enter product name"
                                                required
                                            />
                                        </div>

                                        <div class="col-md-4">
                                            <x-forms.input
                                                label="Slug"
                                                name="slug"
                                                type="text"
                                                :value="old('slug', $product->slug ?? '')"
                                                placeholder="product-url-slug"
                                                required
                                            />
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="sku" id="sku-input" class="form-control pf-mono" value="{{ old('sku', $product->sku ?? '') }}" placeholder="SKU-12345" required>
                                                <button type="button" class="btn btn-outline-secondary" onclick="generateSKU()" title="Generate SKU">
                                                    <i class="fa-solid fa-rotate"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <x-forms.select
                                                name="selling_type"
                                                label="Selling Type"
                                                :options="['Retail' => 'Retail', 'Wholesale' => 'Wholesale']"
                                                placeholder="Select Selling Type"
                                                :value="($product->selling_type ?? '')"
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
                                                :value="($product->category_id ?? '')"
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
                                                :value="($product->sub_category_id ?? '')"
                                            />
                                        </div>

                                        <div class="col-md-4">
                                            <x-forms.select
                                                name="brand_id"
                                                label="Brand"
                                                :options="getBrands()"
                                                placeholder="Select Brand"
                                                :value="($product->brand_id ?? '')"
                                                required
                                            />
                                        </div>

                                        <div class="col-md-4">
                                            <x-forms.select
                                                name="unit_id"
                                                label="Unit"
                                                :options="getUnit()"
                                                placeholder="Select Unit (kg, pcs, box)"
                                                :value="($product->unit_id ?? '')"
                                                required
                                            />
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Barcode <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="barcode" id="barcode-input" class="form-control pf-mono" value="{{ old('barcode', $product->barcode ?? '') }}" placeholder="Barcode" required>
                                                <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()" title="Generate barcode">
                                                    <i class="fa-solid fa-barcode"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control" rows="4" placeholder="Brief details about the product...">{{ $product->description ?? '' }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== STEP 02 — PRICING & INVENTORY ===================== --}}
                    <div class="pf-step mb-4">
                        <div class="pf-step-body">
                            <div class="pf-card">
                                <div class="pf-card-head">
                                    <h2>Pricing &amp; inventory</h2>
                                    <p>Choose whether this is one item, or comes in multiple options.</p>
                                </div>
                                <div class="pf-card-body">

                                    <div class="row g-3 mb-4">
                                        <div class="col-sm-6 pf-type-choice">
                                            <input type="radio" class="btn-check" name="product_type" id="typeSingle" value="Single" checked onclick="toggleProductType()">
                                            <label class="pf-type-card" for="typeSingle">
                                                <span class="pf-type-icon"><i class="fa-solid fa-cube"></i></span>
                                                <span>
                                                    <span class="pf-type-title d-block">Single product</span>
                                                    <p class="pf-type-desc">One price, one SKU, one stock count.</p>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-sm-6 pf-type-choice">
                                            <input type="radio" class="btn-check" name="product_type" id="typeVariable" value="Variable" onclick="toggleProductType()">
                                            <label class="pf-type-card" for="typeVariable">
                                                <span class="pf-type-icon"><i class="fa-solid fa-layer-group"></i></span>
                                                <span>
                                                    <span class="pf-type-title d-block">Variable product</span>
                                                    <p class="pf-type-desc">Comes in options, like size or color.</p>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- ── Single Product ── --}}
                                    <div id="single-product-section">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <x-forms.input
                                                    label="Quantity"
                                                    name="quantity"
                                                    type="number"
                                                    :value="old('quantity', $product->quantity ?? '')"
                                                    placeholder="Enter Quantity"
                                                    min="0"
                                                />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-control" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <x-forms.select
                                                    name="tax_type"
                                                    label="Tax Type"
                                                    :options="['Exclusive' => 'Exclusive', 'Inclusive' => 'Inclusive']"
                                                    placeholder="Select Tax Type"
                                                    :value="old('tax_type', $product->tax_type ?? '')"
                                                />
                                            </div>
                                            <div class="col-md-3">
                                                <x-forms.input
                                                    label="Tax (%)"
                                                    name="tax_value"
                                                    type="number"
                                                    :value="old('tax_value', $product->tax_value ?? '')"
                                                    placeholder="Enter tax value"
                                                    step="0.01"
                                                    min="0"
                                                />
                                            </div>
                                            <div class="col-md-4">
                                                <x-forms.select
                                                    name="discount_type"
                                                    label="Discount Type"
                                                    :options="['Percentage' => 'Percentage', 'Fixed' => 'Fixed']"
                                                    placeholder="Select Discount Type"
                                                    :value="old('discount_type', $product->discount_type ?? '')"
                                                />
                                            </div>
                                            <div class="col-md-4">
                                                <x-forms.input
                                                    label="Discount Value"
                                                    name="discount_value"
                                                    type="number"
                                                    :value="old('discount_value', $product->discount_value ?? '')"
                                                    placeholder="Enter discount value"
                                                    step="0.01"
                                                    min="0"
                                                />
                                            </div>
                                            <div class="col-md-4">
                                                <x-forms.input
                                                    label="Low Stock Alert"
                                                    name="alert_quantity"
                                                    type="number"
                                                    :value="old('alert_quantity', $product->alert_quantity ?? '')"
                                                    placeholder="Enter low stock alert"
                                                    min="0"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ── Variable Product ── --}}
                                    <div id="variable-product-section">
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <x-forms.select
                                                    name="variant_attribute"
                                                    id="variant_attribute"
                                                    label="Variant Attribute"
                                                    :options="['Color' => 'Color', 'Size' => 'Size', 'Material' => 'Material', 'Weight' => 'Weight']"
                                                    :value="old('variant_attribute', $product->variant_attribute ?? '')"
                                                    placeholder="Select attribute"
                                                />
                                            </div>

                                            {{-- Variant Values — chip-style checkboxes, shown after attribute picked --}}
                                            <div class="col-md-6 d-none" id="variant-values-wrap">
                                                <label class="form-label">Variant Values</label>
                                                <div id="variant-values-list" class="pf-chip-list">
                                                </div>
                                            </div>

                                            <div class="col-12 mt-2">
                                                <button type="button" class="btn btn-primary" onclick="generateVariants()">
                                                    <i class="fa-solid fa-layer-group me-2"></i> Generate Variant Table
                                                </button>
                                            </div>

                                            <div class="col-12 mt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-hover align-middle border rounded overflow-hidden">
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
                            </div>
                        </div>
                    </div>

                    {{-- ===================== STEP 03 — PRODUCT GALLERY ===================== --}}
                    <div class="pf-step mb-4">
                        <div class="pf-step-body">
                            <div class="pf-card">
                                <div class="pf-card-head">
                                    <h2>Product gallery</h2>
                                    <p>Add photos so customers know what they're getting.</p>
                                </div>
                                <div class="pf-card-body">
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <div class="upload-box" id="uploadBox">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            <span>Add image</span>
                                            <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="d-none">
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 align-items-center" id="imagePreview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== STEP 04 — CUSTOM FIELDS & DATES ===================== --}}
                    <div class="pf-step mb-4">
                        <div class="pf-step-body">
                            <div class="pf-card">
                                <div class="pf-card-head">
                                    <h2>Custom fields &amp; dates</h2>
                                    <p>Optional — fill in if it applies to this product.</p>
                                </div>
                                <div class="pf-card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <x-forms.select
                                                name="warranty_id"
                                                label="Warranty"
                                                :options="[]"
                                                placeholder="Select Warranty"
                                                :value="old('warranty_id', $product->warranty_id ?? '')"
                                            />
                                        </div>
                                        <div class="col-md-3">
                                            <x-forms.input
                                                label="Manufacturer"
                                                name="manufacturer"
                                                type="text"
                                                :value="old('manufacturer', $product->manufacturer ?? '')"
                                                placeholder="Enter Manufacturer"
                                            />
                                        </div>
                                        <div class="col-md-3">
                                            <x-forms.input
                                                label="MFG Date"
                                                name="mfg_date"
                                                type="date"
                                                :value="old('mfg_date', $product->mfg_date ?? '')"
                                                placeholder="Enter MFG Date"
                                            />
                                        </div>
                                        <div class="col-md-3">
                                            <x-forms.input
                                                label="Expiry Date"
                                                name="exp_date"
                                                type="date"
                                                :value="old('year_built', $product->year_built ?? '')"
                                                placeholder="Enter Expiry Date"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ===================== ACTIONS ===================== --}}
                <div class="pf-actions">
                    <button type="button" class="btn btn-light border px-4">Cancel</button>
                    <button type="submit" class="btn btn-save text-white px-4">
                        <i class="fa-solid fa-check me-2"></i> Save Product
                    </button>
                </div>

            </form>
        </div>
    </div>
    @include('product.products.scripts')
</x-app-layout>