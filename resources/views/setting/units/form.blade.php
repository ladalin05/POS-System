<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Custom touches to make it "Beautiful" */
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
        <form method="POST" action="{{ $action }}" id="unitForm" enctype="multipart/form-data" onsubmit="handleUnitSubmit(event)">
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g. KG" maxlength="55" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Kilogram" maxlength="55" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Base Unit</label>
                    <select name="base_unit" class="form-select">
                        <option selected disabled>Select Base Unit</option>
                        <option value="1">Unit 1</option>
                        <option value="2">Unit 2</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Operator</label>
                    <select name="operator" class="form-select">
                        <option value="">Select Operator</option>
                        <option value="*">Multiply (*)</option>
                        <option value="/">Divide (/)</option>
                        <option value="+">Add (+)</option>
                        <option value="-">Subtract (-)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Unit Value</label>
                    <input type="text" name="unit_value" class="form-control" placeholder="0.00" maxlength="55">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Operation Value</label>
                    <input type="text" name="operation_value" class="form-control" placeholder="0.00" maxlength="55">
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

<script>
    function handleUnitSubmit(e) {
        e.preventDefault();
        ajaxSubmit('#unitForm');
    }
</script>