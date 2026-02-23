<div class="d-inline-flex dropdown ms-2">
    <a href="javascript:void(0)" class="d-inline-flex align-items-center text-body dropdown-toggle"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph ph-list"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">


        @can('other.warehouses.save')
            <a href="{{ route('other.warehouses.save', $a->id) }}" class="dropdown-item" onclick="editwarehouses(event)">
                <i class="ph ph-pencil me-2"></i>
                {{ __('global.edit') }}
            </a>
        @endcan


    </div>
</div>