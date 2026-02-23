<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">Create Category</h2>
                    <p class="text-muted mb-0 small">Create new product category</p>
                </div>
            </div>
        </x-slot>
        
        <div class="header-actions">
            <a href="{{ route('products.categories.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content py-4">
        <div class="container-fluid">
            <x-basic.form action="{{ route('products.categories.save', $form?->id) }}" enctype="multipart/form-data" novalidate>
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="card custom-card">
                            <div class="card-header bg-transparent border-bottom ">
                                <h5 class="card-title mb-0 fw-bold text-dark">General Information</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-basic.form.text label="{{ __('global.category_name') }}" name="name"
                                            placeholder="e.g. Electronics" value="{{ $form?->name }}" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-basic.form.text label="{{ __('global.category_code') }}" name="code"
                                            placeholder="e.g. CAT-001" value="{{ $form?->code }}" :required="true" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('global.warehouse') }} <span class="text-danger">*</span></label>
                                        <select class="form-select select2-basic" name="warehouse_id" required>
                                            <option value="">Select Warehouse</option>
                                            {{-- @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ $form?->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('global.parent_category') }}</label>
                                        <select class="form-select select2-basic" name="parent_id">
                                            <option value="">None (Top Level)</option>
                                            {{-- @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $form?->parent_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <x-basic.form.text label="{{ __('global.type') }}" name="type"
                                            placeholder="e.g. Physical, Digital" value="{{ $form?->category_type }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="d-flex flex-column gap-4">
                            <div class="card custom-card">
                                <div class="card-header bg-transparent border-bottom">
                                    <h5 class="card-title mb-0 fw-bold text-dark">Category Image</h5>
                                </div>
                                <div class="card-body text-center p-4">
                                    <div class="image-upload-wrapper mb-3">
                                        <div class="preview-container border rounded-3 d-flex align-items-center justify-content-center bg-light" style="height: 180px; position: relative; overflow: hidden;">
                                            <i class="fa-solid fa-cloud-arrow-up fs-1 text-muted" id="placeholder-icon"></i>
                                            <img id="image-preview" src="#" alt="Preview" class="d-none img-fluid h-100 w-100 object-fit-cover">
                                        </div>
                                    </div>
                                    <input type="file" class="form-control form-control-sm" name="category_image" id="category_image" accept="image/*" />
                                    <small class="text-muted mt-2 d-block">Recommended: Square image, max 2MB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card custom-card">
                            <div class="card-body p-3 text-end">
                                <button type="button" class="btn btn-light px-4 me-2">Cancel</button>
                                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                    <i class="ph ph-floppy-disk me-2"></i> Save Category
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-basic.form>
        </div>
    </div>
</x-app-layout>

<script>
    // Simple Image Preview Logic
    document.getElementById('category_image').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('placeholder-icon');
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        }
    }
</script>