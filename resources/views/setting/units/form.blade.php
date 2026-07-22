
<style>

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .form-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25 red rgba(13, 110, 253, 0.15);
    }
    .btn-save {
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
</style>

<div class="container py-2">
    <div class="row justify-content-center">
        <form method="POST" action="{{ $action }}" id="unitForm" enctype="multipart/form-data" class="ajax-form">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input
                        label="Code"
                        name="code"
                        type="text"
                        :value="old('code', $form->code ?? '')"
                        placeholder="Enter product name"
                        required
                    />
                </div>

                <div class="col-md-6">
                    <x-forms.input
                        label="Name"
                        name="name"
                        type="text"
                        :value="old('name', $form->name ?? '')"
                        placeholder="Enter product name"
                        required
                    />
                </div>

                <div class="col-md-12">
                    <x-forms.select
                        name="base_unit"
                        label="Base Unit"
                        :options="getUnit()"
                        :value="($form->base_unit ?? '')"
                        placeholder="Select Base Unit"
                        required
                    />
                </div>

                <div class="col-md-4">
                    <x-forms.select
                        name="operator"
                        label="Operator"
                        :options="[ '*' => 'Multiply (*)', '/' => 'Divide (/)', '+' => 'Add (+)', '-' => 'Subtract (-)']"
                        :value="old('operator', $form->operator ?? '')"
                        placeholder="Select Operator"
                    />
                </div>

                <div class="col-md-4">
                    <x-forms.input
                        label="Unit Value"
                        name="unit_value"
                        type="text"
                        :value="old('unit_value', $form->unit_value ?? '')"
                        placeholder="0.00"
                    />
                </div>

                <div class="col-md-4">
                    <x-forms.input
                        label="Operation Value"
                        name="operation_value"
                        type="text"
                        :value="old('operation_value', $form->operation_value ?? '')"
                        placeholder="0.00"
                    />
                </div>

                <div class="col-12 mt-4 text-end">
                    <hr class="text-muted mb-4">
                    <button type="button" class="btn btn-light btn-save me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-save shadow-sm">
                        <i class="bi bi-check-lg"></i> Save Unit
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>