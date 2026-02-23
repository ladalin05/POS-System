@props([
    'data' => null,
    'title' => null,
])
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0"> 
        <div class="table-responsive">
            {!! $data->table(['class' => 'table datatables no-footer'], true) !!}
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script>
        var _initDataTables = function() {
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                dom: '<"datatable-header d-flex justify-content-betweenr px-3 py-0"f<"status-filter-container">>' +
                    'rt' +
                    '<"datatable-footer d-flex justify-content-between align-items-center px-3"lp>',
                language: {
                    search: '',
                    searchPlaceholder: 'Search',
                    lengthMenu: 'Row Per Page _MENU_ Entries',
                    paginate: {
                        next: '<i class="ph ph-caret-right"></i>',
                        previous: '<i class="ph ph-caret-left"></i>'
                    }
                },
                initComplete: function() {
                    // Adding the "Status" dropdown seen in the image
                    $('.status-filter-container').html(`
                        <div class="dropdown">
                            <select id="status-filter" class="form-select form-select-sm border-light shadow-sm" style="width: 120px; border-radius: 8px;">
                                <option value="">Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    `);
                }
            });
        };
        _initDataTables();
    </script>
    {!! $data->scripts() !!}
@endpush
