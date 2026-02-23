@props([
    'disabled' => false,
    'options' => [],
    'selected' => [],
    'label' => null,
])
<div class="mb-3">
    <label class="form-label">{{ $label }}</label>
    <div class="input-group">
        <select @disabled($disabled) {{ $attributes->merge(['class' => 'form-control form-control-sm multiple-select', 'multiple' => 'multiple']) }}>
            @foreach ($options as $row)
                <option value="{{ $row->id }}" {{ in_array($row->id, $selected) ? 'selected' : '' }}>{{ $row->name }}</option>
            @endforeach
        </select>
    </div>
</div>