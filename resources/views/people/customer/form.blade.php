<x-app-layout>

    @push('css')
        <style>
            :root {
                --bs-primary: #4361ee;
                --bs-soft-primary: #eef2ff;
            }

            body {
                background-color: #f8fafc;
                color: #334155;
            }

            .bg-soft-primary { background-color: var(--bs-soft-primary); }

            .card {
                border-radius: 1rem;
                transition: transform 0.2s ease;
            }

            .shadow-sm {
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.04) !important;
            }

            .form-label {
                font-weight: 600;
                font-size: 0.8125rem;
                color: #475569;
                margin-bottom: 0.4rem;
            }

            .form-control, .form-select {
                border: 1px solid #e2e8f0;
                border-radius: 0.625rem;
                padding: 0.6rem 0.875rem;
                font-size: 0.9rem;
                transition: all 0.2s;
            }

            .form-control:focus {
                border-color: var(--bs-primary);
                box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            }

            .border-dashed {
                border: 2px dashed #e2e8f0;
                transition: all 0.2s;
            }

            .border-dashed:hover {
                border-color: var(--bs-primary);
                background-color: #f1f5f9;
            }

            .cursor-pointer { cursor: pointer; }

            .btn-white {
                background: #fff;
                color: var(--bs-primary);
                border: none;
            }

            .btn-white:hover {
                background: #f8fafc;
                transform: translateY(-1px);
            }

            .section-title h6 {
                letter-spacing: -0.01em;
            }

            .section-icon {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: var(--bs-soft-primary);
                color: var(--bs-primary);
                flex-shrink: 0;
            }
        </style>
    @endpush

    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">Create Customer</h2>
                    <p class="text-muted mb-0 small">Create new Customer</p>
                </div>
            </div>
        </x-slot>

        <div class="header-actions me-2">
            <a href="{{ route('people.customers.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content pb-5">
        <div class="container-fluid">
            <x-basic.form action="{{ $action }}" enctype="multipart/form-data" novalidate>
                <div class="row g-4">

                    {{-- Left Column: Primary Info --}}
                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="section-title d-flex align-items-center gap-3 mb-4">
                                    <span class="section-icon"><i class="ph ph-buildings fs-5"></i></span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Company Profile</h6>
                                        <p class="text-muted small mb-0">Enter the legal identification and primary names.</p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-forms.input
                                            label="{{ __('global.company') }}"
                                            name="company"
                                            id="company"
                                            type="text"
                                            :value="old('company', $form->company ?? '')"
                                            placeholder="e.g. Acme Corp"
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
                                            placeholder="Full contact name"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-forms.select
                                            name="customer_group_id"
                                            label="Customer Group"
                                            :options="$group_customer"
                                            placeholder="Select Customer Group"
                                            :value="old('customer_group_id', $form->customer_group_id ?? '')"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-forms.input
                                            label="System Code"
                                            name="code"
                                            id="code"
                                            type="text"
                                            :value="old('code', $form->code ?? '')"
                                            placeholder="CUST-001"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-4">
                                        <x-forms.input
                                            label="VAT Number"
                                            name="vat_number"
                                            id="vat_number"
                                            type="text"
                                            :value="old('vat_number', $form->vat_number ?? '')"
                                            placeholder="Tax ID"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="section-title d-flex align-items-center gap-3 mb-4">
                                    <span class="section-icon"><i class="ph ph-map-pin fs-5"></i></span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Location Details</h6>
                                        <p class="text-muted small mb-0">Where is this client based?</p>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <x-basic.form.textarea
                                            label="Street Address"
                                            name="address"
                                            id="address"
                                            :value="old('address', $form->address ?? '')"
                                            rows="2"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-forms.input
                                            label="City"
                                            name="city"
                                            id="city"
                                            type="text"
                                            :value="old('city', $form->city ?? '')"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-forms.input
                                            label="State"
                                            name="state"
                                            id="state"
                                            type="text"
                                            :value="old('state', $form->state ?? '')"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-forms.input
                                            label="Zip"
                                            name="postal_code"
                                            id="postal_code"
                                            type="text"
                                            :value="old('postal_code', $form->postal_code ?? '')"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <x-forms.input
                                            label="Country"
                                            name="country"
                                            id="country"
                                            type="text"
                                            :value="old('country', $form->country ?? '')"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Secondary Info & Actions --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                            <div class="card-body p-4">
                                <div class="section-title d-flex align-items-center gap-3 mb-4">
                                    <span class="section-icon"><i class="ph ph-phone fs-5"></i></span>
                                    <h6 class="fw-bold text-dark mb-0">Contact & Finance</h6>
                                </div>
                                <div class="vstack gap-3">
                                    <x-forms.input
                                        label="Phone Number"
                                        name="phone"
                                        id="phone"
                                        type="text"
                                        :value="old('phone', $form->phone ?? '')"
                                        required
                                    />
                                    <x-forms.input
                                        label="Email"
                                        name="email_address"
                                        id="email_address"
                                        type="email"
                                        :value="old('email_address', $form->email_address ?? '')"
                                    />

                                    <hr class="my-2 opacity-50">

                                    <x-forms.input
                                        label="Credit Days"
                                        name="credit_day"
                                        id="credit_day"
                                        type="number"
                                        :value="old('credit_day', $form->credit_day ?? '')"
                                        placeholder="0"
                                    />
                                    <x-forms.input
                                        label="Limit Amount"
                                        name="credit_amount"
                                        id="credit_amount"
                                        type="number"
                                        step="0.01"
                                        :value="old('credit_amount', $form->credit_amount ?? '')"
                                        placeholder="0.00"
                                    />

                                    <div class="form-check form-switch pt-1">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                            name="is_active" value="1"
                                            {{ old('is_active', $form?->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold small" for="is_active">Active Customer</label>
                                    </div>

                                    <hr class="my-2 opacity-50">

                                    <div>
                                        <label class="form-label fw-medium small">Attachment</label>
                                        <div class="w-100 d-flex justify-content-center">
                                            <x-basic.uploader
                                                inputName="attachment"
                                                :url="$form?->attachment ? asset($form->attachment) . '?v=' . ($form->updated_at?->timestamp ?? time()) : ''"
                                                :path="$form?->attachment ?? ''"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                layout="block"
                                                width="200px"
                                                height="100px"
                                                shape="square"
                                                folder="customers"
                                                :filenameHint="$form?->code ?? ''"
                                                caption="PDF, JPG or PNG"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card custom-card">
                            <div class="card-body p-3 text-end">
                                <a href="{{ route('people.customers.index') }}" class="btn btn-light px-4 me-2">
                                    {{ __('global.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary px-3 fw-bold shadow-sm">
                                    <i class="ph ph-floppy-disk me-2"></i> Save Customer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-basic.form>
        </div>
    </div>
</x-app-layout>