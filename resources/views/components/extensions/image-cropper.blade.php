@props([
    'name' => 'avatar',
    'image' => null,
    'label' => __('global.avatar'),
    'title' => __('global.avatar')
])
<!-- Full width modal -->
<div id="modal-image-cropper" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Cropper demonstration -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="image-cropper-container mb-3" style="width: 821px; height: 821px;">
                            <img src="{{ asset('assets/images/default/female-avatar.jpg') }}" class="cropper" id="image-cropper-image" style="width: 100%;">
                        </div>
                        <div class="image-cropper-toolbar mb-3">
                            <label class="fw-semibold mb-1">{{ __('global.toolbar') }}:</label>
                            <div class="btn-group d-flex">
                                <button type="button" class="btn btn-primary btn-icon" data-method="setDragMode" data-option="move" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.move') }}">
                                    <span class="ph ph-arrows-out-cardinal"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="setDragMode" data-option="crop" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.crop') }}">
                                    <span class="ph ph-crop"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="move" data-option="-10" data-second-option="0" data-bs-popup="tooltip"
                                    data-bs-placement="top" title="{{ __('global.move_left') }}">
                                    <span class="ph ph-arrow-left"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="move" data-option="10" data-second-option="0" data-bs-popup="tooltip"
                                    data-bs-placement="top" title="{{ __('global.move_right') }}">
                                    <span class="ph ph-arrow-right"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="move" data-option="0" data-second-option="-10" data-bs-popup="tooltip"
                                    data-bs-placement="top" title="{{ __('global.move_up') }}">
                                    <span class="ph ph-arrow-up"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="move" data-option="0" data-second-option="10" data-bs-popup="tooltip"
                                    data-bs-placement="top" title="{{ __('global.move_down') }}">
                                    <span class="ph ph-arrow-down"></span>
                                </button>
                                <button type="button" class="btn btn-primary btn-icon" data-method="zoom" data-option="0.1" data-bs-popup="tooltip" data-bs-placement="top" title="{{ __('global.zoom_in') }}">
                                    <span class="ph ph-magnifying-glass-plus"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="zoom" data-option="-0.1" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.zoom_out') }}">
                                    <span class="ph ph-magnifying-glass-minus"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="rotate" data-option="-45" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.rotate_left') }}">
                                    <span class="ph ph-arrow-counter-clockwise"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="rotate" data-option="45" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.rotate_right') }}">
                                    <span class="ph ph-arrow-clockwise"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="scaleX" data-option="-1" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.flip_horizontal') }}">
                                    <span class="ph ph-arrows-left-right"></span>
                                </button>

                                <button type="button" class="btn btn-primary btn-icon" data-method="scaleY" data-option="-1" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.flip_vertical') }}">
                                    <span class="ph ph-arrows-down-up"></span>
                                </button>
                                <button type="button" class="btn btn-primary btn-icon" data-method="reset" data-option="reset" data-bs-popup="tooltip" data-bs-placement="top"
                                    title="{{ __('global.reset') }}">
                                    <span class="ph ph-arrows-clockwise"></span>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-semibold mb-1">{{ __('global.aspect_ratio') }}:</label>
                            <div class="btn-group d-flex image-cropper-ratio">
                                <!-- 1:1 aspect ratio -->
                                <input type="radio" class="btn-check" id="aspectRatio-1-1" name="aspectRatio" autocomplete="off" value="1" checked>
                                <label class="btn btn-light" for="aspectRatio-1-1">1:1</label>
                                <!-- 16:9 aspect ratio -->
                                <input type="radio" class="btn-check" id="aspectRatio-16-9" name="aspectRatio" autocomplete="off" value="1.7777777777777777">
                                <label class="btn btn-light" for="aspectRatio-16-9">16:9</label>
                                <!-- 3:4 aspect ratio -->
                                <input type="radio" class="btn-check" id="aspectRatio-3-4" name="aspectRatio" autocomplete="off" value="0.75">
                                <label class="btn btn-light" for="aspectRatio-3-4">3:4</label>
                                <!-- 4:6 aspect ratio -->
                                <input type="radio" class="btn-check" id="aspectRatio-4-6" name="aspectRatio" autocomplete="off" value="0.6666666666666666">
                                <label class="btn btn-light" for="aspectRatio-4-6">4:6</label>
                                <!-- Free aspect ratio -->
                                <input type="radio" class="btn-check" id="aspectRatio-free" name="aspectRatio" autocomplete="off" value="NaN">
                                <label class="btn btn-light" for="aspectRatio-free">Free</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="eg-preview d-lg-flex justify-content-lg-center align-items-lg-center text-center wmin-0 mb-3">
                            <div class="preview preview-lg d-lg-inline-block mt-3 mx-auto me-lg-0 mt-lg-0 ms-lg-3 overflow-hidden rounded"></div>
                            <div class="preview preview-md d-lg-inline-block mt-3 mx-auto me-lg-0 mt-lg-0 ms-lg-3 overflow-hidden rounded"></div>
                            <div class="preview preview-sm d-lg-inline-block mt-3 mx-auto me-lg-0 mt-lg-0 ms-lg-3 overflow-hidden rounded"></div>
                            <div class="preview preview-xs d-lg-inline-block mt-3 mx-auto me-lg-0 mt-lg-0 ms-lg-3 overflow-hidden rounded"></div>
                        </div>
                    </div>
                </div>
                <!-- /cropper demonstration -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="cropped-image">{{ __('global.cropped') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- /full width modal -->
<input type="file" class="d-none" id="input-image-cropper" accept="image/*">
<button type="button" class="btn btn-primary" id="open-image-cropper">{{ $label }}</button>
<input type="hidden" id="input-image-cropped" name="{{ $name }}">
{{ $slot }}

@push('scripts')
    <script src="{{ asset('assets/js/vendor/media/cropper.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let cropper;
            $('#open-image-cropper').on('click', function() {
                $('#input-image-cropper').trigger('click');
            });
            $('#input-image-cropper').on('change', function() {
                var input = this;
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#modal-image-cropper').modal('show');
                    $('.image-cropper-container img').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            });
            $('#modal-image-cropper').on('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                $('#input-image-cropper').val('');
            });
            $('#modal-image-cropper').on('shown.bs.modal', function() {
                var container = $('.image-cropper-container');
                var image = container.find('img#image-cropper-image').get(0);
                var download = $('#download');
                var actions = $('#actions');
                var dataX = $('#dataX');
                var dataY = $('#dataY');
                var dataHeight = $('#dataHeight');
                var dataWidth = $('#dataWidth');
                var dataRotate = $('#dataRotate');
                var dataScaleX = $('#dataScaleX');
                var dataScaleY = $('#dataScaleY');
                var options = {
                    aspectRatio: 1 / 1,
                    preview: '.preview',
                    ready: function(e) {
                        console.log(e.type);
                    },
                    cropstart: function(e) {
                        console.log(e.type, e.detail.action);
                    },
                    cropmove: function(e) {
                        console.log(e.type, e.detail.action);
                    },
                    cropend: function(e) {
                        console.log(e.type, e.detail.action);
                    },
                    crop: function(e) {
                        var data = e.detail;
                        dataX.value = Math.round(data.x);
                        dataY.value = Math.round(data.y);
                        dataHeight.value = Math.round(data.height);
                        dataWidth.value = Math.round(data.width);
                        dataScaleX.value = typeof data.scaleX !== 'undefined' ? data.scaleX : '';
                        dataScaleY.value = typeof data.scaleY !== 'undefined' ? data.scaleY : '';
                    },
                    zoom: function(e) {
                        console.log(e.type, e.detail.ratio);
                    }
                };
                // Create a cropper from an image
                cropper = new Cropper(image, options);

                // change aspect ratio
                $('.image-cropper-ratio input[type=radio]').each(function() {
                    $(this).change(function() {
                        options[$(this).attr('name')] = $(this).val();
                        cropper.destroy();
                        cropper = new Cropper(image, options);
                    });
                });

                $('.image-cropper-toolbar').each(function() {
                    $(this).find('.btn').each(function() {
                        var btn = $(this);
                        btn.on('click', function() {
                            var method = btn.attr('data-method');
                            var option = btn.attr('data-option');
                            var secondOption = btn.attr('data-second-option');
                            if (method == 'scaleX' || method == 'scaleY') {
                                btn.attr('data-option', option == '-1' ? '1' : '-1');
                            }
                            cropper[method](option, secondOption);
                        });
                    });
                });
            });
            $('#cropped-image').on('click', function() {
                if (cropper) {
                    // Get cropped canvas and convert to Base64
                    var croppedCanvas = cropper.getCroppedCanvas();
                    var compressedImage = croppedCanvas.toDataURL('image/jpeg');
                    if(compressedImage.length > 400000) {
                        compressedImage = cropper.getCroppedCanvas().toDataURL('image/jpeg', 0.7);
                        console.log('Compressed Image');
                    }
                    $('#input-image-cropped').val(compressedImage);
                    $('#modal-image-cropper').modal('hide');
                }
            });
        });
    </script>
@endpush
