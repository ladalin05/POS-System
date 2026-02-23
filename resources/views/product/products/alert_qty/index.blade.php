<x-app-layout>

    <!-- Content area -->
    <div class="content">
        <x-basic.datatables title="{{ __('global.product_list') }}" :data="$dataTable">
        </x-basic.datatables>
    </div>



</x-app-layout>