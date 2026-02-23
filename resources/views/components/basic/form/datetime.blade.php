{{-- resources/views/components/basic/form/datetime.blade.php --}}
@props([
  'label' => null,
  'name',
  'value' => null,   // expect 'Y-m-d\TH:i' string
  'required' => false,
  'disabled' => false,
  'step' => '60',    // "1" for seconds, "60" for minute precision
])

<label class="form-label" for="{{ $name }}">{{ $label }} @if($required)*@endif</label>
<input
  type="datetime-local"
  name="{{ $name }}"
  id="{{ $name }}"
  value="{{ old($name, $value) }}"
  step="{{ $step }}"
  @if($required) required @endif
  @if($disabled) disabled @endif
  class="form-control"
/>
@error($name)<div class="text-danger small">{{ $message }}</div>@enderror
