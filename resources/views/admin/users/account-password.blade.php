<x-basic.form.text label="{{ __('global.current_password') }}" name="current_password" required />
<x-basic.form.text label="{{ __('global.new_password') }}" name="new_password" required />
<x-basic.form.text label="{{ __('global.confirm_password') }}" name="confirm_password" required />
<div class="text-end mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
</div>