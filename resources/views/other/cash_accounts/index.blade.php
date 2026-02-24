<x-app-layout>
    @push('css')

    <style>
        .card { border-radius: 12px; overflow: hidden; }
        .form-label { font-size: 0.9rem; color: #495057; font-weight: 600; }

        .permission-tree-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #e9ecef !important;
        }

        .tree-checkbox-hierarchical ul {
            list-style: none;
            padding-left: 1.5rem;
            margin-top: 0.5rem;
        }

        .tree-checkbox-hierarchical .tree-root { padding-left: 0; }

        .tree-checkbox-hierarchical li {
            position: relative;
            padding: 5px 0;
            font-size: 0.95rem;
            color: #344767;
        }

        /* Vertical lines for the tree */
        .tree-checkbox-hierarchical li::before {
            content: "";
            position: absolute;
            left: -15px;
            top: 0;
            border-left: 1px solid #dee2e6;
            height: 100%;
        }

        .tree-checkbox-hierarchical li::after {
            content: "";
            position: absolute;
            left: -15px;
            top: 15px;
            border-top: 1px solid #dee2e6;
            width: 10px;
        }

        .tree-checkbox-hierarchical li:last-child::before { height: 15px; }

        .folder > .tree-label::before {
            content: "\F2E8";
            font-family: "bootstrap-icons";
            margin-right: 8px;
            color: #ffc107;
        }

        .selected {
            color: #0d6efd !important;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }
        .btn-primary:hover {
            background-color: #3751d4;
        }
    </style>
        
    @endpush
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0" >Cash Accounts</h2>
            <span style="color: #646B72; font-size: 14px;">Manage your Accounts List</span>
        </x-slot>
        
        <div class="header-actions d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm border-0 shadow-sm" title="Export PDF">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="24" alt="PDF">
            </button>
            <button class="btn btn-outline-secondary btn-sm border-0 shadow-sm" title="Export Excel">
                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="24" alt="Excel">
            </button>
            
            <a href="{{ route('other.cash_accounts.add') }}" class="btn btn-add-user d-flex align-items-center gap-2 text-white">
                <i class="ph ph-plus-circle"></i>
                {{ __('global.add_new') }}
            </a>
        </div>
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
            function changePassword(e) {
                e.preventDefault();
                var url = $(event.target).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
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

            function permission(e) {
                e.preventDefault();
                var url = $(event.target).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#action-modal #action-form').removeClass('was-validated');
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
