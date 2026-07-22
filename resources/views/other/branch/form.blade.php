<form method="POST" action="{{ $action }}" id="baseUnitForm" enctype="multipart/form-data" class="ajax-form">

    {{-- Logo Section - Using new uploader component --}}
    <div class="row mb-4 align-items-center bg-light rounded p-3 mx-0 border-dashed">
        <div class="col-md-8">
            <h6 class="fw-bold mb-1"><i class="ph ph-image me-2"></i>{{ __('global.logo') }}</h6>
            <p class="text-muted small mb-2">Upload your warehouse logo. Recommended size: 250x120px.</p>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <x-basic.uploader
                input-name="logo"
                :url="old('logo', $form->logo ?? '')"
                :path="old('logo', $form->logo ?? '')"
                accept="image/*"
                layout="block"
                width="170px"
                height="170px"
                shape="rounded"
                folder="other/branch"
                caption="PNG or JPG, up to 2MB"
            />
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
                    <x-forms.input
                        name="name"
                        label="{{ __('global.name') }}"
                        type="text"
                        :value="old('name', $form->name ?? '')"
                        placeholder="Enter Branch Name"
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-forms.input
                        name="name_kh"
                        label="{{ __('global.name') }} (Khmer)"
                        type="text"
                        :value="old('name_kh', $form->name_kh ?? '')"
                        placeholder="Enter Branch Name"
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-forms.input
                        name="email"
                        label="{{ __('global.email_address') }}"
                        type="email"
                        :value="old('email', $form->email ?? '')"
                        placeholder="Enter Email Address"
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-forms.input
                        name="prefix"
                        label="{{ __('global.prefix') }}"
                        type="text"
                        :value="old('prefix', $form->prefix ?? '')"
                        placeholder="Enter Prefix"
                    />
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
                    <x-forms.input
                        name="phone"
                        label="{{ __('global.phone') }}"
                        type="text"
                        :value="old('phone', $form->phone ?? '')"
                        placeholder="Enter Phone Number"
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-forms.input
                        name="phone_kh"
                        label="{{ __('global.phone') }} (Khmer)"
                        type="text"
                        :value="old('phone_kh', $form->phone_kh ?? '')"
                        placeholder="Enter Phone Number"
                    />
                </div>
                <div class="col-md-6">
                    <x-forms.input
                        name="address"
                        label="{{ __('global.address') }}"
                        type="text"
                        :value="old('address', $form->address ?? '')"
                        placeholder="Enter Address"
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-forms.input
                        name="address_kh"
                        label="{{ __('global.address') }} (Khmer)"
                        type="text"
                        :value="old('address_kh', $form->address_kh ?? '')"
                        placeholder="Enter Address"
                    />
                </div>
                <div class="col-md-3">
                    <x-forms.input
                        name="city"
                        label="{{ __('global.city') }}"
                        type="text"
                        :value="old('city', $form->city ?? '')"
                        placeholder="Enter City"
                        required
                    />
                </div>
                <div class="col-md-3">
                    <x-forms.input
                        name="city_kh"
                        label="{{ __('global.city') }} (KH)"
                        type="text"
                        :value="old('city_kh', $form->city_kh ?? '')"
                        placeholder="Enter City"
                    />
                </div>
                <div class="col-md-3">
                    <x-forms.input
                        name="country"
                        label="{{ __('global.country') }}"
                        type="text"
                        :value="old('country', $form->country ?? '')"
                        placeholder="Enter Country"
                    />
                </div>
                <div class="col-md-3">
                    <x-forms.input
                        name="country_kh"
                        label="{{ __('global.country') }} (KH)"
                        type="text"
                        :value="old('country_kh', $form->country_kh ?? '')"
                        placeholder="Enter Country"
                    />
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
                    <x-forms.input
                        name="vat_number"
                        label="{{ __('global.vat_number') }}"
                        type="text"
                        :value="old('vat_number', $form->vat_number ?? '')"
                        placeholder="Enter VAT Number"
                    />
                </div>
                <div class="col-md-6">
                    <x-forms.input
                        name="vat_number_kh"
                        label="{{ __('global.vat_number') }} (Khmer)"
                        type="text"
                        :value="old('vat_number_kh', $form->vat_number_kh ?? '')"
                        placeholder="Enter VAT Number"
                    />
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
</form>

<style>
    .bg-primary-light { background: rgba(13, 110, 253, 0.1); }
    .bg-success-light { background: rgba(25, 135, 84, 0.1); }
    .bg-warning-light { background: rgba(255, 193, 7, 0.1); }
    .border-dashed { border: 2px dashed #dee2e6 !important; }
    .modal-body { max-height: 80vh; overflow-y: auto; }
</style>