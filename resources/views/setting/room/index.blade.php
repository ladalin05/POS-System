<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>
            @can('setting.room.add')
                <a href="{{ route('setting.room.add') }}" class="dropdown-item">
                    <i class="ph ph-plus-circle me-2"></i>
                    {{ __('global.add_new') }}
                </a>
            @endcan
        </x-basic.option>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.list') }}" :data="$dataTable"></x-basic.datatables>
    </div>
    <!-- /content area -->
</x-app-layout>
