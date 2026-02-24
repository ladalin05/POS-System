<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <div class="d-flex align-items-center gap-3 ms-3">
                <div>
                    <h2 class="mb-0 fw-bold h4">Create Cash Account</h2>
                    <p class="text-muted mb-0 small">Create new Account</p>
                </div>
            </div>
        </x-slot>
        
        <div class="header-actions">
            <a href="{{ route('other.cash_accounts.index') }}" class="btn btn-add-user bg-primary d-flex align-items-center gap-2 text-white">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('global.back_to_list') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content">
        <div class="card custom-card">
            <x-basic.form class="card-body p-4" action="{{ route('other.cash_accounts.save', $form?->id) }}" novalidate>
                <div class="row g-3">
                    <div class="col-md-4">
                        <x-basic.form.text 
                            label="{{ __('global.code') }}" 
                            name="code" 
                            value="{{ $form?->code }}" 
                            placeholder="e.g. ACC-001"
                            :required="true" />
                    </div>

                    <div class="col-md-8">
                        <x-basic.form.text 
                            label="{{ __('global.name') }}" 
                            name="name" 
                            value="{{ $form?->name }}" 
                            placeholder="{{ __('Enter account name') }}"
                            :required="true" />
                    </div>

                    <div class="col-md-6">
                        <x-basic.form.select 
                            label="{{ __('global.type') }}" 
                            name="type" 
                            :required="true">
                            <option value="cash" {{ ($form?->type == 'cash') ? 'selected' : '' }}>{{ __('Cash') }}</option>
                            <option value="bank" {{ ($form?->type == 'bank') ? 'selected' : '' }}>{{ __('Bank') }}</option>
                            <option value="petty_cash" {{ ($form?->type == 'petty_cash') ? 'selected' : '' }}>{{ __('Petty Cash') }}</option>
                        </x-basic.form.select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('other.cash_accounts.index') }}" class="btn btn-light border">
                        <i class="ph-x me-2"></i>{{ __('global.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                        <i class="ph ph-floppy-disk me-2"></i> Save Account
                    </button>
                </div>

            </x-basic.form>
        </div>
</x-app-layout>