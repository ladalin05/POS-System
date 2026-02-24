
    {{-- Logo Section - Styled as a Profile Upload --}}
    <div class="row mb-4 align-items-center bg-light rounded p-3 mx-0 border-dashed">
        <div class="col-md-8">
            <h6 class="fw-bold mb-1"><i class="ph ph-image me-2"></i>{{ __('global.logo') }}</h6>
            <p class="text-muted small mb-2">Upload your warehouse logo. Recommended size: 250x120px.</p>
            <x-basic.form.file id="logo-input" name="logo" accept="image/*" />
        </div>
        <div class="col-md-4 text-center">
            <div class="preview-container shadow-sm bg-white rounded d-flex align-items-center justify-content-center p-2" style="height: 120px; border: 1px solid #dee2e6;">
                <img id="logo-preview"
                    src="{{ $form->logo ? asset($form->logo) . '?v=' . ($form->updated_at?->timestamp ?? time()) : asset('assets/images/no_image.png') }}"
                    alt="Logo preview"
                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
            </div>
        </div>
    </div>

    {{-- Form Sections --}}
    <div class="row g-2">
        {{-- Section 1: Basic Information --}}
        <div class="col-12">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-light text-primary p-2"><i class="ph ph-identification-card fs-5"></i></span>
                <h6 class="mb-0 fw-bold">General Information</h6>
            </div>
            <hr class="mt-0 mb-3 opacity-10">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.name') }}" name="name" :value="old('name', $form->name ?? '')" required />
                </div>
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.name') }} (Khmer)" name="name_kh" :value="old('name_kh', $form->name_kh ?? '')" required />
                </div>
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.email_address') }}" name="email" type="email" :value="old('email', $form->email ?? '')" required />
                </div>
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.prefix') }}" name="prefix" :value="old('prefix', $form->prefix ?? '')" placeholder="e.g., WH-01" />
                </div>
            </div>
        </div>

        {{-- Section 2: Contact & Location --}}
        <div class="col-12 mt-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-success-light text-success p-2"><i class="ph ph-map-pin fs-5"></i></span>
                <h6 class="mb-0 fw-bold">Contact & Location</h6>
            </div>
            <hr class="mt-0 mb-3 opacity-10">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.phone') }}" name="phone" :value="old('phone', $form->phone ?? '')" required />
                </div>
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.phone') }} (Khmer)" name="phone_kh" :value="old('phone_kh', $form->phone_kh ?? '')" />
                </div>
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.address') }}" name="address" :value="old('address', $form->address ?? '')" required />
                </div>
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.address') }} (Khmer)" name="address_kh" :value="old('address_kh', $form->address_kh ?? '')" />
                </div>
                <div class="col-md-3">
                    <x-basic.form.text label="{{ __('global.city') }}" name="city" :value="old('city', $form->city ?? '')" required />
                </div>
                <div class="col-md-3">
                    <x-basic.form.text label="{{ __('global.city') }} (KH)" name="city_kh" :value="old('city_kh', $form->city_kh ?? '')" />
                </div>
                <div class="col-md-3">
                    <x-basic.form.text label="{{ __('global.country') }}" name="country" :value="old('country', $form->country ?? '')" />
                </div>
                <div class="col-md-3">
                    <x-basic.form.text label="{{ __('global.country') }} (KH)" name="country_kh" :value="old('country_kh', $form->country_kh ?? '')" />
                </div>
            </div>
        </div>

        {{-- Section 3: Tax & Additional info --}}
        <div class="col-12 mt-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-warning-light text-warning p-2"><i class="ph ph-receipt fs-5"></i></span>
                <h6 class="mb-0 fw-bold">Tax & Billing</h6>
            </div>
            <hr class="mt-0 mb-3 opacity-10">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.vat_number') }}" name="vat_number" :value="old('vat_number', $form->vat_number ?? '')" />
                </div>
                <div class="col-md-6">
                    <x-basic.form.text label="{{ __('global.vat_number') }} (Khmer)" name="vat_number_kh" :value="old('vat_number_kh', $form->vat_number_kh ?? '')" />
                </div>
                <div class="col-md-12">
                    <x-basic.form.textarea label="{{ __('global.invoice_footer') }}" name="invoice_footer" :value="old('invoice_footer', $form->invoice_footer ?? '')" rows="2" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 px-4 justify-content-end d-flex gap-2">
        <button type="button" class="btn btn-link text-muted fw-semibold" data-bs-dismiss="modal">{{ __('global.close') }}</button>
        <button type="submit" class="btn btn-primary px-4 shadow-sm">
            <i class="ph ph-floppy-disk me-2"></i> {{ __('global.save') }}
        </button>
    </div>

<style>
    .bg-primary-light { background: rgba(13, 110, 253, 0.1); }
    .bg-success-light { background: rgba(25, 135, 84, 0.1); }
    .bg-warning-light { background: rgba(255, 193, 7, 0.1); }
    .border-dashed { border: 2px dashed #dee2e6 !important; }
    .modal-body { max-height: 80vh; overflow-y: auto; }
</style>

<script>
    // Improved Image Preview logic
    document.getElementById('logo-input')?.addEventListener('change', function (e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                const preview = document.getElementById('logo-preview');
                preview.src = ev.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>