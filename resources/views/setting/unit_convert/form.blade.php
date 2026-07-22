<div class="container mt-4">
    <form method="POST" action="{{ $action }}" id="unitConvertForm" enctype="multipart/form-data" class="ajax-form">
        @csrf
        
        <div class="mb-3">
            <x-forms.select
                name="unit_from_id"
                label="Convert From"
                :options="getUnit()"
                :value="old('unit_from_id', $form->unit_from_id ?? '')"
                placeholder="Select Unit"
            />
        </div>

        <div class="mb-3">
            <x-forms.select
                name="unit_to_id"
                label="Convert To"
                :options="getUnit()"
                :value="old('unit_to_id', $form->unit_to_id ?? '')"
                placeholder="Select Unit"
            />
        </div>

        <div class="mb-3">
            <x-forms.select
                name="operator"
                label="Operator"
                :options="[ '*' => 'Multiply (*)', '/' => 'Divide (/)', '+' => 'Add (+)', '-' => 'Subtract (-)']"
                :value="old('operator', $form->operator ?? '')"
                placeholder="Select Operator"
            />
        </div>

        <div class="mb-3">
            <x-forms.input
                label="Numerator"
                name="numerator"
                type="text"
                :value="old('numerator', $form->numerator ?? '')"
                placeholder="Enter Numerator"
                required
            />
        </div>

        <div class="mb-3">
            <x-forms.input
                label="Name"
                name="name"
                type="text"
                :value="old('name', $form->name ?? '')"
                placeholder="Enter Name"
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