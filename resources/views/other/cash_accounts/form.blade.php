<div class="content">
    <div class="card custom-card">

        <div class="card-header bg-light border-bottom">
            <h5 class="card-title mb-0 d-flex align-items-center">
                <i class="ph ph-wallet me-2 text-primary fs-5"></i>
                {{ isset($form->id) ? 'Edit Cash Account' : 'New Cash Account' }}
            </h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ $action }}" id="roomForm" enctype="multipart/form-data" class="ajax-form">
                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <x-forms.input
                            label="{{ __('global.code') }}"
                            name="code"
                            type="text"
                            :value="old('code', $form->code ?? '')"
                            placeholder="e.g. ACC-001"
                            required
                        />
                    </div>

                    <div class="col-md-8">
                        <x-forms.input
                            label="{{ __('global.name') }}"
                            name="name"
                            type="text"
                            :value="old('name', $form->name ?? '')"
                            placeholder="Enter Account Name"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-forms.select
                            name="type"
                            label="Type"
                            :options="[
                                'cash' => 'Cash',
                                'bank' => 'Bank',
                                'petty_cash' => 'Petty Cash',
                            ]"
                            :value="($form->type ?? '')"
                            placeholder="Select Type"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-forms.select
                            name="currency"
                            label="Currency"
                            :options="[
                                'USD' => 'USD - US Dollar',
                                'KHR' => 'KHR - Cambodian Riel',
                                'EUR' => 'EUR - Euro',
                            ]"
                            :value="($form->currency ?? 'USD')"
                            placeholder="Select Currency"
                        />
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-8">
                            <x-forms.input
                                label="Opening Balance"
                                name="opening_balance"
                                type="number"
                                step="0.01"
                                :value="old('opening_balance', $form->opening_balance ?? 0)"
                                placeholder="0.00"
                            />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label d-block">Status</label>
                        <div class="form-check form-switch mt-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $form->is_active ?? true) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('other.cash_accounts.index') }}" class="btn btn-light border">
                        <i class="ph ph-x me-2"></i>{{ __('global.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                        <i class="ph ph-floppy-disk me-2"></i> Save Account
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>