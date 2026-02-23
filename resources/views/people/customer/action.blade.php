<div class="d-inline-flex dropdown ms-2">
    <a href="javascript:void(0)" class="d-inline-flex align-items-center text-body dropdown-toggle"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph ph-list"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @can('people.customer.edit')
            <a href="{{ route('people.customer.edit', $a->id) }}" class="dropdown-item">
                <i class="ph ph-pencil me-2"></i>
                {{ __('global.edit') }}
            </a>
        @endcan
        @can('people.customer_deposit.edit')
            <a href="{{ route('people.customer_deposit.edit', $a->id) }}" class="dropdown-item">
                <i class="ph ph-plus-circle me-2"></i>
                {{ __('global.add_deposit') }}
            </a>
        @endcan
        @can('people.customer.delete')
            <div class="dropdown-divider"></div>
            <a href="{{ route('people.customer.delete', $a->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
                <i class="ph ph-x text-danger me-2"></i>
                {{ __('global.delete') }}
            </a>
        @endcan
    </div>
</div>