<div class="d-inline-flex dropdown ms-2">
    <a href="javascript:void(0)" class="d-inline-flex align-items-center text-body dropdown-toggle"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph ph-list"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">


        @can('other.currencies.save')
            <a href="{{ route('other.currencies.save', $a->id) }}" class="dropdown-item" onclick="editcurrencies(event)">
                <i class="ph ph-pencil me-2"></i>
                {{ __('global.edit') }}
            </a>
        @endcan
        @can('other.currencies.delete')
            <div class="dropdown-divider"></div>
            <a href="{{ route('other.currencies.delete', $a->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
                <i class="ph ph-x text-danger me-2"></i>
                {{ __('global.delete') }}
            </a>
        @endcan


    </div>
</div>