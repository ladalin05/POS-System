<x-basic.form.text label="{{ __('global.code') }}" name="code" :value="old('code', $form->code ?? '')" required />


<x-basic.form.text label="{{ __('global.name') }}" name="name" :value="old('name', $form->name ?? '')" required />



<x-basic.form.text label="{{ __('global.exchange_rate') }} " name="rate" :value="old('rate', $form->rate ?? '')"
    required />


<div class="text-end mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
    <button type="submit" class="btn btn-primary ">{{ __('global.save') }}</button>
</div>