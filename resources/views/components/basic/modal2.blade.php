@props([
    'size' => '',        // 'sm' | 'lg' | 'xl' | '' (default ~500px) | 'fullscreen'
    'title' => '',
    'subtitle' => '',
    'scrollable' => true,
    'centered' => true,
])

@php
    // normalize whether someone passes "xl" or "modal-xl"
    $sizeClass = $size ? 'modal-' . str_replace('modal-', '', $size) : '';

    $dialogClasses = collect([
        $sizeClass,
        $centered ? 'modal-dialog-centered' : null,
        $scrollable ? 'modal-dialog-scrollable' : null,
    ])->filter()->implode(' ');
@endphp

<div {{ $attributes->merge(['class' => 'modal fade']) }} tabindex="-1">
    <div class="modal-dialog {{ $dialogClasses }}">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

            @if ($title)
                <div class="modal-header bg-white border-bottom px-4 py-3">
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight: 600;">{{ $title }}</h5>
                        @if ($subtitle)
                            <div class="text-muted" style="font-size: 13px;">{{ $subtitle }}</div>
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            <div class="modal-body px-4 py-4" style="max-height: 75vh;">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="modal-footer bg-white border-top px-4 py-3">
                    {{ $footer }}
                </div>
            @endisset

        </div>
    </div>
</div>