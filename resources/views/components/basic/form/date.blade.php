@props([
    'label' => '',
    'name' => '',
    'value' => '',
    'required' => false,
])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input 
        type="date" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control']) }}
    >

    @error($name)
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>
