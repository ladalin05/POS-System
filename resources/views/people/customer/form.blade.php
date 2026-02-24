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
            
            /* Card Styling */
            .card {
                border-radius: 1rem;
                transition: transform 0.2s ease;
            }
            
            .shadow-sm {
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.04) !important;
            }

            /* Form Elements */
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

            /* File Upload Area */
            .border-dashed {
                border: 2px dashed #e2e8f0;
                transition: all 0.2s;
            }

            .border-dashed:hover {
                border-color: var(--bs-primary);
                background-color: #f1f5f9;
            }

            .cursor-pointer { cursor: pointer; }

            /* Buttons */
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
            <x-basic.form action="{{ route('people.customers.add', $form?->id) }}" enctype="multipart/form-data" novalidate>
                <div class="row g-4">
                    {{-- Left Column: Primary Info --}}
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="section-title mb-4">
                                    <h6 class="fw-bold text-dark mb-1">Company Profile</h6>
                                    <p class="text-muted small">Enter the legal identification and primary names.</p>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-basic.form.text label="{{ __('global.company') }}" name="company" value="{{ $form?->company }}" :required="true" placeholder="e.g. Acme Corp" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-basic.form.text label="{{ __('global.name') }}" name="name" value="{{ $form?->name }}" :required="true" placeholder="Full contact name" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-basic.form.select label="Customer Group" name="customer_group_id" :options="$group_customer" :selected="$form?->customer_group_id" :required="true" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-basic.form.text label="System Code" name="code" value="{{ $form?->code }}" :required="true" placeholder="CUST-001" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-basic.form.text label="VAT Number" name="vat_number" value="{{ $form?->vat_number }}" placeholder="Tax ID" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="section-title mb-4">
                                    <h6 class="fw-bold text-dark mb-1">Location Details</h6>
                                    <p class="text-muted small">Where is this client based?</p>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <x-basic.form.textarea label="Street Address" name="address" value="{{ $form?->address }}" :required="true" rows="2" />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.text label="City" name="city" value="{{ $form?->city }}" />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.text label="State" name="state" value="{{ $form?->state }}" />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.text label="Zip" name="postal_code" value="{{ $form?->postal_code }}" />
                                    </div>
                                    <div class="col-md-3">
                                        <x-basic.form.text label="Country" name="country" value="{{ $form?->country }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Secondary Info & Actions --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="section-title mb-4">
                                    <h6 class="fw-bold text-dark mb-1">Contact & Finance</h6>
                                </div>
                                <div class="vstack gap-3">
                                    <x-basic.form.text label="Phone Number" name="phone" value="{{ $form?->phone }}" :required="true" />
                                    <x-basic.form.text label="Email" name="email_address" value="{{ $form?->email_address }}" type="email" />
                                    <hr class="my-2 opacity-50">
                                    <x-basic.form.text label="Credit Days" name="credit_day" value="{{ $form?->credit_day }}" type="number" />
                                    <x-basic.form.text label="Limit Amount" name="credit_amount" value="{{ $form?->credit_amount }}" type="number" />
                                    
                                    <div>
                                        <label class="form-label fw-medium small">Attachment</label>
                                        <div class="upload-zone rounded-3 border-dashed p-3 text-center">
                                            <input type="file" name="attachment" id="attachment" class="d-none">
                                            <label for="attachment" class="mb-0 cursor-pointer">
                                                <i class="fa-solid fa-cloud-arrow-up text-primary mb-2 fs-4"></i>
                                                <p class="small text-muted mb-0">Click to upload files</p>
                                            </label>
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