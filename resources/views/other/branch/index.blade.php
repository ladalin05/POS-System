<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0">Branch List</h2>
            <span style="color: #646B72; font-size: 14px;">Manage your organization branches</span>
        </x-slot>
        
        <div class="header-actions d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm border-0 shadow-sm" title="Export PDF">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="24" alt="PDF">
            </button>
            <button class="btn btn-outline-secondary btn-sm border-0 shadow-sm" title="Export Excel">
                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="24" alt="Excel">
            </button>
            
            <a href="{{ route('other.branch.add') }}" onclick="openBranchModal(event)" class="btn btn-add-user d-flex align-items-center gap-2 text-white">
                <i class="ph ph-plus-circle"></i>
                {{ __('global.add_new') }}
            </a>
        </div>
    </x-basic.breadcrumb>

    <div class="content pt-0">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-0">
                <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable">
                </x-basic.datatables>
            </div>
        </div>
    </div>

    <x-basic.modal id="action-modal" size="modal-lg">
        <x-basic.form id="action-form" novalidate>
            </x-basic.form>
    </x-basic.modal>

    @push('scripts')
    <script>
        /**
         * Open Modal for Add or Edit
         */
        function openBranchModal(e) {
            e.preventDefault();
            const btn = $(e.currentTarget);
            const url = btn.attr('href');
            const modal = $('#action-modal');
            const form = $('#action-form');

            // Show loading spinner in modal
            form.html(`
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading details...</p>
                </div>
            `);
            modal.modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {
                    if (res.status === 'success') {
                        modal.find('.modal-title').text(res.title || 'Branch Details');
                        form.html(res.html).removeClass('was-validated');
                        form.attr('action', url);
                        
                        // Re-initialize any plugins (Select2, etc.) if they exist in the injected HTML
                        if ($.fn.select2) {
                            $('.select').select2({ dropdownParent: modal });
                        }
                    } else {
                        form.html('<div class="alert alert-danger m-3">Error: ' + res.message + '</div>');
                    }
                },
                error: function() {
                    form.html('<div class="alert alert-danger m-3">Could not connect to server.</div>');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>