<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('setting.room.save', $form?->id) }}" novalidate>
                <div class="row">
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.code') }}" name="code"
                            value="{{ $form?->code }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.name') }}" name="name"
                            value="{{ $form?->name }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.floor') }}" name="floor_id"
                            :options="$floor" :selected="$form?->floor_id" :required="true" />
                    </div>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('setting.room.index') }}"
                        class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>

            </x-basic.form>
        </x-basic.card>
    </div>
    <!-- /content area -->
</x-app-layout>