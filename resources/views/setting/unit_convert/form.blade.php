<div class="container mt-4">
    <form action="save_room.php" method="POST">
        <div class="mb-3">
            <label>Convert From</label>
            <select name="unit_from_id" class="form-control">
                @foreach($units as $id => $name)
                    <option value="{{ $id }}" {{ ($form->unit_from_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Convert To</label>
            <select name="unit_to_id" class="form-control">
                @foreach($units as $id => $name)
                    <option value="{{ $id }}" {{ ($form->unit_to_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Operator</label>
            <select name="operator" class="form-control">
                <option value="*" {{ ($form->operator ?? '') == '*' ? 'selected' : '' }}>* Multiply</option>
                <option value="/" {{ ($form->operator ?? '') == '/' ? 'selected' : '' }}>/ Divide</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Numerator</label>
            <input type="number" step="0.000001" name="numerator" value="{{ $form->numerator ?? '' }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $form->name ?? '' }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ ($form->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ ($form->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
</div>