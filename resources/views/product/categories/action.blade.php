<div class="dropdown">
    <button class="btn btn-light btn-icon btn-sm dropdown-toggle hide-arrow shadow-sm" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            style="border-radius: 8px; padding: 0.5rem;">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2" style="min-width: 160px; border-radius: 12px;">
        
        @can('products.categories.edit')
            <a href="{{ route('products.categories.edit', ['id' => $row->id]) }}" onclick="editCategory(event)" class="dropdown-item py-2 px-3 d-flex align-items-center">
                <div class="bg-primary-subtle rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="ph ph-pencil me-2"></i>
                </div>
                {{ __('global.edit') }}
            </a>
        @endcan

        @can('products.categories.delete')
            @can('products.categories.edit')
                <div class="dropdown-divider my-1 opacity-50"></div>
            @endcan

            <a href="javascript:void(0);" data-id="{{ $row->id }}" 
               class="dropdown-item py-2 px-3 d-flex align-items-center text-danger data_remove">
                <div class="bg-danger-subtle rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="ph ph-trash text-danger"></i>
                </div>
                <span class="fw-medium">{{ __('global.delete') }}</span>
            </a>
        @endcan

    </div>
</div>

<script>
    
    $(document).on("click", ".data_remove", function(e){

        e.preventDefault(); // 🔥 IMPORTANT

        let id = $(this).data("id");

        Swal.fire({
            title: '{{ __("global.dlt_warning") }}',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                cancelButton: 'btn btn-secondary'
            }

        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('products.categories.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            successAlert(response.message);
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        }

                        if (response.status === 'error') {
                            errorAlert(response.message || 'An error occurred');
                        }
                    }
                });

            }

        });

    });
</script>