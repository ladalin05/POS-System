<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('people.customer.save', $form?->id) }}" enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.customer_group') }}" name="customer_group_id"
                            :options="" :selected="" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.price_group') }}" name="price_group_id"
                            :options="" :selected="" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.salesman') }}" name="salesman_id"
                            :options="" :selected="" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.code') }}" name="code"
                            value="{{ $form?->code }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.company') }}" name="company"
                            value="{{ $form?->company }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.name') }}" name="name"
                            value="{{ $form?->name }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.phone') }}" name="phone"
                            value="{{ $form?->phone }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.address') }}" name="address"
                            value="{{ $form?->address }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.city') }}" name="city"
                            value="{{ $form?->city }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.state') }}" name="state"
                            value="{{ $form?->state }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.email_address') }}" name="email_address"
                            value="{{ $form?->email_address }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.vat_number') }}" name="vat_number"
                            value="{{ $form?->vat_number }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.postal_code') }}" name="postal_code"
                            value="{{ $form?->postal_code }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.country') }}" name="country"
                            value="{{ $form?->country }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.credit_day') }}" name="credit_day"
                            value="{{ $form?->credit_day }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.credit_amount') }}" name="credit_amount"
                            value="{{ $form?->credit_amount }}" />
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">{{ __('global.attachment') }}</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('people.customer.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
</x-app-layout>
