@extends('layouts.app')
@section('title')
    {{__('admin.settings.title')}}
@endsection
@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="storeForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-12 mb-4">
            <div class="form-group">
                <label for="app_name" class="form-label">{{ __('admin.settings.app_name') }}</label>
                <input type="text" class="form-control" id="app_name" name="app_name"
                       value="{{ get_setting('app_name') }}" required>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 mb-4">
            <label for="app_logo" class="form-label">{{ __('admin.settings.app_logo') }} (Sidebar)</label>
            @php
                // Tentukan path URL logo saat ini untuk Dropify
                $currentLogoPath = get_setting('app_logo');
                $defaultLogo = $currentLogoPath ? asset('storage/' . $currentLogoPath) : '';
            @endphp

            <input type="file" name="app_logo" class="dropify" data-bs-height="180"
                   data-default-file="{{ $defaultLogo }}"
                   data-allowed-file-extensions="jpg png jpeg svg" />

            <small class="form-text text-muted">{{ __('admin.settings.app_logo_help') }} (.jpg, .png, .svg).</small>
        </div>

        <div class="col-lg-6 col-sm-12 mb-4">
            <label for="app_favicon" class="form-label">Favicon</label>
            @php
                // Tentukan path URL favicon saat ini untuk Dropify
                $currentFaviconPath = get_setting('app_favicon');
                $defaultFavicon = $currentFaviconPath ? asset('storage/' . $currentFaviconPath) : '';
            @endphp

            <input type="file" name="app_favicon" class="dropify" data-bs-height="180"
                   data-default-file="{{ $defaultFavicon }}"
                   data-allowed-file-extensions="ico png jpg" />

            <small class="form-text text-muted">{{ __('admin.settings.app_icon_help') }} (.ico, .png).</small>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">{{ __('admin.settings.save_settings') }}</button>
</form>
@endsection
@push('js')
<script src="{{asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{asset('assets/js/swalConfirm.js')}}"></script>
<script>
    // Pastikan Dropify diinisialisasi setelah DOM siap


    $(document).ready(function() {
        confirmAndSubmit('#storeForm', '{{ __('admin.settings.swal.text') }}');

        $('.dropify').dropify({
            messages: {
                'default': '{{ __('admin.settings.swal.default') }}',
                'replace': '{{ __('admin.settings.swal.replace') }}',
                'remove':  '{{ __('admin.settings.swal.remove') }}',
                'error':   '{{ __('admin.settings.swal.error') }}',
            }
        });
    });


</script>
@endpush
