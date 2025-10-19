<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- META DATA -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        {{-- <meta name="description" content="Zanex – Bootstrap  Admin & Dashboard Template"> --}}
        {{-- <meta name="author" content="Spruko Technologies Private Limited"> --}}
        {{-- <meta name="keywords" content="admin, dashboard, dashboard ui, admin dashboard template, admin panel dashboard, admin panel html, admin panel html template, admin panel template, admin ui templates, administrative templates, best admin dashboard, best admin templates, bootstrap 4 admin template, bootstrap admin dashboard, bootstrap admin panel, html css admin templates, html5 admin template, premium bootstrap templates, responsive admin template, template admin bootstrap 4, themeforest html"> --}}
        @vite(['resources/sass/app.scss'])
        <!-- FAVICON -->
        @php
            $faviconPath = get_setting('app_favicon');
            $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
        @endphp

        <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}" />

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- TITLE -->
        <title>@yield('title', get_setting('app_name', config('app.name')))</title>

        <!-- BOOTSTRAP CSS -->
        <link id="style" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />

        <!-- STYLE CSS -->
        <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" />
        <script src="{{asset('assets/js/sweetalert.js')}}"></script>
        <!--- FONT-ICONS CSS -->
        <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" />
        @stack('css')
    </head>

    <body class="app sidebar-mini ltr light-mode">
        @include('swal')
        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            <img src="{{asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOBAL-LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="page-main">

                <!-- app-Header -->
                <div class="app-header header sticky">
                    <div class="container-fluid main-container">
                        <div class="d-flex align-items-center">
                            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0);"></a>
                            @php
                                $logoPath = get_setting('app_logo');
                                $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('assets/images/brand/logo.png');
                            @endphp
                            <div class="responsive-logo">
                                <a href="index.html" class="header-logo">
                                    <img src="{{ $logoUrl }}" class="mobile-logo logo-1" alt="logo" height="50px">
                                    <img src="{{ $logoUrl }}" class="mobile-logo dark-logo-1" alt="logo" height="50px">
                                </a>
                            </div>
                            <!-- sidebar-toggle-->
                            <a class="logo-horizontal " href="index.html">
                                <img src="{{ $logoUrl }}" class="header-brand-img desktop-logo" alt="logo">
                                <img src="{{ $logoUrl }}" class="header-brand-img light-logo1"
                                    alt="logo">
                            </a>
                            <!-- LOGO -->

                            @include('layouts.partials.sidebar-right')
                        </div>
                    </div>
                </div>
                <!-- /app-Header -->

                <!--APP-SIDEBAR-->
                @include('layouts.partials.sidebar')
                <!--/APP-SIDEBAR-->

                <!--app-content open-->
                <div class="main-content app-content mt-0">
                    <div class="side-app">

                        <!-- CONTAINER -->
                        <div class="main-container container-fluid">

                            <!-- PAGE-HEADER -->
                            @include('layouts.partials.header-content')
                            <!-- PAGE-HEADER END -->
                            @yield('content')

                        </div>
                        <!-- CONTAINER END -->
                    </div>
                </div>
                <!--app-content end-->
            </div>
            <!--/Sidebar-right-->

            <!-- FOOTER -->
            <footer class="footer">
                <div class="container">
                    <div class="row align-items-center flex-row-reverse">
                        <div class="col-md-12 col-sm-12 text-center">
                            Copyright © <span id="year"></span> by <a href="javascript:void(0);"> SSI Inquiry </a> All rights reserved
                        </div>
                    </div>
                </div>
            </footer>
            <!-- FOOTER END -->
        </div>

        <!-- BACK-TO-TOP -->
        <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

        <!-- JQUERY JS -->
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>

        <!-- BOOTSTRAP JS -->
        <script src="{{asset('assets/plugins/bootstrap/js/popper.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

        <!-- SPARKLINE JS-->
        <script src="{{asset('assets/js/jquery.sparkline.min.js')}}"></script>

        <!-- CHART-CIRCLE JS-->
        <script src="{{asset("assets/js/circle-progress.min.js")}}"></script>

        <!-- CHARTJS CHART JS-->
        <script src="{{asset('assets/plugins/chart/Chart.bundle.js')}}"></script>
        <script src="{{asset("assets/plugins/chart/utils.js")}}"></script>

        <!-- PIETY CHART JS-->
        <script src="{{asset("assets/plugins/peitychart/jquery.peity.min.js")}}"></script>
        <script src="{{asset("assets/plugins/peitychart/peitychart.init.js")}}"></script>

        <!-- INTERNAL SELECT2 JS -->
        <script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>

        <!-- SELECT2 CSS -->
        <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

        <!-- SELECT2 Bootstrap 5 theme -->
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

        <!-- Bootstrap CSS (wajib jika belum ada) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />


        <!-- INTERNAL Data tables js-->
        <script src="{{asset("assets/plugins/datatable/js/jquery.dataTables.min.js")}}"></script>
        <script src="{{asset("assets/plugins/datatable/js/dataTables.bootstrap5.js")}}"></script>
        <script src="{{asset("assets/plugins/datatable/dataTables.responsive.min.js")}}"></script>

        <!-- ECHART JS-->
        <script src="{{asset('assets/plugins/echarts/echarts.js')}}"></script>

        <!-- SIDE-MENU JS-->
        <script src="{{asset('assets/plugins/sidemenu/sidemenu.js')}}"></script>

        <!-- Sticky js -->
        <script src="{{asset('assets/js/sticky.js')}}"></script>

        <!-- SIDEBAR JS -->
        <script src="{{asset('assets/plugins/sidebar/sidebar.js')}}"></script>

        <!-- Perfect SCROLLBAR JS-->
        <script src="{{asset('assets/plugins/p-scroll/perfect-scrollbar.js')}}"></script>
        <script src="{{asset('assets/plugins/p-scroll/pscroll.js')}}"></script>
        {{-- <script src="{{asset('assets/plugins/p-scroll/pscroll-1.js')}}"></script> --}}

        <!-- APEXCHART JS -->
        <script src="{{asset('assets/js/apexcharts.js')}}"></script>

        <!-- INDEX JS -->
        <script src="{{asset('assets/js/index1.js')}}"></script>

        <!-- Color Theme js -->
        <script src="{{asset('assets/js/themeColors.js')}}"></script>

        <!-- swither styles js -->
        <script src="{{asset('assets/js/swither-styles.js')}}"></script>

        <!-- CUSTOM JS -->
        <script src="{{asset('assets/js/custom.js')}}"></script>
        @stack('js')
    </body>

</html>
