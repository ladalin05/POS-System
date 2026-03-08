<div class="container mt-4">
    <form action="save_room.php" method="POST">
        <div class="mb-3">
            <label>{{ __('global.name') }}</label>
            <input type="text" name="name" value="{{ $form->name ?? '' }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>