@extends('layouts.admin')
@section('title')
    Settings
@endsection
@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="storeForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-12 mb-4">
            <div class="form-group">
                <label for="app_name" class="form-label">Nama Aplikasi</label>
                <input type="text" class="form-control" id="app_name" name="app_name"
                       value="{{ get_setting('app_name') }}" required>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 mb-4">
            <label for="app_logo" class="form-label">Logo Aplikasi (Sidebar)</label>
            @php
                // Tentukan path URL logo saat ini untuk Dropify
                $currentLogoPath = get_setting('app_logo');
                $defaultLogo = $currentLogoPath ? asset('storage/' . $currentLogoPath) : '';
            @endphp

            <input type="file" name="app_logo" class="dropify" data-bs-height="180"
                   data-default-file="{{ $defaultLogo }}"
                   data-allowed-file-extensions="jpg png jpeg svg" />

            <small class="form-text text-muted">Maksimal 2MB (JPG, PNG, SVG).</small>
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

            <small class="form-text text-muted">Maksimal 50KB (ICO, PNG).</small>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Pengaturan</button>
</form>
@endsection
@push('js')
<script src="{{asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{asset('assets/js/swalConfirm.js')}}"></script>
<script>
    // Pastikan Dropify diinisialisasi setelah DOM siap


    $(document).ready(function() {
        confirmAndSubmit('#storeForm', 'Yakin ingin menyimpan pengaturan?');

        $('.dropify').dropify({
            messages: {
                'default': 'Seret dan lepas file di sini atau klik',
                'replace': 'Seret dan lepas atau klik untuk mengganti',
                'remove':  'Hapus',
                'error':   'Terjadi kesalahan.'
            }
        });
    });


</script>
@endpush
