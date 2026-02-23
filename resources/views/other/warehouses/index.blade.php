<x-app-layout>
    <style>
        /* Modern Header Refinement */
        .breadcrumb-container {
            background: #fff;
            padding: 1.5rem;
            border-bottom: 1px solid #edf2f7;
            margin-bottom: 20px;
        }
        
        /* Icon Button Styling */
        .btn-export {
            background-color: #f8f9fa;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .btn-export:hover {
            background-color: #edf2f7;
            transform: translateY(-1px);
        }

        .btn-export img {
            width: 20px;
            height: 20px;
        }

        /* Add New Button */
        .btn-add-primary {
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
            transition: 0.3s;
        }

        .btn-add-primary:hover {
            background: #4338ca;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        /* Table Card Styling */
        .content {
            padding: 0 1.5rem;
        }
        
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>

    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0" >Warehouse List</h2>
            <span style="color: #646B72; font-size: 14px;">Manage your warehouse</span>
        </x-slot>
        <div class="header-actions d-flex align-items-center gap-2">
            <button class="btn btn-icon-box" style="border: none">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF">
            </button>
            <button class="btn btn-icon-box" style="border: none">
                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" alt="Excel">
            </button>
            <a href="{{ route('other.warehouses.add') }}" onclick="addwarehouses(event)" class="btn btn-add-user d-flex align-items-center gap-2 text-white">
                <i class="ph ph-plus-circle me-2"></i>
                {{ __('global.add_new') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content">
        <div class="card card-custom">
            <div class="card-body p-0">
                <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable">
                </x-basic.datatables>
            </div>
        </div>
    </div>

    <x-basic.modal id="action-modal" size="modal-xl">
        <x-basic.form id="action-form" novalidate>
        </x-basic.form>
    </x-basic.modal>

    @push('scripts')
        <script>
            // Added a small UI feedback for AJAX loading
            function showLoading() {
                // You could trigger a spinner here
            }

            function addwarehouses(e) {
                e.preventDefault();
                var url = $(e.currentTarget).attr('href'); // Changed to currentTarget for better reliability
                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: showLoading,
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

            function editwarehouses(e) {
                e.preventDefault();
                var url = $(e.currentTarget).attr('href');
                $.ajax({
                    url: url,
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

            function delete_selected(e) {
                e.preventDefault();
                const ids = $('.row-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    swalInit.fire('No Selection', 'Please select at least one warehouse.', 'info');
                    return;
                }

                swalInit.fire({
                    title: '{{ __('messages.are_you_sure') }}',
                    text: '{{ __('messages.you_want_to_delete') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.no_cancel') }}',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger px-4 mx-2',
                        cancelButton: 'btn btn-light px-4 mx-2'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('other.warehouses.bulk-delete') }}",
                            type: "POST",
                            data: {
                                ids: ids,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (res) {
                                if (res.status === 'success') {
                                    $('#warehouses-table').DataTable().ajax.reload();
                                    $('#delete-selected').prop('disabled', true);
                                    swalInit.fire('Deleted!', res.message, 'success');
                                }
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>