<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>
            @can('purchases.add')
                <a href="{{ route('purchases.add') }}" class="dropdown-item">
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

    <div class="modal fade" id="purchasesModal" tabindex="-1" aria-labelledby="purchasesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchasesModalLabel">{{ __('global.purchases') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('global.close') }}"></button>
                </div>
                <div class="modal-body" id="purchasesModalBody">
                    <div class="text-center">{{ __('global.loading') }}...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function approvepurchases(e) {
            e.preventDefault();

            const link = e.currentTarget;
            const url = link.getAttribute('href');
            const $wrapper = $(link).closest('.dataTables_wrapper'); 

            swalInit.fire({
                title: '{{ __("messages.are_you_sure") }}',
                text: '{{ __("messages.you_want_to_approve") ?? "This will create stock moves and lock this purchases." }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __("messages.yes_approve") ?? "Yes, approve" }}',
                cancelButtonText: '{{ __("messages.no_cancel") ?? "No, cancel" }}',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-danger'
                }
            }).then(function (result) {
                if (!result.isConfirmed) return;

                const originalHtml = link.innerHTML;
                link.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>{{ __("global.approving") ?? "Approving..." }}';
                link.classList.add('disabled');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.status === 'success') {
                            swalInit.fire({
                                title: '{{ __("global.approved") ?? "Approved!" }}',
                                text: response.message || '{{ __("messages.approved_success") ?? "purchases approved and stock moves created." }}',
                                icon: 'success',
                                customClass: { confirmButton: 'btn btn-success' }
                            }).then(function () {
                                if ($wrapper.length) {
                                    // Reload only the table if we are inside a DataTable
                                    $wrapper.find('table.dataTable').DataTable().ajax.reload(null, false);
                                } else if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            swalInit.fire({
                                title: 'Error',
                                text: response.message || 'Approval failed.',
                                icon: 'error',
                                customClass: { confirmButton: 'btn btn-danger' }
                            });
                            link.innerHTML = originalHtml;
                            link.classList.remove('disabled');
                        }
                    },
                    error: function (xhr) {
                        const msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong.';
                        swalInit.fire({
                            title: 'Error',
                            text: msg,
                            icon: 'error',
                            customClass: { confirmButton: 'btn btn-danger' }
                        });
                        link.innerHTML = originalHtml;
                        link.classList.remove('disabled');
                    }
                });
            });
        }
    </script>


    <!-- /content area -->
</x-app-layout>