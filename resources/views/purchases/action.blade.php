<div class="d-inline-flex dropdown ms-2">
    <a href="javascript:void(0)" class="d-inline-flex align-items-center text-body dropdown-toggle"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph ph-list"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @can('purchases.edit')
            <a href="{{ route('purchases.edit', $a->id) }}" class="dropdown-item">
                <i class="ph ph-pencil me-2"></i>
                {{ __('global.edit') }}
            </a>
        @endcan
         @can('purchases.approve')
            @if(empty($a->status) || $a->status !== 'approved')
                <div class="dropdown-divider"></div>
                <a href="{{ route('purchases.approve', $a->id) }}" class="dropdown-item" onclick="approvepurchases(event)">
                    <i class="ph ph-check-circle text-success me-2"></i>
                    {{ __('global.approve') }}
                </a>
            @endif
        @endcan
        @can('purchases.delete')
            <div class="dropdown-divider"></div>
            <a href="{{ route('purchases.delete', $a->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
                <i class="ph ph-x text-danger me-2"></i>
                {{ __('global.delete') }}
            </a>
        @endcan

    </div>
</div>