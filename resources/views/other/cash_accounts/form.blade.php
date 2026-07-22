
<div class="content">
    <div class="card custom-card">
        <form method="POST" action="{{ $action }}" id="roomForm" enctype="multipart/form-data" class="ajax-form">
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
                        :options="[ 'cash' => 'Cash', 'bank' => 'Bank', 'petty_cash' => 'Petty Cash']"
                        :value="($form->type ?? '')"
                        placeholder="Select Type"
                        required
                    />
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

        </form>
    </div>