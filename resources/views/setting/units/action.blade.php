<div class="dropdown">
    <button class="btn btn-light btn-icon btn-sm dropdown-toggle hide-arrow shadow-sm" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            style="border-radius: 8px; padding: 0.5rem;">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2" style="min-width: 160px; border-radius: 12px;">
        
        @can('setting.units.edit')
            <a href="{{ route('setting.units.edit', $row->id) }}" class="dropdown-item py-2 px-3 d-flex align-items-center">
                <div class="bg-primary-subtle rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="ph ph-pencil me-2"></i>
                </div>
                {{ __('global.edit') }}
            </a>
        @endcan

        @can('setting.units.delete')
            @can('setting.units.edit')
                <div class="dropdown-divider my-1 opacity-50"></div>
            @endcan

            <a href="{{ route('setting.units.delete', $row->id) }}" 
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