<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">Create Supplier</h2>
                    <p class="text-muted mb-0 small">Add a new supplier to your directory</p>
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
        <x-basic.form action="{{ $action }}" novalidate>
            <div class="row g-4">

                {{-- ── Main column ─────────────────────────────────────── --}}
                <div class="col-lg-8">

                    {{-- General Information --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center gap-2">
                            <span class="badge bg-primary-light text-primary p-2"><i class="ph ph-identification-card fs-5"></i></span>
                            <h5 class="fw-bold mb-0">General Information</h5>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-forms.input
                                        label="{{ __('global.code') }}"
                                        name="code"
                                        id="code"
                                        type="text"
                                        :value="old('code', $form->code ?? '')"
                                        placeholder="SUP-001"
                                        required
                                    />
                                </div>
                                <div class="col-md-8">
                                    <x-forms.input
                                        label="{{ __('global.company') }}"
                                        name="company"
                                        id="company"
                                        type="text"
                                        :value="old('company', $form->company ?? '')"
                                        placeholder="Company name"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input
                                        label="{{ __('global.name') }}"
                                        name="name"
                                        id="name"
                                        type="text"
                                        :value="old('name', $form->name ?? '')"
                                        placeholder="Contact person name"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input
                                        label="{{ __('global.email_address') }}"
                                        name="email_address"
                                        id="email_address"
                                        type="email"
                                        :value="old('email_address', $form->email_address ?? '')"
                                        placeholder="Enter Email Address"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input
                                        label="{{ __('global.phone') }}"
                                        name="phone"
                                        id="phone"
                                        type="text"
                                        :value="old('phone', $form->phone ?? '')"
                                        placeholder="Enter Phone Number"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input
                                        label="{{ __('global.vat_number') }}"
                                        name="vat_number"
                                        id="vat_number"
                                        type="text"
                                        :value="old('vat_number', $form->vat_number ?? '')"
                                        placeholder="Enter VAT Number"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Business Terms --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center gap-2">
                            <span class="badge bg-success-light text-success p-2"><i class="ph ph-handshake fs-5"></i></span>
                            <h5 class="fw-bold mb-0">Business Terms</h5>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-forms.select
                                        name="payment_terms"
                                        label="{{ __('global.payment_terms') ?? 'Payment Terms' }}"
                                        :options="[
                                            'due_on_receipt' => 'Due on Receipt',
                                            'net_15' => 'Net 15',
                                            'net_30' => 'Net 30',
                                            'net_60' => 'Net 60',
                                        ]"
                                        placeholder="Select Terms"
                                        :value="old('payment_terms', $form->payment_terms ?? '')"
                                    />
                                </div>
                                <div class="col-md-4">
                                    <x-forms.select
                                        name="currency_id"
                                        label="{{ __('global.currency') ?? 'Currency' }}"
                                        :options="getCurrencies()"
                                        placeholder="Select Currency"
                                        :value="old('currency_id', $form->currency_id ?? '')"
                                    />
                                </div>
                                <div class="col-md-4">
                                    <x-forms.input
                                        label="{{ __('global.credit_limit') ?? 'Credit Limit' }}"
                                        name="credit_limit"
                                        id="credit_limit"
                                        type="number"
                                        step="0.01"
                                        :value="old('credit_limit', $form->credit_limit ?? '')"
                                        placeholder="0.00"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Address --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center gap-2">
                            <span class="badge bg-warning-light text-warning p-2"><i class="ph ph-map-pin fs-5"></i></span>
                            <h5 class="fw-bold mb-0">Detailed Address</h5>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <x-basic.form.textarea
                                        label="{{ __('global.address') }}"
                                        name="address"
                                        id="address"
                                        :value="old('address', $form->address ?? '')"
                                        rows="3"
                                        placeholder="Street, building, unit..."
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Sidebar ─────────────────────────────────────────── --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">

                        {{-- Supplier identity mini-header --}}
                        <div class="card-body p-4 pb-3 text-center border-bottom">
                            <div class="uploader-avatar d-inline-block mb-2">
                                <x-basic.uploader
                                    inputName="logo"
                                    :url="$form?->logo ? asset($form->logo) . '?v=' . ($form->updated_at?->timestamp ?? time()) : ''"
                                    :path="$form?->logo ?? ''"
                                    accept="image/*"
                                    layout="block"
                                    width="88px"
                                    height="88px"
                                    shape="circle"
                                    folder="suppliers"
                                    :filenameHint="$form?->code ?? ''"
                                />
                            </div>
                            <div class="small text-muted">Supplier logo (optional)</div>
                        </div>

                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center gap-2">
                            <span class="badge bg-primary-light text-primary p-2"><i class="ph ph-globe fs-5"></i></span>
                            <h5 class="fw-bold mb-0">Location Details</h5>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="d-flex flex-column gap-3">
                                <x-forms.input
                                    label="{{ __('global.city') }}"
                                    name="city"
                                    id="city"
                                    type="text"
                                    :value="old('city', $form->city ?? '')"
                                    placeholder="Enter City"
                                    required
                                />

                                <x-forms.input
                                    label="{{ __('global.state') }}"
                                    name="state"
                                    id="state"
                                    type="text"
                                    :value="old('state', $form->state ?? '')"
                                    placeholder="Enter State"
                                    required
                                />

                                <x-forms.input
                                    label="{{ __('global.postal_code') }}"
                                    name="postal_code"
                                    id="postal_code"
                                    type="text"
                                    :value="old('postal_code', $form->postal_code ?? '')"
                                    placeholder="Enter Postal Code"
                                    required
                                />

                                <x-forms.input
                                    label="{{ __('global.country') }}"
                                    name="country"
                                    id="country"
                                    type="text"
                                    :value="old('country', $form->country ?? '')"
                                    placeholder="Enter Country"
                                    required
                                />

                                <div class="form-check form-switch pt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                        name="is_active" value="1"
                                        {{ old('is_active', $form?->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">Active Supplier</label>
                                </div>
                            </div>

                            <hr class="my-4 opacity-50">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary px-3 fw-bold shadow-sm">
                                    <i class="ph ph-floppy-disk me-2"></i> Save Supplier
                                </button>
                                <a href="{{ route('people.suppliers.index') }}" class="btn btn-light text-muted fw-semibold">
                                    {{ __('global.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-basic.form>
    </div>
</x-app-layout>

<style>
    .bg-primary-light { background: rgba(78, 115, 223, 0.1); }
    .bg-success-light { background: rgba(25, 135, 84, 0.1); }
    .bg-warning-light { background: rgba(255, 193, 7, 0.1); }

    .card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .btn-primary { background: linear-gradient(45deg, #4e73df, #224abe); border: none; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35); }
    .form-control:focus { border-color: #4e73df; box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1); }
</style>