@props([
    'disabled' => false,
    'checked' => false,
    'label' => null,
])
<div class="form-check form-check-inline">
    <input type="checkbox" @disabled($disabled) {{ $attributes->merge(['class' => 'form-check-input']) }} @if($checked) checked @endif>
    <label class="form-check-label cursor-pointer ms-3" for="{{ $attributes['id'] }}">{{ $label }}</label>
</div>