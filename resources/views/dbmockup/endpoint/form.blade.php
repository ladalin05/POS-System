<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('dbmockup.endpoint.save', $form?->id) }}" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.name') }}" name="name" value="{{ $form?->name }}" :required="true" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.endpoint') }}" name="url" value="{{ $form?->url }}" :required="true" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.select label="{{ __('global.method') }}" name="method" :options="$methods" :required="true" :selected="$form?->method" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.select label="{{ __('global.project') }}" name="project_id" :options="$projects" :required="true" :selected="$form?->project_id" />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('global.json') }}<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="file" accept=".json"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('dbmockup.endpoint.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
    <!-- /content area -->
</x-app-layout>
