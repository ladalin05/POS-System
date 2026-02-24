<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">Create Supplier</h2>
                    <p class="text-muted mb-0 small">Create new Supplier</p>
                </div>
            </div>
        </x-slot>
        
        <div class="header-actions me-2">
            <a href="{{ route('people.suppliers.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content mt-4">
        <x-basic.form action="{{ route('people.suppliers.save', $form?->id) }}" novalidate>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0">General Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-basic.form.text label="{{ __('global.code') }}" name="code"
                                        value="{{ $form?->code }}" :required="true" placeholder="CUST-001" />
                                </div>
                                <div class="col-md-8">
                                    <x-basic.form.text label="{{ __('global.company') }}" name="company"
                                        value="{{ $form?->company }}" :required="true" />
                                </div>
                                <div class="col-md-6">
                                    <x-basic.form.text label="{{ __('global.name') }}" name="name"
                                        value="{{ $form?->name }}" :required="true" />
                                </div>
                                <div class="col-md-6">
                                    <x-basic.form.text label="{{ __('global.email_address') }}" name="email_address"
                                        value="{{ $form?->email_address }}" :required="true" />
                                </div>
                                <div class="col-md-6">
                                    <x-basic.form.text label="{{ __('global.phone') }}" name="phone"
                                        value="{{ $form?->phone }}" :required="true" />
                                </div>
                                <div class="col-md-6">
                                    <x-basic.form.text label="{{ __('global.vat_number') }}" name="vat_number"
                                        value="{{ $form?->vat_number }}" :required="true" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0">Detailed Address</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <x-basic.form.textarea label="{{ __('global.address') }}" name="address"
                                        value="{{ $form?->address }}" :required="true" rows="3" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0">Location Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-column gap-3">
                                <x-basic.form.text label="{{ __('global.city') }}" name="city"
                                    value="{{ $form?->city }}" :required="true" />
                                
                                <x-basic.form.text label="{{ __('global.state') }}" name="state"
                                    value="{{ $form?->state }}" :required="true" />

                                <x-basic.form.text label="{{ __('global.postal_code') }}" name="postal_code"
                                    value="{{ $form?->postal_code }}" :required="true" />

                                <x-basic.form.text label="{{ __('global.country') }}" name="country"
                                    value="{{ $form?->country }}" :required="true" />
                            </div>

                            <hr class="my-4 opacity-50">

                            <div class="d-grid gap-2">
                                <a href="{{ route('people.suppliers.index') }}" class="btn btn-light btn-lg text-muted fw-semibold">
                                    {{ __('global.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary px-3 fw-bold shadow-sm">
                                    <i class="ph ph-floppy-disk me-2"></i> Save Customer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-basic.form>
    </div>
</x-app-layout>

<style>
    /* Add these to your CSS for that extra polish */
    .card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .btn-primary { background: linear-gradient(45deg, #4e73df, #224abe); border: none; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35); }
    .form-control:focus { border-color: #4e73df; box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1); }
</style>