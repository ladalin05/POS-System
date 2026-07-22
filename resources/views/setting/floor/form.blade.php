<div class="container mt-4">
    <form method="POST" action="{{ $action }}" id="floorForm" enctype="multipart/form-data" class="ajax-form">
        
        <div class="mb-3">
            <x-forms.input
                label="Name"
                name="name"
                type="text"
                :value="old('name', $form->name ?? '')"
                placeholder="Enter Floor Name"
                required
            />
        </div>

        <div class="col-12 mt-4 text-end">
            <hr class="text-muted mb-4">
            <button type="button" class="btn btn-light btn-save me-2">Cancel</button>
            <button type="submit" class="btn btn-primary btn-save shadow-sm">
                <i class="bi bi-check-lg"></i> Save Floor
            </button>
        </div>
    </form>
</div>