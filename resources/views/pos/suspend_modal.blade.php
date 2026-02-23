<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suspended Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                @if($suspends->count())
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Room ID</th>
                                <th>Room Name</th>
                                <th>Suspended At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suspends as $s)
                                <tr>
                                    <td>{{ $s->id }}</td>
                                    <td>{{ $s->room_id }}</td>
                                    <td>{{ $s->room_name ?? 'N/A' }}</td>
                                    <td>{{ $s->created_at }}</td>
                                    <td>
                                        <a href="{{ route('pos.resume', $s->id) }}" class="btn btn-sm btn-outline-success">
                                            Resume
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="deleteSuspend({{ $s->id }}, this)">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No suspended orders found.</p>
                @endif

            </div>
        </div>
    </div>
</div>