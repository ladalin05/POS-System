<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-body: #f8fafc;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body { 
            background-color: var(--bg-body); 
            font-family: 'Inter', sans-serif; 
            color: var(--text-main);
        }

        .page-header {
            margin-bottom: 2rem;
        }

        /* Card Styling */
        .custom-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: transform 0.2s ease;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-section-title i {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            padding: 10px;
            border-radius: 8px;
            font-size: 1rem;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        /* Button Customization */
        .btn-primary { background-color: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background-color: var(--primary-hover); border-color: var(--primary-hover); }
        
        .btn-generate {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        /* Upload Box Styling */
        .upload-box {
            width: 10%;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--bg-light);
        }

        .upload-box:hover {
            border-color: var(--primary-color);
            background: #f0f7ff;
        }

        .upload-box i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Preview Thumbnails */
        .preview-card {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .preview-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .delete-btn:hover {
            transform: scale(1.1);
            background: #dc2626;
        }

        /* Radio Toggle Styles */
        .product-type-selector {
            display: flex;
            gap: 20px;
            padding: 15px;
            background: #f1f5f9;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
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
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>
    <div class="content py-4">
        <div class="container-fluid">
            <form action="{{ route('products.products.add') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="custom-card">
                    <div class="form-section-title">
                        <i class="fa-solid fa-box"></i>
                        Product Information
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Warehouse <span class="text-danger">*</span></label>
                            <select name="warehouse_id" class="form-select" required>
                                <option value="">Select Warehouse</option>
                                @foreach(getWarehouse() as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" class="form-control" placeholder="Enter product name" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control" placeholder="Product URL Slug" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="sku" class="form-control" id="sku-input" placeholder="SKU-12345" required>
                                <button type="button" class="btn btn-generate" onclick="generateSKU()"><i class="fa-solid fa-rotate"></i></button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Selling Type <span class="text-danger">*</span></label>
                            <select name="selling_type" class="form-select" required>
                                <option value="Retail">Retail</option>
                                <option value="Wholesale">Wholesale</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach (getCategory() as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Sub Category</label>
                            <select name="sub_category_id" id="sub_category_id" class="form-select">
                                <option value="">Select Sub Category</option>
                                {{-- @foreach (getSubCategory($category_id) as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach --}}
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-select" required>
                                <option value="">Select Brand</option>
                                @foreach (getBrands() as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" class="form-select" required>
                                <option value="">Select Unit (kg, pcs, box)</option>
                                @foreach (getUnit() as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
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

                <div class="custom-card">
                    <div class="form-section-title">
                        <i class="fa-solid fa-tags"></i>
                        Pricing & Inventory
                    </div>

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

                    <div id="single-product-section">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Quantity *</label>
                                <input type="number" name="quantity" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="price" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tax Type</label>
                                <select name="tax_type" class="form-select">
                                    <option value="Exclusive">Exclusive</option>
                                    <option value="Inclusive">Inclusive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tax (%)</label>
                                <input type="number" name="tax_value" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-select">
                                    <option value="Percentage">Percentage</option>
                                    <option value="Fixed">Fixed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount Value</label>
                                <input type="number" name="discount_value" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Low Stock Alert</label>
                                <input type="number" name="alert_quantity" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div id="variable-product-section" style="display:none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Variant Attributes</label>
                                <select id="variant-attribute" class="form-select" multiple>
                                    <option value="Color">Color</option>
                                    <option value="Size">Size</option>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Variant Values</label>
                                <input type="text" id="variant-values" class="form-control" placeholder="Red, Blue, Green OR S, M, L">
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

                <div class="custom-card">
                    <div class="form-section-title">
                        <i class="fa-solid fa-images text-primary"></i>
                        Product Gallery
                    </div>
                    <div class="d-flex g-3">
                        <div class="upload-box mx-3" id="uploadBox">
                            <i class="fa-solid fa-circle-plus"></i>
                            <p class="mb-1 fw-bold">Add Image</p>
                            
                            <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="d-none">
                        </div>

                        <div class="image-preview-container d-flex flex-wrap gap-3 align-items-center" id="imagePreview">
                        </div>
                    </div>
                </div>

                <div class="custom-card">
                    <div class="form-section-title">
                        <i class="fa-solid fa-circle-info"></i>
                        Custom Fields & Dates
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Warranty *</label>
                            <select name="warranty_id" class="form-select">
                                <option value="">Select Warranty</option>
                            </select>
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

        $(document).ready(function() {
            $('#category_id').off('change').on('change', function() {
                let categoryId = $(this).val();
                if(categoryId === "") {
                    $('#sub_category_id').html('<option value="">Select Sub Category</option>');
                    return;
                }
                $.ajax({
                    url: "{{ route('get-subcategory') }}",
                    type: "GET",
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {

                        let sub = $('#sub_category_id');
                        sub.empty();
                        sub.append('<option value="">Select Sub Category</option>');

                        $.each(response, function(id, name){
                            sub.append('<option value="'+id+'">'+name+'</option>');
                        });
                    }
                });
            });
        });

        function toggleProductType() {
            let selected = document.querySelector('input[name="product_type"]:checked').value;
            let single = document.getElementById("single-product-section");
            let variable = document.getElementById("variable-product-section");

            if (selected === "Single") {
                single.style.display = "block";
                variable.style.display = "none";
            } else {
                single.style.display = "none";
                variable.style.display = "block";
            }
        }

        function generateVariants() {
            let attribute = document.getElementById("variant-attribute");
            let valuesInput = document.getElementById("variant-values").value;
            let values = valuesInput.split(",");
            let tableBody = document.getElementById("variant-table-body");
            tableBody.innerHTML = "";

            Array.from(attribute.selectedOptions).forEach(attr => {
                values.forEach(val => {
                    if(val.trim() === "") return;
                    let variantName = attr.value + " - " + val.trim();
                    let row = `
                        <tr>
                            <td><input type="text" name="variant_name[]" class="form-control form-control-sm" value="${variantName}" readonly></td>
                            <td><input type="text" name="variant_sku[]" class="form-control form-control-sm"></td>
                            <td><input type="number" name="variant_qty[]" class="form-control form-control-sm"></td>
                            <td><input type="number" step="0.01" name="variant_price[]" class="form-control form-control-sm"></td>
                        </tr>`;
                    tableBody.innerHTML += row;
                });
            });
        }

        let imageInput = document.getElementById("imageInput");
        let uploadBox = document.getElementById("uploadBox");
        let previewContainer = document.getElementById("imagePreview");
        let selectedFiles = [];
        /* ================= CLICK TO UPLOAD ================= */
        uploadBox.addEventListener("click", () => {
            imageInput.click();
        });
        /* ================= FILE SELECT ================= */
        imageInput.addEventListener("change", function () {
            addFiles(this.files);
        });
        /* ================= ADD FILES ================= */
        function addFiles(files) {
            Array.from(files).forEach(file => {
                selectedFiles.push(file);
            });
            updatePreview();
            updateInputFiles();
        }
        /* ================= UPDATE PREVIEW ================= */
        function updatePreview() {
            previewContainer.innerHTML = "";
            
            selectedFiles.forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let div = document.createElement("div");
                    div.className = "preview-card"; // Using the new CSS class
                    
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="preview">
                        <button type="button" onclick="removeImage(${index})" class="delete-btn">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
        /* ================= REMOVE IMAGE ================= */
        function removeImage(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
            updateInputFiles();
        }
        /* ================= UPDATE FILE INPUT ================= */
        function updateInputFiles() {
            let dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            imageInput.files = dataTransfer.files;
        }

        function generateSKU() {
            let randomNumber = Math.floor(100000 + Math.random() * 900000);
            let prefix = "SKU-";
            let sku = prefix + randomNumber;
            $('#sku-input').val(sku);
        };


        function generateBarcode() {
            let randomBarcode = Math.floor(100000000000 + Math.random() * 900000000000);
            $('#barcode-input').val(randomBarcode);
        }
    </script>
</x-app-layout>