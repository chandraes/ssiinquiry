<div class="page-header">
    <div>
        <h1 class="page-title">@yield('title', get_setting('app_name', config('app.name')))</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield('title', get_setting('app_name', config('app.name')))</li>
        </ol>
    </div>
    <div class="ms-auto pageheader-btn">
        @yield('addition-right-header')
        {{-- <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white me-2">
            <span>
                <i class="fe fe-plus"></i>
            </span> Add Account
        </a>
        <a href="javascript:void(0);" class="btn btn-success btn-icon text-white">
            <span>
                <i class="fe fe-log-in"></i>
            </span> Export
        </a> --}}
    </div>
</div>
