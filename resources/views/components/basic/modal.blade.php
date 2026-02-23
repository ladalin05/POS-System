@props([
    'size' => '',
    'title' => '',
])
<div {{ $attributes->merge(['class' => 'modal fade']) }}>
    <div class="modal-dialog modal-{{ $size }} ">
        <div class="modal-content">
            <div class="modal-header bg-white py-3">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
