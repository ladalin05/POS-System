<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('people.customer_deposit.save', $form?->id) }}" enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.customer') }}" name="customer_id"
                            :options="$customer" :selected="$form?->customer_id" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.code') }}" name="code"
                            value="{{ $form?->code }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.reference_no') }}" name="reference_no"
                            value="{{ $form?->reference_no }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.name') }}" name="name"
                            value="{{ $form?->name }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.branch') }}" name="branch"
                            value="{{ $form?->branch }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.address') }}" name="address"
                            value="{{ $form?->address }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.customer') }}" name="customer"
                            value="{{ $form?->customer }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.amount') }}" name="amount"
                            value="{{ $form?->amount }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.paying_by') }}" name="paying_by"
                            value="{{ $form?->paying_by }}" />
                    </div>
                    <div class="mb-3">
                            <label for="attachment" class="form-label">{{ __('global.attachment') }}</label>
                            <input type="file" class="form-control" id="attachment" name="attachment">
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.textarea label="{{ __('global.note') }}" name="note"
                            value="{{ $form?->note }}" />
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('people.customer_deposit.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
</x-app-layout>
