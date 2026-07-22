<div class="container mt-4">
    <form method="POST" action="{{ $action }}" id="roomForm" enctype="multipart/form-data" class="ajax-form">
        
        <div class="mb-3">
            <x-forms.input
                label="Room Code"
                name="code"
                type="text"
                :value="old('code', $form->code ?? '')"
                placeholder="Enter Room Code"
                required
            />
        </div>

        <div class="mb-3">
            <x-forms.input
                label="Room Name"
                name="name"
                type="text"
                :value="old('name', $form->name ?? '')"
                placeholder="Enter Room Name"
                required
            />
        </div>

        <div class="mb-3">
            <x-forms.input
                label="Floor ID"
                name="floor_id"
                type="number"
                :value="old('floor_id', $form->floor_id ?? '')"
                placeholder="Enter Floor ID"
                required
            />
        </div>

        <div class="col-12 mt-4 text-end">
            <hr class="text-muted mb-4">
            <button type="button" class="btn btn-light btn-save me-2">Cancel</button>
            <button type="submit" class="btn btn-primary btn-save shadow-sm">
                <i class="bi bi-check-lg"></i> Save Room
            </button>
        </div>
    </form>
</div>