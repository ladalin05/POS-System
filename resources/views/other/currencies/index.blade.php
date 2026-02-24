<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0" >Currencies</h2>
            <span style="color: #646B72; font-size: 14px;">Manage your Currencies List</span>
        </x-slot>
        
        <div class="header-actions d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm border-0 shadow-sm" title="Export PDF">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="24" alt="PDF">
            </button>
            <button class="btn btn-outline-secondary btn-sm border-0 shadow-sm" title="Export Excel">
                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="24" alt="Excel">
            </button>
            
            <a href="{{ route('other.currencies.add') }}" onclick="addCurrencies(event)" class="btn btn-add-user d-flex align-items-center gap-2 text-white">
                <i class="ph ph-plus-circle"></i>
                {{ __('global.add_new') }}
            </a>
        </div>
    </x-basic.breadcrumb>
    <div class="content">
        <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable">
        </x-basic.datatables>
    </div>
    <x-basic.modal id="action-modal" size="modal-xl">
        <x-basic.form id="action-form" novalidate>
        </x-basic.form>
    </x-basic.modal>
    @push('scripts')
        <script>

            function addCurrencies(e) {
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


            function editcurrencies(e) {
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

       
            function delete_selected(e) {
                e.preventDefault();
                const ids = $('.row-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();

                swalInit.fire({
                    title: '{{ __('messages.are_you_sure') }}',
                    text: '{{ __('messages.you_want_to_delete') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.no_cancel') }}',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger'
                    }
                }).then(function (result) {
                    if (ids.length === 0) return;
                    $.ajax({
                        url: "{{ route('other.currencies.bulk-delete') }}",
                        type: "POST",
                        data: {
                            ids: ids,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            if (res.status === 'success') {
                                $('#currencies-table').DataTable().ajax.reload();
                                $('#delete-selected').prop('disabled', true);
                                swalInit.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    html: res.message
                                });
                            }

                        },
                        error: function (err) {
                            alert('Something went wrong.');
                        }
                    });

                });
            }

        </script>
    @endpush
</x-app-layout>