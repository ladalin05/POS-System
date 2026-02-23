@props([
    'title' => '',
    'action' => '',
    'method' => 'POST',
    'enctype' => 'multipart/form-data',
    'id' => '',
])
<!-- Basic modal -->
<div id="modal_default" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Basic modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ $action }}">
                    @csrf
                    {{ $slot }}
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /basic modal -->
