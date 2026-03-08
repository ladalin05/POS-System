<div class="dropdown">
    <button class="btn btn-light btn-icon btn-sm dropdown-toggle hide-arrow shadow-sm" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            style="border-radius: 8px; padding: 0.5rem;">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2" style="min-width: 160px; border-radius: 12px;">
        
        @can('stock.adjustment.edit')
            <a href="{{ route('stocks.adjustment.edit', $row->id) }}" class="dropdown-item py-2 px-3 d-flex align-items-center">
                <div class="bg-primary-subtle rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="ph ph-pencil me-2"></i>
                </div>
                {{ __('global.edit') }}
            </a>
        @endcan

        @can('stock.adjustment.approve')
            @if(empty($row->status) || $row->status !== 'approved')
                @can('stock.adjustment.edit')
                    <div class="dropdown-divider my-1 opacity-50"></div>
                @endcan

                <a href="{{ route('stocks.adjustment.approve', $row->id) }}" 
                   class="dropdown-item py-2 px-3 d-flex align-items-center text-success"
                   onclick="approveAdjustment(event)">
                    <div class="bg-success-subtle rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="ph ph-check-circle text-success"></i>
                    </div>
                    <span class="fw-medium">{{ __('global.approve') }}</span>
                </a>
            @endif
        @endcan
        
        @can('stock.adjustment.delete')
            @can('stock.adjustment.approve')
                <div class="dropdown-divider my-1 opacity-50"></div>
            @endcan

            <a href="{{ route('stocks.adjustment.delete', $row->id) }}" 
               class="dropdown-item py-2 px-3 d-flex align-items-center text-danger"
               onclick="deleteRecord(event)">
                <div class="bg-danger-subtle rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="ph ph-trash text-danger"></i>
                </div>
                <span class="fw-medium">{{ __('global.delete') }}</span>
            </a>
        @endcan

    </div>
</div>