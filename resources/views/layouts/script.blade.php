<script>
    $(document).ready(function() {
        $('#body-overlay').hide();
        $('.nav-item-submenu').each(function() {
            if ($(this).find('.nav-link.active').length > 0) {
                $(this).addClass('nav-item-open');
                $(this).find('.nav-group-sub').addClass('show');
            }
        });
        $(document).find('select.multiple-select').multiselect();
        var forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
    const swalInit = swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-light',
            denyButton: 'btn btn-light',
            input: 'form-control'
        }
    });

    function loading(e) {
        if (e == 'stop') {
            $('#body-overlay').hide();
        } else {
            $('#body-overlay').show();
        }
    }

    function logout() {
        swalInit.fire({
            title: '{{ __('messages.are_you_sure') }}',
            text: '{{ __('messages.you_want_to_logout') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('messages.yes_logout') }}',
            cancelButtonText: '{{ __('messages.no_cancel') }}',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then(function(result) {
            if (result.value) {
                // 2.5s delay
                setTimeout(function() {
                    document.getElementById('logout-form').submit();
                }, 300);
            }
        });
    }

    function deleteRecord(e) {
        e.preventDefault();
        const el = $(e.target);
        const url = el.attr('href');
        swalInit.fire({
            title: '{{ __('messages.are_you_sure') }}',
            text: '{{ __('messages.you_want_to_delete') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('messages.yes_delete') }}',
            cancelButtonText: '{{ __('messages.no_cancel') }}',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            swalInit.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then(function() {
                                if (el.closest('.dataTables_wrapper').length) {
                                    el.closest('.dataTables_wrapper').find('table.datatables').DataTable().ajax.reload();
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            swalInit.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    error: function(e) {
                        swalInit.fire({
                            title: 'Error!',
                            text: 'Something went wrong',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                });
            }
        });
    }

    function clearCache() {
        swalInit.fire({
            title: '{{ __('messages.are_you_sure') }}',
            text: 'Are you sure you want to clear cache',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('messages.yes_clear') }}',
            cancelButtonText: '{{ __('messages.no_cancel') }}',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then(function(result) {
            if (result.value) {
                setTimeout(function() {
                    window.location.href = '{{ route('clear-cache') }}';
                }, 300);
            }
        });
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            loading();
        },
        complete: function(e) {
            loading('stop');
            if (e.status === 403) {
                window.location.href = '/403';
            }
        }
    });

    function changeLanguage(lang) {
        setTimeout(function() {
            $('#change-language-locale').val(lang);
            document.getElementById('change-language-form').submit();
        }, 300);
    }

    function copyToClipboard(e) {
        e.preventDefault();
        const text = $(e.target).attr('clipboard-text');
        console.log(text);
        navigator.clipboard.writeText(text).then(() => {
            swalInit.fire({
                text: 'Copied to clipboard',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'top-end',
                timer: 1000
            });
        });
    }
</script>
