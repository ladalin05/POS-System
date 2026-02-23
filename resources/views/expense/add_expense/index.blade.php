<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>
            @can('expense.add_expense.add')
                <a href="{{ route('expense.add_expense.add') }}" class="dropdown-item">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.add_new') }}
                </a>
            @endcan
        </x-basic.option>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable">
        </x-basic.datatables>
    </div>

    <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseModalLabel">{{ __('global.expense') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('global.close') }}"></button>
                </div>
                <div class="modal-body" id="expenseModalBody">
                    <div class="text-center">{{ __('global.loading') }}...</div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>