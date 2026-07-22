<x-app-layout>
    @push('css')
    <style>
        .ls-1 { letter-spacing: 0.5px; }
        .fs-7 { font-size: 0.78rem; }

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
            <x-basic.form action="{{ $action }}" enctype="multipart/form-data" class="needs-validation" novalidate>

                {{-- Avatar --}}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="avatar-upload-zone text-center p-4 rounded-4 border">
                            <div class="uploader-avatar d-inline-block mb-2">
                                <x-basic.uploader
                                    inputName="avatar"
                                    :url="$form?->avatar ? asset($form->avatar) . '?v=' . ($form->updated_at?->timestamp ?? time()) : ''"
                                    :path="$form?->avatar ?? ''"
                                    accept="image/*"
                                    layout="block"
                                    width="120px"
                                    height="120px"
                                    shape="circle"
                                    folder="avatars"
                                    :filenameHint="$form?->id ?? ''"
                                />
                            </div>
                            <p class="text-muted small mt-2 mb-0">
                                Click the camera icon to upload. Recommended: square image, max 2MB (JPG, PNG)
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="section-title">
                    <div class="section-accent"></div>
                    <h6 class="text-uppercase fs-7 fw-bold text-dark mb-0 ls-1">{{ __('global.personal_information') }}</h6>
                    <div class="line"></div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <x-forms.input
                            label="{{ __('global.name_en') }}"
                            name="name_en"
                            id="name_en"
                            type="text"
                            :value="old('name_en', $form->name_en ?? '')"
                            placeholder="Name EN"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <x-forms.input
                            label="{{ __('global.name_kh') }}"
                            name="name_kh"
                            id="name_kh"
                            type="text"
                            :value="old('name_kh', $form->name_kh ?? '')"
                            placeholder="ឈ្មោះជាភាសាខ្មែរ"
                        />
                    </div>
                </div>

                {{-- Account Details --}}
                <div class="section-title mt-4">
                    <div class="section-accent"></div>
                    <h6 class="text-uppercase fs-7 fw-bold text-dark mb-0 ls-1">{{ __('global.account_details') }}</h6>
                    <div class="line"></div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-5">
                        <x-forms.input
                            label="{{ __('global.email') }}"
                            name="email"
                            id="email"
                            type="email"
                            :value="old('email', $form->email ?? '')"
                            placeholder="Enter Email Address"
                            required
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.input
                            label="{{ __('global.phone_number') }}"
                            name="phone"
                            id="phone"
                            type="text"
                            :value="old('phone', $form->phone ?? '')"
                            placeholder="Enter Phone Number"
                        />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fa-solid fa-shield-halved me-2 text-muted"></i>{{ __('global.role') }}</label>
                        <x-basic.form.multiple-select
                            name="role_id[]"
                            :options="$roles"
                            :selected="old('role_id', $form?->roles?->pluck('id')->toArray() ?? [])"
                            :required="true"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-forms.input
                            label="{{ __('global.password') }}"
                            name="password"
                            id="password"
                            type="password"
                            placeholder="{{ $form?->id ? '••••••••' : 'Enter Password' }}"
                            :required="!$form?->id"
                        />
                        @if($form?->id)
                            <div class="alert alert-light border-0 py-2 mt-2">
                                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> {{ __('global.leave_blank_to_keep_current') }}</small>
                            </div>
                        @endif
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
</x-app-layout>