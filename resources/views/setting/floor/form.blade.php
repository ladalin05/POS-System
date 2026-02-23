<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('setting.floor.save', $form?->id) }}" novalidate>
                <div class="row">
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.name') }}" name="name"
                            value="{{ $form?->name }}" :required="true" />
                    </div>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('setting.floor.index') }}"
                        class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>

            </x-basic.form>
        </x-basic.card>
    </div>
    <!-- /content area -->
</x-app-layout>