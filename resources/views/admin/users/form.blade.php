<x-app-layout> 
    @push('css')
    <style>
        .ls-1 { letter-spacing: 1px; }
        .fs-7 { font-size: 0.75rem; }

        .form-section-header hr {
            height: 2px;
            background: linear-gradient(to right, #4361ee, transparent);
            border: none;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .card {
            background-color: #ffffff;
            transition: transform 0.2s ease;
        }

        .avatar-upload-wrapper {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 1px dashed #dee2e6;
        }

        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .btn-primary:hover {
            background-color: #3751d4;
            border-color: #3751d4;
            transform: translateY(-1px);
        }

        .btn-light {
            background-color: #f1f3f5;
            color: #495057;
        }

        label {
            font-weight: 500;
            color: #344767;
            margin-bottom: 0.4rem;
        }
    </style>
    @endpush
    <div class="content">
        <x-basic.breadcrumb>
            <x-slot name="title">
                <h2 class="mb-0" >{{ __('global.create_product') }}</h2>
                <span style="color: #646B72; font-size: 14px;" >Create new product</span>
            </x-slot>
            <div class="header-actions d-flex align-items-center gap-2">
                <a href="{{ route('users-management.users.index') }}" class="btn btn-add-user d-flex align-items-center gap-2 text-white">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.add_new') }}
                </a>
            </div>
        </x-basic.breadcrumb>
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('users-management.users.save', $form?->id) }}" class="needs-validation" novalidate>
                <div class="form-section-header mb-4 mt-3">
                    <h6 class="text-uppercase fs-7 fw-bold text-primary ls-1">{{ __('global.personal_information') }}</h6>
                    <div class="h-divider"></div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.name_en') }}" name="name_en" value="{{ $form?->name_en }}" :required="true" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.name_kh') }}" name="name_kh" value="{{ $form?->name_kh }}" />
                    </div>
                </div>

                <div class="form-section-header mb-4 mt-5">
                    <h6 class="text-uppercase fs-7 fw-bold text-primary ls-1">{{ __('global.account_details') }}</h6>
                    <div class="h-divider"></div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.email') }}" name="email" value="{{ $form?->email }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.phone_number') }}" name="phone" value="{{ $form?->phone }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.multiple-select label="{{ __('global.role') }}" name="role_id[]" :options="$roles" :selected="$form?->roles?->pluck('id')->toArray()" :required="true" />
                    </div>
                    <div class="col-md-12">
                        <x-basic.form.text label="{{ __('global.password') }}" name="password" type="password" :required="$form?->id ? false : true" />
                        @if($form?->id)
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-shield-lock me-1"></i>{{ __('global.leave_blank_to_keep_current') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="avatar-upload-zone p-4 text-center border rounded-4 bg-light">
                            <x-extensions.image-cropper title="{{ __('global.avatar') }}" label="{{ __('global.avatar') }}" name="avatar" :image="$form?->avatar" />
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-5 pt-4 border-top">
                    <a href="{{ route('users-management.users.index') }}" class="btn btn-light fw-semibold px-4">
                        {{ __('global.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold px-5 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> {{ __('global.save') }}
                    </button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
    <!-- /content area -->
</x-app-layout>
