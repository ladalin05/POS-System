<x-app-layout>
    <x-basic.breadcrumb>
        <x-slot name="title">
            <h2 class="mb-0" >{{ __('global.roles') }}</h2>
            <span style="color: #646B72; font-size: 14px;" >Manage your roles</span>
        </x-slot>
        <div class="header-actions d-flex align-items-center gap-2">
            <button class="btn btn-icon-box" style="border: none">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF">
            </button>
            <button class="btn btn-icon-box" style="border: none">
                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" alt="Excel">
            </button>
            <a href="{{ route('users-management.roles.add') }}" class="btn btn-add-user d-flex align-items-center gap-2 text-white">
                <i class="ph ph-plus-circle me-2"></i>
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
</x-app-layout>
