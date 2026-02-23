<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>

    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('people.saleman.save', $form?->id) }}" enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.first_name') }}" name="first_name" value="{{ $form?->first_name }}" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.last_name') }}" name="last_name" value="{{ $form?->last_name }}" :required="true" />
                    </div>
                    
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.gender') }}" name="gender" 
                            :options="$gender" :selected="$form?->gender" :required="true"/>
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.phone') }}" name="phone" value="{{ $form?->phone }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.position') }}" name="position" value="{{ $form?->position }}" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.group_saleman') }}" name="group_id" 
                            :options="$group_options" :selected="$form?->group_id" />
                    </div>
                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.status') }}" name="status" 
                            :options="$status" :selected="$form?->status" :required="true"/>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('people.saleman.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
</x-app-layout>
