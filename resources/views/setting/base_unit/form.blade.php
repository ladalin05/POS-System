<div class="container mt-4">
    <form method="POST" action="{{ $action }}" id="baseUnitForm" enctype="multipart/form-data" class="ajax-form">
        <div class="mb-3">
            <x-forms.select
                name="from_unit_id"
                label="Convert From"
                :options="getUnit()"
                :value="old('from_unit_id', $form->from_unit_id ?? '')"
                placeholder="Select Unit"
            />
        </div>

        <div class="mb-3">
            <x-forms.select
                name="to_unit_id"
                label="Convert To"
                :options="getUnit()"
                :value="old('to_unit_id', $form->to_unit_id ?? '')"
                placeholder="Select Unit"
            />
        </div>

        <div class="mb-3">
            <x-forms.input
                label="Numerator"
                name="numerator"
                type="number"
                :value="old('numerator', $form->numerator ?? '')"
                placeholder="Enter Numerator"
                required
            />
        </div>

        <div class="mb-3">
            <x-forms.select
                name="is_active"
                label="Status"
                :options="[ '1' => 'Active', '0' => 'Inactive']"
                :value="old('is_active', $form->is_active ?? '')"
                placeholder="Select Status"
            />
        </div>

        <div class="col-12 mt-4 text-end">
            <hr class="text-muted mb-4">
            <button type="button" class="btn btn-light btn-save me-2">Cancel</button>
            <button type="submit" class="btn btn-primary btn-save shadow-sm">
                <i class="bi bi-check-lg"></i> Save Unit
            </button>
        </div>
    </form>
</div>