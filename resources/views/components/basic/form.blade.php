<form {{ $attributes->merge(['class' => 'needs-validation', 'enctype' => 'multipart/form-data', 'action' => '', 'method' => 'POST']) }}>
    @csrf
    {{ $slot }}
</form>
@push('scripts')
    <script src="{{ asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
    <script>
        var prossesing = false;
        $(document).ready(function() {
            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
                if (prossesing) {
                    return;
                }
                prossesing = true;
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var data = new FormData(form[0]);
                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        prossesing = false;
                        if (res.status == 'success') {
                            swalInit.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.message,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            if (res.redirect == 'modal') {
                                $(`#${res.modal}`).modal('hide');
                            } else {
                                setTimeout(function() {
                                    window.location = res.redirect;
                                }, 1200);
                            }
                        } else {
                            swalInit.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: res.message || 'Something went wrong!',
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr) {
                        prossesing = false;
                        var errors = xhr.responseJSON.errors;
                        var message = '';
                        $.each(errors, function(key, value) {
                            message += value + '<br>';
                        });
                        swalInit.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: message || 'Something went wrong!',
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });
    </script>
@endpush
