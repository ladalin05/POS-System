@props([
    'label' => null,
    'name' => 'file',
    'accept' => 'image/*',
    'required' => false,
])

<div class="mb-3">
    @if($label)
        <label class="form-label" for="{{ $name }}">{{ $label }} @if($required) * @endif</label>
    @endif
    <input type="file"
           id="{{ $name }}"
           name="{{ $name }}"
           accept="{{ $accept }}"
           {{ $required ? 'required' : '' }}
           {{ $attributes->merge(['class' => 'form-control']) }}>
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
