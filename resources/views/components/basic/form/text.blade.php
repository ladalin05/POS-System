@props([
    'disabled' => false,
    'label' => null,
])
<div class="mb-3">
    <label class="form-label">{{ $label }} @if($attributes->has('required')) <span class="text-danger">*</span> @endif</label>
    <div class="input-group">
        <input @disabled($disabled) {{ $attributes->merge(['class' => 'form-control form-control-sm']) }} type="text">
    </div>
</div>
