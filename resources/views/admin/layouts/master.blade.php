<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Web Development Company">
    <meta name="author" content="Bazaard">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title') | Refugee Chronicles</title>

    <link href="{{ asset('admin/node_modules/icheck/skins/all.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/toastr/toastr.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/Ladda/ladda.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/jqueryui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/node_modules/summernote/summernote-lite.min.css') }}" rel="stylesheet">

        <style type="text/css">
            .filters button {
                margin-right: 5px;
            }
            .has-error .help-block {
                color: red;
            }
            .dt-buttons {
                float: right;
            }
            .dropdown-menu.show {
                display: block;
                background: #edf1f5;
                border: 2px solid #cccccc;
            }

            .dropdown-menu.show a {
                display: block;
            }

        </style>
    {!!  \Assets::css() !!}

    @yield('css')

    <script type="text/javascript">
        window.base_url = '{!! url('/') !!}';
        window.initFunctions = [];
    </script>
</head>

<body class="fixed-layout skin-megna-dark">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Bazaard</p>
        </div>
    </div>
    <div id="main-wrapper">
        @include('admin.partials.header')
        @include('admin.partials.sidebar')
        <div class="page-wrapper">
            <div class="container-fluid">
                @yield('content_header')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12" style="text-align: right;">
                                        @yield('actions')
                                    </div>
                                </div>
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.partials.footer')
    </div>
    <script src="{{ asset('admin/node_modules/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->

    <script src="{{ asset('admin/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/blockUI/jquery.blockUI.js') }}"></script> <!-- Select Inputs -->
    <script src="{{ asset('admin/node_modules/select2/dist/js/select2.full.min.js') }}"></script> <!-- Select Inputs -->
    <script src="{{ asset('admin/node_modules/icheck/icheck.min.js') }}"></script> <!-- Select Inputs -->
    <script src="{{ asset('admin/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/waves.js') }}"></script>
    <script src="{{ asset('admin/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('admin/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/custom.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/Ladda/spin.min.js') }}"></script>
    <script src="{{ asset('admin/node_modules/Ladda/ladda.min.js') }}"></script>
    @include('Pearls::pearls_main')
    <script src="{{ asset('pearls/js/functions.js') }}"></script>
    <script src="{{ asset('pearls/js/main.js') }}"></script>
    <script src="{{ asset('pearls/js/pearls_functions.js') }}"></script>
    <script src="{{ asset('pearls/js/pearls_main.js') }}"></script>
    {!!  \Assets::js() !!}
    @include('admin.partials.notifications')
    @yield('js')
</body>
</html>