<x-app-layout>
    <x-basic.breadcrumb>
        <x-basic.option>




        </x-basic.option>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.stock_count') }}" :data="$dataTable">
            
        </x-basic.datatables>
    </div>





</x-app-layout>