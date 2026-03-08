<div class="container mt-4">
    <form action="save_room.php" method="POST">
        <div class="mb-3">
            <label>From Unit</label>
            <select name="from_unit_id" class="form-control">
                @foreach($units as $id => $name)
                    <option value="{{ $id }}" {{ ($form->from_unit_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>To Unit</label>
            <select name="to_unit_id" class="form-control">
                @foreach($units as $id => $name)
                    <option value="{{ $id }}" {{ ($form->to_unit_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Numerator</label>
            <input type="number" name="numerator" value="{{ $form->numerator ?? '' }}" class="form-control">
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