<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>


            @can('pos.index')
                <a href="{{ route('pos.index') }}" class="dropdown-item">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.add_new') }}
                </a>
            @endcan


        </x-basic.option>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.sales') }}" :data="$dataTable">
        </x-basic.datatables>
    </div>

    <div class="modal fade" id="salesModal" tabindex="-1" aria-labelledby="salesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesModalLabel">{{ __('global.sales') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('global.close') }}"></button>
                </div>
                <div class="modal-body" id="salesModalBody">
                    <div class="text-center">{{ __('global.loading') }}...</div>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment Modal (single shared container) -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="paymentModalBody">
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="paymentView" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">view payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="paymentViewBody">
                </div>
            </div>
        </div>
    </div>



    <script>
        // Submit payment form via AJAX
        $(document).on('submit', '#paymentForm', function (e) {
            e.preventDefault();

            var form = this;
            var formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function (res) {
                    if (res.status === 'success') {
                        showAlert('success', res.message);

                        // Close modal
                        $('#paymentModal').modal('hide');

                        // Reload datatable or page
                        if (window.LaravelDataTables && LaravelDataTables.dataTableBuilder) {
                            LaravelDataTables.dataTableBuilder.ajax.reload();
                        } else {
                            location.reload();
                        }
                    } else {
                        showAlert('error', res.message);
                    }
                },

                error: function (xhr) {
                    let msg = xhr.responseJSON?.message ?? 'Server error';
                    showAlert('error', msg);
                }
            });
        });



        function showAlert(type, message) {
            var html = `
        <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show"
             role="alert" style="position:fixed; top:20px; right:20px; z-index:10000;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;

            $('body').append(html);

            setTimeout(() => {
                $('.alert').alert('close');
            }, 4000);
        }

    </script>

</x-app-layout>