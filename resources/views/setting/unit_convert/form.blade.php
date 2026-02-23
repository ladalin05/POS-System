{{-- expects: $action (route), $form (UnitConvert model or null), $units (collection) --}}
<form action="{{ $action ?? route('setting.unit_convert.save') }}" method="POST" id="unit-convert-form">
    @csrf

    @if(!empty($form->id))
        <input type="hidden" name="id" value="{{ $form->id }}">
    @endif

    <div class="mb-3">
        <label for="unit_from_id" class="form-label">{{ __('global.unit_from') }}</label>
        <select name="unit_from_id" id="unit_from_id" class="form-select @error('unit_from_id') is-invalid @enderror"
            required>
            <option value="">{{ __('global.please_select') }}</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ (int) old('unit_from_id', $form->unit_from_id ?? 0) === $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>
        @error('unit_from_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="unit_to_id" class="form-label">{{ __('global.unit_to') }}</label>
        <select name="unit_to_id" id="unit_to_id" class="form-select @error('unit_to_id') is-invalid @enderror"
            required>
            <option value="">{{ __('global.please_select') }}</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ (int) old('unit_to_id', $form->unit_to_id ?? 0) === $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>
        @error('unit_to_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="row g-2">
        <div class="col-md-6 mb-3">
            <label for="numerator" class="form-label">{{ __('global.numerator') }}</label>
            <input type="text" name="numerator" id="numerator"
                class="form-control @error('numerator') is-invalid @enderror"
                value="{{ old('numerator', $form->numerator ?? '') }}" required>
            @error('numerator')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">e.g. enter 24 for 1 case = 24 cans</div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="operator" class="form-label">{{ __('global.operator') }}</label>
            <select name="operator" id="operator" class="form-select @error('operator') is-invalid @enderror" required>
                <option value="*" {{ old('operator', $form->operator ?? '*') == '*' ? 'selected' : '' }}>× (multiply)
                </option>
                <option value="/" {{ old('operator', $form->operator ?? '*') == '/' ? 'selected' : '' }}>÷ (divide)
                </option>
            </select>
            @error('operator')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Choose operator. Use * if 1 from = numerator × to</div>
        </div>
    </div>

    <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $form->is_active ?? 1) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">{{ __('global.active') }}</label>
    </div>

    <div class="mb-3 mt-3">
        <label for="name" class="form-label">{{ __('global.name') }} <small
                class="text-muted">(optional)</small></label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $form->name ?? '') }}"
            placeholder="Case → Can (optional)">
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
        <button type="submit" class="btn btn-primary" id="unit-convert-submit">{{ __('global.save') }}</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('unit-convert-form');
        const fromSelect = document.getElementById('unit_from_id');
        const toSelect = document.getElementById('unit_to_id');
        const numeratorEl = document.getElementById('numerator');
        const submitBtn = document.getElementById('unit-convert-submit');

        function showError(msg) {
            // replace with custom toast if you want
            alert(msg);
        }

        form.addEventListener('submit', function (e) {
            // client-side checks: from != to, numerator > 0
            const from = fromSelect.value;
            const to = toSelect.value;
            const numerator = parseFloat(numeratorEl.value);

            if (!from || !to) {
                e.preventDefault();
                showError("{{ __('global.please_select_both_units') ?? 'Please select both units.' }}");
                return false;
            }

            if (from === to) {
                e.preventDefault();
                showError("{{ __('global.from_must_not_equal_to') ?? 'From unit must be different from To unit.' }}");
                return false;
            }

            if (!numerator || numerator <= 0) {
                e.preventDefault();
                showError("{{ __('global.enter_valid_numerator') ?? 'Please enter a valid numerator (> 0).' }}");
                return false;
            }

            // disable to avoid double submit
            submitBtn.disabled = true;
        });

        // auto-generate name if empty
        function previewName() {
            const fromText = fromSelect.options[fromSelect.selectedIndex]?.text || '';
            const toText = toSelect.options[toSelect.selectedIndex]?.text || '';
            const nameInput = document.getElementById('name');
            if (fromText && toText && fromText !== toText && nameInput && !nameInput.value) {
                nameInput.value = `${fromText} → ${toText}`;
            }
        }

        fromSelect.addEventListener('change', previewName);
        toSelect.addEventListener('change', previewName);
    });
</script>