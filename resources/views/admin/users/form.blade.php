<x-app-layout> 
    @push('css')
    <style>
        .ls-1 { letter-spacing: 0.5px; }
        .fs-7 { font-size: 0.78rem; }
        
        /* Modern Section Divider */
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        .section-title .line {
            flex-grow: 1;
            height: 1px;
            background: linear-gradient(to right, #e9ecef, transparent);
        }
        .section-accent {
            width: 4px;
            height: 16px;
            background: #4361ee;
            border-radius: 10px;
        }

        /* Form Styling */
        .form-label {
            font-size: 0.875rem;
            text-transform: none;
            letter-spacing: 0;
        }
        
        .avatar-upload-zone {
            border: 2px dashed #dee2e6 !important;
            transition: all 0.3s ease;
            background: #fdfdfd !important;
        }
        
        .avatar-upload-zone:hover {
            border-color: #4361ee !important;
            background: #f8f9ff !important;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 12px;
        }
    </style>
    @endpush

    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-2">
                <div>
                    <h2 class="mb-0 fw-bold h4">{{ $form?->id ? 'Edit User' : 'Create New User' }}</h2>
                    <p class="text-muted mb-0 small">Manage system access and profiles</p>
                </div>
            </div>
        </x-slot>
        
        <div class="header-actions">
            <a href="{{ route('users-management.users.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left-long"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="container-fluid px-4 pb-5">
        <x-basic.card>
            <x-basic.form action="{{ route('users-management.users.save', $form?->id) }}" class="needs-validation" novalidate>
                <div class="section-title">
                    <div class="section-accent"></div>
                    <h6 class="text-uppercase fs-7 fw-bold text-dark mb-0 ls-1">{{ __('global.personal_information') }}</h6>
                    <div class="line"></div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fa-regular fa-id-badge me-2 text-muted"></i>{{ __('global.name_en') }} <span class="text-danger">*</span></label>
                            <x-basic.form.text name="name_en" value="{{ $form?->name_en }}" :required="true" placeholder="Name EN" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fa-solid fa-signature me-2 text-muted"></i>{{ __('global.name_kh') }}</label>
                            <x-basic.form.text name="name_kh" value="{{ $form?->name_kh }}" placeholder="ឈ្មោះជាភាសាខ្មែរ" />
                        </div>
                    </div>
                </div>

                <div class="section-title mt-4">
                    <div class="section-accent"></div>
                    <h6 class="text-uppercase fs-7 fw-bold text-dark mb-0 ls-1">{{ __('global.account_details') }}</h6>
                    <div class="line"></div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-5">
                        <label class="form-label"><i class="fa-regular fa-envelope me-2 text-muted"></i>{{ __('global.email') }}</label>
                        <x-basic.form.text name="email" type="email" value="{{ $form?->email }}" :required="true" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fa-solid fa-phone-flip me-2 text-muted"></i>{{ __('global.phone_number') }}</label>
                        <x-basic.form.text name="phone" value="{{ $form?->phone }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fa-solid fa-shield-halved me-2 text-muted"></i>{{ __('global.role') }}</label>
                        <x-basic.form.multiple-select name="role_id[]" :options="$roles" :selected="$form?->roles?->pluck('id')->toArray()" :required="true" />
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label"><i class="fa-solid fa-key me-2 text-muted"></i>{{ __('global.password') }}</label>
                        <x-basic.form.text name="password" type="password" :required="!$form?->id" />
                        @if($form?->id)
                            <div class="alert alert-light border-0 py-2 mt-2">
                                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> {{ __('global.leave_blank_to_keep_current') }}</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="avatar-upload-zone text-center p-4 rounded-4 border">
                            {{-- Preview --}}
                            <div class="avatar-preview mb-3">
                                <img id="avatarPreview"
                                    src="{{ $form?->avatar ? asset($form->avatar) : asset('assets/images/default/male-avatar.jpg') }}"
                                    class="rounded-circle shadow" style="width:120px;height:120px;object-fit:cover;">
                            </div>
                            {{-- Upload --}}
                            <div class="mb-2">
                                <label class="btn btn-primary btn-sm">
                                    <i class="fa fa-upload me-1"></i> Upload Avatar
                                    <input type="file" name="avatar" id="avatarInput"
                                        accept="image/*" hidden>
                                </label>
                            </div>

                            <p class="text-muted small mt-2">
                                Recommended: Square image, max 2MB (JPG, PNG)
                            </p>

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center mt-5 pt-4 border-top">
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('users-management.users.index') }}" class="btn btn-light px-4 fw-semibold text-secondary">
                            {{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-check me-2"></i> {{ __('global.save') }}
                        </button>
                    </div>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const input = document.querySelector('input[name="avatar"]');
                const preview = document.getElementById('avatarPreview');

                if (input && preview) {

                    input.addEventListener('change', function (e) {

                        const file = e.target.files[0];

                        if (file) {
                            preview.src = URL.createObjectURL(file);
                        }

                    });

                }

            });
        </script>
    @endpush
</x-app-layout>