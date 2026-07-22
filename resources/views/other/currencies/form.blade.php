<form method="POST" action="{{ $action }}" id="baseUnitForm" enctype="multipart/form-data" class="ajax-form">

    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-primary-light text-primary p-2"><i class="ph ph-currency-circle-dollar fs-5"></i></span>
        <h6 class="mb-0 fw-bold">{{ isset($form->id) ? 'Edit Currency' : 'New Currency' }}</h6>
    </div>
    <hr class="mt-0 mb-3 opacity-10">

    <div class="row g-3">
        <div class="col-md-4">
            <x-forms.input
                label="{{ __('global.code') }}"
                name="code"
                type="text"
                :value="old('code', $form->code ?? '')"
                placeholder="e.g. USD"
                maxlength="3"
                style="text-transform:uppercase"
                required
            />
            <small class="text-muted">3-letter ISO code</small>
        </div>

        <div class="col-md-8">
            <x-forms.input
                label="{{ __('global.name') }}"
                name="name"
                type="text"
                :value="old('name', $form->name ?? '')"
                placeholder="Enter Currency Name"
                required
            />
        </div>

        <div class="col-md-4">
            <x-forms.input
                label="{{ __('global.symbol') ?? 'Symbol' }}"
                name="symbol"
                type="text"
                :value="old('symbol', $form->symbol ?? '')"
                placeholder="e.g. $"
                maxlength="5"
            />
        </div>

        <div class="col-md-8">
            <x-forms.input
                label="{{ __('global.exchange_rate') }}"
                name="rate"
                id="rate-input"
                type="number"
                step="0.00001"
                :value="old('rate', $form->rate ?? '')"
                placeholder="0.00000"
                required
            />
            <small class="text-muted">Value relative to your base currency</small>
        </div>

        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center gap-2 mb-0 py-2 px-3" id="rate-preview" style="display:none !important;">
                <i class="ph ph-info text-primary"></i>
                <span class="small">1 <strong id="preview-code">---</strong> = <span id="preview-rate">0.00000</span> (base currency)</span>
            </div>
        </div>
    </div>

    <div class="modal-footer border-top-0 mt-3 px-0 pb-0">
        <button type="button" class="btn btn-link text-muted fw-semibold" data-bs-dismiss="modal">
            {{ __('global.close') }}
        </button>
        <button type="submit" class="btn btn-primary px-4 shadow-sm">
            <i class="ph ph-floppy-disk me-2"></i> {{ __('global.save') }}
        </button>
    </div>
</form>

<style>
    .bg-primary-light { background: rgba(13, 110, 253, 0.1); }
</style>

<script>
    (function () {
        const codeInput = document.querySelector('input[name="code"]');
        const rateInput = document.getElementById('rate-input');
        const preview = document.getElementById('rate-preview');
        const previewCode = document.getElementById('preview-code');
        const previewRate = document.getElementById('preview-rate');

        function updatePreview() {
            const code = (codeInput?.value || '').toUpperCase();
            const rate = parseFloat(rateInput?.value);

            if (code && !isNaN(rate) && rate > 0) {
                previewCode.textContent = code;
                previewRate.textContent = rate.toFixed(5);
                preview.style.setProperty('display', 'flex', 'important');
            } else {
                preview.style.setProperty('display', 'none', 'important');
            }
        }

        codeInput?.addEventListener('input', updatePreview);
        rateInput?.addEventListener('input', updatePreview);
        updatePreview();
    })();
</script>