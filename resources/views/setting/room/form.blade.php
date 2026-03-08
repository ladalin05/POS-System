<div class="container mt-4">
    <form action="save_room.php" method="POST">
        
        <div class="mb-3">
            <label class="form-label">Room Code</label>
            <input type="text" name="code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Room Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Floor ID</label>
            <input type="number" name="floor_id" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>