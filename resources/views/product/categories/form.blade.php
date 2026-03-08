<!-- ================= CATEGORY FORM MODAL ================= -->
<div class="modal-body">
    <form method="POST" action="{{ $action }}" id="categoryForm" enctype="multipart/form-data" onsubmit="handleCategorySubmit(event)">
        @csrf
        <div class="modal-body p-0">
            <div class="row g-2">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        Category Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control" value="{{ $form?->name }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        Slug
                    </label>
                    <input type="text" name="slug" class="form-control" value="{{ $form?->slug }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        Parent Category
                    </label>
                    <select class="form-select select2-basic" name="parent_id" >
                        <option value="">None (Top Level)</option>
                        @foreach (getCategory() as $id => $name)
                            <option value="{{ $id }}" {{ $id == $form?->parent?->id ? 'selected' : ''}}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        Category Image
                    </label>
                    <div class="text-center">
                        <div class="border rounded-3 bg-light d-flex align-items-center p-2 justify-content-center position-relative"
                            style="height: 200px; overflow:hidden;">
                            <img id="image-preview" src="{{ $form?->image ? asset($form->image) : '' }}"
                                class="img-fluid object-fit-cover h-100 {{ $form?->image ? '' : 'd-none' }}">
                            <i id="placeholder-icon"
                                class="fa-solid fa-cloud-arrow-up fs-1 text-muted {{ $form?->image ? 'd-none' : '' }}">
                            </i>
                            <button type="button" id="remove-image"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 {{ $form?->image ? '' : 'd-none' }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>

                        <input type="file" name="category_image" id="category_image"
                            class="form-control form-control-sm mt-3"
                            accept="image/*">

                        <small class="text-muted d-block mt-2">
                            Recommended: Square image | Max 2MB
                        </small>

                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">
                Cancel
            </button>

            <button type="submit"
                    class="btn btn-primary px-4">
                Save Category
            </button>
        </div>
    </form>
</div>

<script>
    function handleCategorySubmit(e) {
        e.preventDefault();
        ajaxSubmit('#categoryForm');
    }

    $(document).ready(function () {
        $('#category_id').off('change').on('change', function() {
            let categoryId = $(this).val();
            if(categoryId === "") {
                $('#sub_category_id').html('<option value="">Select  Category</option>');
                return;
            }
            $.ajax({
                url: "{{ route('get-subcategory') }}",
                type: "GET",
                data: {
                    category_id: categoryId
                },
                success: function(response) {

                    let sub = $('#sub_category_id');
                    sub.empty();
                    sub.append('<option value="">Select Sub Category</option>');

                    $.each(response, function(id, name){
                        sub.append('<option value="'+id+'">'+name+'</option>');
                    });
                }
            });
        });
        
        // ================= PREVIEW IMAGE =================
        $(document).on('change', '#category_image', function (e) {

            let file = e.target.files[0];

            if (file) {

                let reader = new FileReader();

                reader.onload = function (event) {

                    $('#image-preview')
                        .attr('src', event.target.result)
                        .removeClass('d-none');

                    $('#placeholder-icon').addClass('d-none');

                    $('#remove-image').removeClass('d-none');
                };

                reader.readAsDataURL(file);
            }

        });


        // ================= REMOVE IMAGE =================
        $(document).on('click', '#remove-image', function () {

            $('#category_image').val(''); // Clear input
            $('#image-preview').addClass('d-none').attr('src', '');
            $('#placeholder-icon').removeClass('d-none');
            $('#remove-image').addClass('d-none');

        });

    });
</script>