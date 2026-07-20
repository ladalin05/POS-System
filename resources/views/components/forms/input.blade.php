@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
])

<div class="mb-3">
    @if($label ?? $slot->isNotEmpty())
        <label for="{{ $name }}" class="form-label">
            {{ $label ?? $slot }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')
        ]) }}
    />

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>