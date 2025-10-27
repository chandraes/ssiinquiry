<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- ... (head Anda tidak berubah) ... --}}
    @vite(['resources/sass/app.scss'])
    @php
    $faviconPath = get_setting('app_favicon');
    $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
    $logoPath = get_setting('app_logo');

    $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('assets/images/brand/logo.png');
    @endphp
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', get_setting('app_name', config('app.name')))</title>
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/js/sweetalert.js')}}"></script>
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/plugins/tinymce/tinymce.min.js')}}" referrerpolicy="origin" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    @stack('css')
</head>

<body class="app sidebar-mini ltr light-mode">
    @include('swal')
    <div id="global-loader">
        {{-- [DIUBAH] alt text --}}
        <img src="{{asset('assets/images/loader.svg')}}" class="loader-img" alt="{{ __('admin.loader_alt') }}">
    </div>
    <div class="page">
        <div class="page-main">

            <div class="app-header header sticky">
                <div class="container-fluid main-container">
                    <div class="d-flex align-items-center">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar"
                            href="javascript:void(0);"></a>

                        {{-- [PERBAIKAN DI SINI] --}}
                        {{-- Blok @php yang lama dihapus --}}
                        {{-- Kita hanya mendefinisikan $logoUrl yang dibutuhkan oleh header --}}


                        <div class="responsive-logo">
                            <a href="index.html" class="header-logo">
                                {{-- [DIUBAH] alt text --}}
                                <img src="{{ $logoUrl }}" class="mobile-logo logo-1" alt="{{ __('admin.logo_alt') }}"
                                    height="50px">
                                <img src="{{ $logoUrl }}" class="mobile-logo dark-logo-1"
                                    alt="{{ __('admin.logo_alt') }}" height="50px">
                            </a>
                        </div>
                        <a class="logo-horizontal " href="index.html">
                            {{-- [DIUBAH] alt text --}}
                            <img src="{{ $logoUrl }}" class="header-brand-img desktop-logo"
                                alt="{{ __('admin.logo_alt') }}">
                            <img src="{{ $logoUrl }}" class="header-brand-img light-logo1"
                                alt="{{ __('admin.logo_alt') }}">
                        </a>
                        @include('layouts.partials.sidebar-right')
                    </div>
                </div>
            </div>

            {{-- Sidebar baru Anda dimuat di sini dan tidak lagi membutuhkan variabel $moduls --}}
            @include('layouts.partials.sidebar')

            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        @include('layouts.partials.header-content')
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 text-center">
                        {{-- [DIUBAH] Teks footer --}}
                        {!! __('admin.footer_copyright', ['year' => '<span id="year"></span>']) !!}
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- ... (Semua script Anda tidak berubah) ... --}}

    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset(" assets/js/circle-progress.min.js")}}"></script>
    <script src="{{asset('assets/plugins/chart/Chart.bundle.js')}}"></script>
    <script src="{{asset(" assets/plugins/chart/utils.js")}}"></script>
    <script src="{{asset(" assets/plugins/peitychart/jquery.peity.min.js")}}"></script>
    <script src="{{asset(" assets/plugins/peitychart/peitychart.init.js")}}"></script>
    <script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <script src="{{asset(" assets/plugins/datatable/js/jquery.dataTables.min.js")}}"></script>
    <script src="{{asset(" assets/plugins/datatable/js/dataTables.bootstrap5.js")}}"></script>
    <script src="{{asset(" assets/plugins/datatable/dataTables.responsive.min.js")}}"></script>
    <script src="{{asset('assets/plugins/echarts/echarts.js')}}"></script>
    <script src="{{asset('assets/plugins/sidemenu/sidemenu.js')}}"></script>
    <script src="{{asset('assets/js/sticky.js')}}"></script>
    <script src="{{asset('assets/plugins/sidebar/sidebar.js')}}"></script>
    <script src="{{asset('assets/plugins/p-scroll/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/plugins/p-scroll/pscroll.js')}}"></script>
    <script src="{{asset('assets/js/apexcharts.js')}}"></script>
    <script src="{{asset('assets/js/index1.js')}}"></script>
    <script src="{{asset('assets/js/themeColors.js')}}"></script>
    <script src="{{asset('assets/js/swither-styles.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    @stack('js')
</body>

</html>
