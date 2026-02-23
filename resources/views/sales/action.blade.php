<div class="d-inline-flex dropdown ms-2">
    <a href="javascript:void(0)" class="d-inline-flex align-items-center text-body dropdown-toggle"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph ph-list"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">









        @can('payment.modal')
            <a href="javascript:void(0)" class="dropdown-item" id="open-payment-modal"
                data-url="{{ route('payment.modal', $a->id) }}">
                <i class="ph ph-pencil me-2"></i> {{ __('global.add_payment') }}
            </a>
        @endcan

        @can('payment.index')
            <a href="javascript:void(0)" class="dropdown-item" id="open-payment-view"
                data-url="{{ route('payment.index', $a->id) }}">
                <i class="ph ph-pencil me-2"></i> {{ __('global.view_payment') }}
            </a>
        @endcan





        @can('sales.delete')
            <div class="dropdown-divider"></div>
            <a href="{{ route('sales.delete', $a->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
                <i class="ph ph-x text-danger me-2"></i>
                {{ __('global.delete') }}
            </a>
        @endcan





    </div>
</div>