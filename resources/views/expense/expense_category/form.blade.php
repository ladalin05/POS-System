<x-basic.form.text label="{{ __('global.code') }}" name="code" :value="old('code', $form->code ?? '')" required />
<x-basic.form.text label="{{ __('global.name') }}" name="name" :value="old('name', $form->name ?? '')" required />

<x-basic.form.textarea label="{{ __('global.description') }}" name="description" rows="3" :required="false">
    {{ old('description', $form->description ?? '') }}
</x-basic.form.textarea>

<div class="text-end mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
    <button type="submit" class="btn btn-primary ">{{ __('global.save') }}</button>
</div>