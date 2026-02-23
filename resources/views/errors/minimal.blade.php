<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon shortcut" href="{{ asset('assets/images/default/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Styles -->
    <link href="{{ asset('assets/icons/phosphor/regular/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" id="stylesheet" rel="stylesheet" type="text/css">

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/init.js') }}"></script>

    @include('layouts.script')

    <!-- Scripts by page -->
    @stack('scripts')

</head>

<body>
    <div class="card-overlay" id="body-overlay"><span class="ph ph-spinner-gap spinner"></span></div>
    @include('layouts.navigation')
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Inner content -->
            <div class="content-inner">

                <!-- Content area -->
                <div class="content d-flex justify-content-center align-items-center">

                    <!-- Container -->
                    <div class="flex-fill">

                        <!-- Error title -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/error_bg.svg') }}" class="img-fluid mb-3" height="230" alt="">
                            <h1 class="display-3 fw-semibold lh-1 mb-3">@yield('code')</h1>
                            <h6 class="w-md-25 mx-md-auto">Oops, an error has occurred. <br> @yield('message').</h6>
                        </div>
                        <!-- /error title -->

                        <!-- Error content -->
                        <div class="text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="ph ph-house me-2"></i>
                                Return to dashboard
                            </a>
                        </div>
                        <!-- /error wrapper -->

                    </div>
                    <!-- /container -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /inner content -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>

</html>
