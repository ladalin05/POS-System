<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>
            @can('expense.expense_category.save')
                <a href="{{ route('expense.expense_category.save') }}" class="dropdown-item"
                    onclick="expenseCategory(event)">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.add_new') }}
                </a>
            @endcan

            @can('expense.expense_category.delete')
                <a href="{{ route('expense.expense_category.bulkDelete') }}" class="dropdown-item"
                    onclick="delete_selected(event)">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.delete') }}
                </a>
            @endcan
        </x-basic.option>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable">
        </x-basic.datatables>
    </div>
    <!-- /content area -->
    <x-basic.modal id="action-modal">
        <x-basic.form id="action-form" novalidate>
        </x-basic.form>
    </x-basic.modal>
    <!-- /content area -->
    @push('scripts')
        <script>
            function expenseCategory(e) {
                e.preventDefault();
                var url = $(event.target).attr('href');
                $.ajax({
                    url: url,
                    // type: 'GET',
                    success: function (res) {
                        $('#action-modal #action-form').html('').removeClass('was-validated');
                        if (res.status == 'success') {
                            $('#action-modal .modal-title').text(res.title);
                            $('#action-modal #action-form').html(res.html);
                            $('#action-modal form').attr('action', url);
                            $('#action-modal').modal('show');
                        }
                    }
                });
            }
           


        </script>
    @endpush
</x-app-layout>