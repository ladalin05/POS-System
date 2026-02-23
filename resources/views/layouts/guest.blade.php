<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon shortcut" href="{{ asset('assets/images/default/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Styles -->
    <link href="{{ asset('assets/icons/phosphor/regular/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/login.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/init.js') }}"></script> 

    @include('layouts.script')

    <!-- Scripts by page -->
    @stack('scripts')

</head>

<body>
    <div class="card-overlay" id="body-overlay"><span class="ph ph-spinner-gap spinner"></span></div>
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Inner content -->
            <div class="content-inner">
                {{ $slot }}
            </div>
            <!-- /inner content -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
    <script>
        $(document).ready(function() {
            $('.password-toggle').click(function() {
                var $input = $(this).siblings('input');
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                } else {
                    $input.attr('type', 'password');
                }
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
</body>

</html>
