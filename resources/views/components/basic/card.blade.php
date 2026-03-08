@props([
    'data' => null,
    'title' => null,
])
<div class="card shadow-sm border-0">
    @if($title)
        <div class="card-header bg-white py-2  ">
            {{ $title }}
        </div>
    @endif
    <div class="card-body p-4">
        {{ $slot }}
    </div>
</div>