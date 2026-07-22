
<div class="modal-body">
    <form method="POST" action="{{ $action }}" id="baseUnitForm" enctype="multipart/form-data" class="ajax-form">
        <div class="row">
            <div class="col-md-6 mb-3">
                <x-form.input
                    name="name"
                    label="Warehouse Name"
                    type="text"
                    :value="old('name', $form->name ?? '')"
                    placeholder="Enter Warehouse Name"
                    required
                />
            </div>

            <div class="col-md-6 mb-3">
                <x-form.input
                    name="code"
                    label="Warehouse Code"
                    type="text"
                    :value="old('code', $form->code ?? '')"
                    placeholder="Enter Warehouse Code"
                    required
                />
            </div>

            <div class="col-md-6 mb-3">
                <x-forms.select
                    name="branch_id"
                    label="Branch"
                    :options="getBranch()"
                    :value="($form->branch_id ?? '')"
                    placeholder="Select branch_id"
                    required
                />
            </div>

            <div class="col-md-6 mb-3">
                <x-form.input
                    name="email"
                    label="Email"
                    type="text"
                    :value="old('email', $form->email ?? '')"
                    placeholder="Enter Your Email"
                    required
                />
            </div>

            <div class="col-md-6 mb-3">
                <x-form.input
                    name="phone"
                    label="phone"
                    type="text"
                    :value="old('phone', $form->phone ?? '')"
                    placeholder="Enter Your Phone"
                    required
                />
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Address <span class="text-danger">*</span></label>
                <textarea name="address" class="form-control" rows="2" required>{{ $form->address }}</textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>