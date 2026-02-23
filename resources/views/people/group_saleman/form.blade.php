<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('people.group_saleman.save', $form?->id) }}" enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.group_name') }}" name="group_name"
                            value="{{ $form?->group_name }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.description') }}" name="description"
                            value="{{ $form?->description }}" :required="true" />
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('people.group_saleman.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
</x-app-layout>
