
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Warehouse Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $form->name }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Warehouse Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" value="{{ $form->code }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Branch <span class="text-danger">*</span></label>
            <select name="branch_id" class="form-select" required>
                <option value="">Select Branch</option>
                @foreach($branch as $b)
                    <option value="{{ $b->id }}" {{ $form->branch_id == $b->id ? 'selected' : '' }}>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ $form->email }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control" value="{{ $form->phone }}" required>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Address <span class="text-danger">*</span></label>
            <textarea name="address" class="form-control" rows="2" required>{{ $form->address }}</textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</div>