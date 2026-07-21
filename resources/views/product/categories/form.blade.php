<!-- ================= CATEGORY FORM MODAL ================= -->
<div class="modal-body">
    <form method="POST" action="{{ $action }}" id="categoryForm" enctype="multipart/form-data" class="ajax-form">
        @csrf
        <div class="modal-body p-0">
            <div class="row g-2">
                <div class="col-md-12">
                    <x-forms.input
                        label="Category Name"
                        name="name"
                        id="name"
                        type="text"
                        :value="old('name', $form->name ?? '')"
                        placeholder="Enter category name"
                        required
                    />
                </div>
                <div class="col-md-12">
                    <x-forms.input
                        label="Slug"
                        name="slug"
                        id="slug"
                        type="text"
                        :value="old('slug', $form->slug ?? '')"
                        placeholder="Enter Slug"
                        required
                    />
                </div>
                <div class="col-md-12">
                    <x-forms.select
                        name="parent_id"
                        label="Parent Category"
                        :options="getCategory()"
                        placeholder="Select Parent Category"
                        :value="old('parent_id', $form->parent_id ?? '')"
                    />
                </div>
                <div class="col-md-12">
                    <div class="cf-eyebrow"><span class="cf-dot"></span> Category image</div>
                    @php
                        $hasImage  = !empty($form->image ?? null);
                        $imageUrl  = $hasImage ? $form->image : '';
                    @endphp

                    <div class="d-flex justify-content-center w-100"> 
                        <x-basic.uploader
                            input-name="image"
                            :url="old('image', $imageUrl)"
                            :path="old('image', $form->image ?? '')"
                            folder="product/categories"
                            width="200px"
                            height="150px"
                            caption="Recommended: 600×400px"
                        />
                    </div>
                </div>

            </div>
        </div>
        <div class="d-flex p-3 justify-content-end">
            <button type="button"
                    class="btn btn-light me-2"
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

    $(document).ready(function () {
        let slugManuallyEdited = {{ isset($form) && filled($form->slug) ? 'true' : 'false' }};
        function updateSlugFromName(value) {
            if (slugManuallyEdited) return;

            const slug = value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            $('#slug').val(slug);
        }

        $('#name').off('input.slug').on('input.slug', function () {
            updateSlugFromName($(this).val());
        });

        $('#slug').off('input.slug').on('input.slug', function () {
            slugManuallyEdited = true;
        });
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