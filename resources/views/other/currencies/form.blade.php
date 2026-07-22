
<form method="POST" action="{{ $action }}" id="baseUnitForm" enctype="multipart/form-data" class="ajax-form">
    <div class="row g-3">
        <div class="col-md-5">
            <div class="form-group">
                <x-forms.input
                    label="{{ __('global.code') }}"
                    name="code"
                    type="text"
                    :value="old('code', $form->code ?? '')"
                    placeholder="e.g. USD"
                    required
                />
            </div>
        </div>

        <div class="col-md-7">
            <div class="form-group">
                <x-forms.input
                    label="{{ __('global.exchange_rate') }}"
                    name="rate"
                    type="number"
                    step="0.00001"
                    :value="old('rate', $form->rate ?? '')"
                    placeholder="0.0000"
                    required />
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                <x-forms.input
                    label="{{ __('global.name') }}"
                    name="name"
                    type="text"
                    :value="old('name', $form->name ?? '')"
                    placeholder="Enter Currency Name"
                    required
                />
            </div>
        </div>
    </div>

    <div class="modal-footer border-top-0 mt-2">
        <button type="button" class="btn btn-link text-muted fw-semibold" data-bs-dismiss="modal">
            {{ __('global.close') }}
        </button>
        <button type="submit" class="btn btn-primary px-4 shadow-sm">
            <i class="ph ph-floppy-disk me-2"></i> {{ __('global.save') }}
        </button>
    </div>
</form>