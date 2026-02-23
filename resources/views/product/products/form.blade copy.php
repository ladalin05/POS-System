<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Product Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            border: 1px solid #dee2e6;
        }
        .section-header {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .toggle-section {
            background: #ffffff;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            transition: all 0.3s ease;
        }
        .toggle-section.active {
            border-color: #0d6efd;
            background: #f8f9ff;
        }
        .file-preview {
            background: #e9ecef;
            padding: 8px 12px;
            border-radius: 4px;
            margin-top: 8px;
            font-size: 0.9em;
        }
        .field-group {
            transition: all 0.3s ease;
        }
        .field-group.hidden {
            display: none !important;
        }
        .inventory-badge {
            background: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            margin-left: 8px;
        }
        .service-badge {
            background: #17a2b8;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            margin-left: 8px;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-group-custom {
            gap: 10px;
        }
        .price-field {
            position: relative;
        }
        .price-field::before {
            content: '$';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-weight: 500;
            z-index: 5;
        }
        .price-field input {
            padding-left: 25px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-box me-2"></i>Product Management</h4>
                    </div>
                    <div class="card-body">
                        <form id="productForm" novalidate>
                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    Basic Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Product Type <span class="text-danger">*</span></label>
                                        <select class="form-select" name="product_type" id="product_type" required>
                                            <option value="">Select Type</option>
                                            <option value="standard">Standard Product</option>
                                            <option value="service">Service</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a product type.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" required>
                                        <div class="invalid-feedback">Please enter a product name.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_code" required>
                                        <div class="form-text">You can scan your barcode and search the correct symbology below.</div>
                                        <div class="invalid-feedback">Please enter a product code.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Classification Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-tags text-success"></i>
                                    Product Classification
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Brand</label>
                                        <select class="form-select" name="brand">
                                            <option value="">Select Brand</option>
                                            <option value="samsung">Samsung</option>
                                            <option value="apple">Apple</option>
                                            <option value="lg">LG</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <option value="1">Electronics</option>
                                            <option value="2">Clothing</option>
                                            <option value="3">Food & Beverages</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a category.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Sub Category</label>
                                        <input type="text" class="form-control" name="sub_category" placeholder="Please select category to load">
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory Management Section (Hidden for Services) -->
                            <div class="form-section field-group inventory-fields">
                                <div class="section-header">
                                    <i class="fas fa-warehouse text-warning"></i>
                                    Inventory Management
                                    <span class="inventory-badge">Standard Product Only</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Product Unit <span class="text-danger">*</span></label>
                                        <select class="form-select" name="unit" id="unit">
                                            <option value="">Select Unit</option>
                                            <option value="piece">Piece</option>
                                            <option value="kg">Kilogram</option>
                                            <option value="liter">Liter</option>
                                            <option value="meter">Meter</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a unit.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Default Sale Unit</label>
                                        <select class="form-select" name="default_sale_unit">
                                            <option value="">Select Unit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Default Purchase Unit</label>
                                        <select class="form-select" name="default_purchase_unit">
                                            <option value="">Select Unit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Product Cost</label>
                                        <div class="price-field">
                                            <input type="number" class="form-control" name="cost" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Alert Quantity</label>
                                        <input type="number" class="form-control" name="alert_quantity" min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-dollar-sign text-success"></i>
                                    Pricing Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Product Price <span class="text-danger">*</span></label>
                                        <div class="price-field">
                                            <input type="number" class="form-control" name="price" step="0.01" min="0" required>
                                        </div>
                                        <div class="invalid-feedback">Please enter a product price.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Media Section (Hidden for Services) -->
                            <div class="form-section field-group inventory-fields">
                                <div class="section-header">
                                    <i class="fas fa-images text-info"></i>
                                    Product Media
                                    <span class="inventory-badge">Standard Product Only</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*" onchange="showFileName(this, 'imagePreview')">
                                        <div id="imagePreview" class="file-preview d-none"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Product Gallery Images</label>
                                        <input type="file" class="form-control" name="product_gallery_images" accept="image/*" multiple onchange="showFileName(this, 'galleryPreview')">
                                        <div id="galleryPreview" class="file-preview d-none"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Promotion Section -->
                            <div class="toggle-section">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="enablePromotion" name="promotion" value="1">
                                    <label class="form-check-label" for="enablePromotion">
                                        <strong><i class="fas fa-percentage me-1"></i>Enable Promotion</strong>
                                    </label>
                                </div>
                                <div id="promotionFields" class="d-none">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Promotion Price</label>
                                            <div class="price-field">
                                                <input type="number" name="promotion_price" class="form-control" step="0.01" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" name="promotion_start" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">End Date</label>
                                            <input type="date" name="promotion_end" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Options -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-cog text-secondary"></i>
                                    Additional Options
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6 field-group inventory-fields">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="enableAdjustment" name="adjustment_qty" value="1">
                                            <label class="form-check-label" for="enableAdjustment">
                                                <strong>Adjustment Quantity</strong>
                                                <span class="inventory-badge">Standard Only</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="enableCustomFields" name="custom_fields" value="1">
                                            <label class="form-check-label" for="enableCustomFields">
                                                <strong>Custom Fields</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Fields Section -->
                            <div id="customFields" class="toggle-section d-none">
                                <div class="section-header">
                                    <i class="fas fa-plus-circle text-primary"></i>
                                    Custom Fields
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Custom Field 1</label>
                                        <input type="text" class="form-control" name="custom_field_1">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Custom Field 2</label>
                                        <input type="text" class="form-control" name="custom_field_2">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Custom Field 3</label>
                                        <input type="text" class="form-control" name="custom_field_3">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Custom Field 4</label>
                                        <input type="text" class="form-control" name="custom_field_4">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Custom Field 5</label>
                                        <input type="text" class="form-control" name="custom_field_5">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Custom Field 6</label>
                                        <input type="text" class="form-control" name="custom_field_6">
                                    </div>
                                </div>
                            </div>

                            <!-- Product Details Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-file-text text-primary"></i>
                                    Product Details
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Product Details</label>
                                        <textarea class="form-control" name="product_details" rows="4" placeholder="Enter detailed product description..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Product Details for Invoice</label>
                                        <textarea class="form-control" name="product_invoice_details" rows="4" placeholder="Enter invoice-specific product details..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end btn-group-custom mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-1"></i>Save Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"></script>

    <script>
        // Complete Dynamic Form Handler
        class ProductFormManager {
            constructor() {
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.initializeRichTextEditors();
                this.setupFormValidation();
                this.populateUnits();
            }

            setupEventListeners() {
                // Product type change handler
                const productTypeSelect = document.getElementById('product_type');
                if (productTypeSelect) {
                    productTypeSelect.addEventListener('change', (e) => {
                        this.handleProductTypeChange(e.target.value);
                    });
                }

                // Promotion toggle
                const promotionCheckbox = document.getElementById('enablePromotion');
                const promotionFields = document.getElementById('promotionFields');
                if (promotionCheckbox && promotionFields) {
                    promotionCheckbox.addEventListener('change', (e) => {
                        this.toggleSection(promotionFields, e.target.checked);
                        document.querySelector('.toggle-section').classList.toggle('active', e.target.checked);
                    });
                }

                // Custom fields toggle
                const customFieldsCheckbox = document.getElementById('enableCustomFields');
                const customFields = document.getElementById('customFields');
                if (customFieldsCheckbox && customFields) {
                    customFieldsCheckbox.addEventListener('change', (e) => {
                        this.toggleSection(customFields, e.target.checked);
                        customFields.classList.toggle('active', e.target.checked);
                    });
                }

                // Unit change handler for sale/purchase units
                const unitSelect = document.getElementById('unit');
                if (unitSelect) {
                    unitSelect.addEventListener('change', (e) => {
                        this.updateSaleAndPurchaseUnits(e.target.value);
                    });
                }
            }

            handleProductTypeChange(productType) {
                const inventoryFields = document.querySelectorAll('.inventory-fields');
                const isService = productType === 'service';

                inventoryFields.forEach(section => {
                    if (isService) {
                        section.classList.add('hidden');
                        // Remove required attributes for hidden fields
                        this.toggleRequiredFields(section, false);
                    } else {
                        section.classList.remove('hidden');
                        // Restore required attributes for visible fields
                        this.toggleRequiredFields(section, true);
                    }
                });

                // Update form title indicator
                this.updateFormIndicator(productType);

                // Show notification
                this.showNotification(`Form updated for ${productType === 'service' ? 'Service' : 'Standard Product'}`);
            }

            toggleRequiredFields(section, makeRequired) {
                const requiredFields = section.querySelectorAll('select[name="unit"]');
                requiredFields.forEach(field => {
                    if (makeRequired) {
                        field.setAttribute('required', 'true');
                    } else {
                        field.removeAttribute('required');
                    }
                });
            }

            updateFormIndicator(productType) {
                const cardHeader = document.querySelector('.card-header h4');
                const icon = productType === 'service' ? 'fas fa-concierge-bell' : 'fas fa-box';
                const text = productType === 'service' ? 'Service Management' : 'Product Management';
                
                if (cardHeader) {
                    cardHeader.innerHTML = `<i class="${icon} me-2"></i>${text}`;
                }
            }

            toggleSection(section, show) {
                if (show) {
                    section.classList.remove('d-none');
                    section.style.animation = 'slideDown 0.3s ease';
                } else {
                    section.classList.add('d-none');
                }
            }

            updateSaleAndPurchaseUnits(selectedUnit) {
                const saleUnitSelect = document.querySelector('select[name="default_sale_unit"]');
                const purchaseUnitSelect = document.querySelector('select[name="default_purchase_unit"]');
                
                if (selectedUnit && saleUnitSelect && purchaseUnitSelect) {
                    const unitOptions = this.getUnitOptions();
                    const selectedOption = unitOptions.find(option => option.value === selectedUnit);
                    
                    if (selectedOption) {
                        // Populate with related units
                        const relatedUnits = this.getRelatedUnits(selectedUnit);
                        
                        [saleUnitSelect, purchaseUnitSelect].forEach(select => {
                            select.innerHTML = '<option value="">Select Unit</option>';
                            relatedUnits.forEach(unit => {
                                select.innerHTML += `<option value="${unit.value}">${unit.text}</option>`;
                            });
                        });
                    }
                }
            }

            getUnitOptions() {
                return [
                    { value: 'piece', text: 'Piece' },
                    { value: 'kg', text: 'Kilogram' },
                    { value: 'liter', text: 'Liter' },
                    { value: 'meter', text: 'Meter' }
                ];
            }

            getRelatedUnits(baseUnit) {
                const unitFamilies = {
                    'piece': [
                        { value: 'piece', text: 'Piece' },
                        { value: 'dozen', text: 'Dozen' },
                        { value: 'box', text: 'Box' }
                    ],
                    'kg': [
                        { value: 'kg', text: 'Kilogram' },
                        { value: 'g', text: 'Gram' },
                        { value: 'ton', text: 'Ton' }
                    ],
                    'liter': [
                        { value: 'liter', text: 'Liter' },
                        { value: 'ml', text: 'Milliliter' },
                        { value: 'gallon', text: 'Gallon' }
                    ],
                    'meter': [
                        { value: 'meter', text: 'Meter' },
                        { value: 'cm', text: 'Centimeter' },
                        { value: 'inch', text: 'Inch' }
                    ]
                };
                
                return unitFamilies[baseUnit] || [];
            }

            populateUnits() {
                const unitSelect = document.getElementById('unit');
                if (unitSelect && unitSelect.children.length <= 1) {
                    const units = this.getUnitOptions();
                    units.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit.value;
                        option.textContent = unit.text;
                        unitSelect.appendChild(option);
                    });
                }
            }

            initializeRichTextEditors() {
                // Initialize Summernote for textareas
                if (typeof $ !== 'undefined' && $.fn.summernote) {
                    $('textarea[name="product_details"]').summernote({
                        height: 120,
                        placeholder: 'Enter detailed product description...',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });
                    
                    $('textarea[name="product_invoice_details"]').summernote({
                        height: 120,
                        placeholder: 'Enter invoice-specific product details...',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });
                }
            }

            setupFormValidation() {
                const form = document.getElementById('productForm');
                if (form) {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        
                        if (this.validateForm(form)) {
                            this.submitForm(form);
                        }
                    });
                }
            }

            validateForm(form) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]:not(.hidden [required])');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });

                if (!isValid) {
                    this.showNotification('Please fill in all required fields', 'error');
                    // Focus on first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                return isValid;
            }

            submitForm(form) {
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
                submitButton.disabled = true;

                // Simulate form submission
                setTimeout(() => {
                    this.showNotification('Product saved successfully!', 'success');
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    
                    // Reset form if needed
                    // form.reset();
                }, 2000);
            }

            showNotification(message, type = 'info') {
                // Create and show a toast notification
                const toast = document.createElement('div');
                toast.className = `alert alert-${type === 'error' ? 'danger' : type} position-fixed`;
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                toast.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation' : 'info'}-circle me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                `;
                
                document.body.appendChild(toast);
                
                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 3000);
            }
        }

        // File preview function
        function showFileName(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files.length > 0) {
                let fileList = '';
                for (let i = 0; i < input.files.length; i++) {
                    fileList += `<i class="fas fa-file-image me-1"></i>${input.files[i].name}<br>`;
                }
                preview.innerHTML = fileList;
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        }

        // Initialize the form manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new ProductFormManager();
        });

        // Add some CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .is-valid {
                border-color: #198754 !important;
            }
            
            .is-invalid {
                border-color: #dc3545 !important;
            }
            
            .alert {
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                border: none;
                border-radius: 8px;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>