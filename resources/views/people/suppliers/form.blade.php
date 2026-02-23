<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
    <div class="content">
    <x-basic.card :title="$title">
        <x-basic.form action="{{ route('people.suppliers.save', $form?->id) }}" novalidate>
            <div class="row">
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
                    <x-basic.form.text label="{{ __('global.city') }}" name="city"
                        value="{{ $form?->city }}" :required="true" />
                </div>
                <div class="col-md-4">
                    <x-basic.form.text label="{{ __('global.state') }}" name="state"
                        value="{{ $form?->state }}" :required="true" />
                </div>
                <div class="col-md-4">
                    <x-basic.form.text label="{{ __('global.email_address') }}" name="email_address"
                        value="{{ $form?->email_address }}" :required="true" />
                </div>
                <div class="col-md-4">
                    <x-basic.form.text label="{{ __('global.vat_number') }}" name="vat_number"
                        value="{{ $form?->vat_number }}" :required="true" />
                </div>
                <div class="col-md-4">
                    <x-basic.form.text label="{{ __('global.postal_code') }}" name="postal_code"
                        value="{{ $form?->postal_code }}" :required="true" />
                </div>
                <div class="col-md-4">
                    <x-basic.form.text label="{{ __('global.country') }}" name="country"
                        value="{{ $form?->country }}" :required="true" />
                </div>
                <div class="col-md-12">
                    <x-basic.form.textarea label="{{ __('global.address') }}" name="address"
                        value="{{ $form?->address }}" :required="true" />
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('people.suppliers.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
            </div>
        </x-basic.form>
    </x-basic.card>
    </div>
</x-app-layout>
