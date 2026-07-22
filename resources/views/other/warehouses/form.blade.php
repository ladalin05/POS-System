<div class="modal-body">
    <form method="POST" action="{{ $action }}" id="baseUnitForm" enctype="multipart/form-data" class="ajax-form">

        {{-- Section 1: Warehouse Identity --}}
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-light text-primary p-2"><i class="ph ph-warehouse fs-5"></i></span>
                <h6 class="mb-0 fw-bold">Warehouse Details</h6>
            </div>
            <hr class="mt-0 mb-3 opacity-10">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input
                        name="name"
                        label="Warehouse Name"
                        type="text"
                        :value="old('name', $form->name ?? '')"
                        placeholder="Enter Warehouse Name"
                        required
                    />
                </div>

                <div class="col-md-6">
                    <x-forms.input
                        name="code"
                        label="Warehouse Code"
                        type="text"
                        :value="old('code', $form->code ?? '')"
                        placeholder="e.g. WH-001"
                        required
                    />
                </div>

                <div class="col-md-12">
                    <x-forms.select
                        name="branch_id"
                        label="Branch"
                        :options="getBranch()"
                        :value="($form->branch_id ?? '')"
                        placeholder="Select Branch"
                        required
                    />
                </div>
            </div>
        </div>

        {{-- Section 2: Contact Information --}}
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-success-light text-success p-2"><i class="ph ph-phone fs-5"></i></span>
                <h6 class="mb-0 fw-bold">Contact Information</h6>
            </div>
            <hr class="mt-0 mb-3 opacity-10">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input
                        name="email"
                        label="Email"
                        type="email"
                        :value="old('email', $form->email ?? '')"
                        placeholder="Enter Email Address"
                        required
                    />
                </div>

                <div class="col-md-6">
                    <x-forms.input
                        name="phone"
                        label="Phone"
                        type="text"
                        :value="old('phone', $form->phone ?? '')"
                        placeholder="Enter Phone Number"
                        required
                    />
                </div>
            </div>
        </div>

        {{-- Section 3: Location --}}
        <div class="mb-2">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-warning-light text-warning p-2"><i class="ph ph-map-pin fs-5"></i></span>
                <h6 class="mb-0 fw-bold">Location</h6>
            </div>
            <hr class="mt-0 mb-3 opacity-10">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Enter full warehouse address" required>{{ old('address', $form->address ?? '') }}</textarea>
                </div>
            </div>
        </div>

    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
        <i class="ph ph-x me-2"></i>Close
    </button>
    <button type="submit" form="baseUnitForm" class="btn btn-primary px-4 shadow-sm">
        <i class="ph ph-floppy-disk me-2"></i> Save Changes
    </button>
</div>

<style>
    .bg-primary-light { background: rgba(13, 110, 253, 0.1); }
    .bg-success-light { background: rgba(25, 135, 84, 0.1); }
    .bg-warning-light { background: rgba(255, 193, 7, 0.1); }
</style>