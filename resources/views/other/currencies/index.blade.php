<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>

            @can('other.currencies.save')
                <a href="{{ route('other.currencies.save') }}" class="dropdown-item hidden" onclick="addCurrencies(event)">
                    <i class=" ph ph-plus-circle me-2"></i>
                    {{ __('global.add') }}
                </a>
                @can('other.currencies.delete')
                    <a href="{{ route('other.currencies.bulk-delete') }}" class="dropdown-item" onclick="delete_selected(event)">
                        <i class="ph ph-plus-circle me-2"></i>
                        {{ __('global.delete') }}
                    </a>
                @endcan
            @endcan

        </x-basic.option>
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