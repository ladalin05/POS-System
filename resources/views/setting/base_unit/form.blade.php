<x-app-layout>
    <x-basic.breadcrumb></x-basic.breadcrumb>

    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('setting.base_units.save', $form?->id) }}" method="POST" novalidate>
                @csrf
                <div class="row">

                   

                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('From Unit') }}" name="from_unit_id"
                            :options="$units" :selected="$form?->from_unit_id" :required="true" />
                    </div>

                     <div class="col-md-4">
                        <x-basic.form.select label="{{ __('To Unit') }}" name="to_unit_id"
                            :options="$units" :selected="$form?->to_unit_id" :required="true" />
                    </div>

                 

                    <div class="col-md-4">
                        <x-basic.form.text
                            label="{{ __('Numerator (ratio)') }}"
                            name="numerator"
                            type="number"
                            min="1"
                            step="1"
                            value="{{ old('numerator', 1) }}"
                            :required="true" />
                        @error('numerator')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="form-check form-switch">
                           <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('global.active') }}</label>
                        </div>
                        @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('setting.units.index') }}" class="btn btn-warning">
                        {{ __('global.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ __('global.save') }}
                    </button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
</x-app-layout>
