@extends('layouts.app')
@section('title')
    {{-- [DIUBAH] --}}
    {{ __('admin.dashboard.title') }}
@endsection
@section('content')
@include('swal')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- [DIUBAH] --}}
                <div class="card-header">{{ __('admin.dashboard.header') }}</div>
                @php
                    // Cek apakah user login termasuk owner atau admin
                    $isGuru = $userLogin->roles->contains('name', 'Guru');
                    $isAdmin = $userLogin->roles->contains('name', 'Administrator');
                @endphp
                @if ($isAdmin)
                    <div class="card-body">
                        {{-- [DIUBAH] --}}
                        <div class="card-header">{{ __('admin.dashboard.module_title') }}</div>
                        <div class="row">
                            @include('modul.create')
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0 hover-card"
                                    style="cursor:pointer; transition: transform 0.2s ease;"
                                    data-bs-toggle="modal" data-bs-target="#createModal">
                                    <div class="card-body py-5">
                                        <i class="fa fa-plus fa-3x text-primary mb-3"></i>
                                        {{-- [DIUBAH] --}}
                                        <h5 class="card-title text-primary mb-0">{{ __('admin.dashboard.create_module') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-book fa-3x text-success mb-3"></i>
                                        {{-- [DIUBAH] --}}
                                        <h5 class="card-title mb-1">{{ __('admin.dashboard.total_modules') }}</h5>
                                        <h3 class="fw-bold text-success">{{ $modul->count() }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-users fa-3x text-info mb-3"></i>
                                        {{-- [DIUBAH] --}}
                                        <h5 class="card-title mb-1">{{ __('admin.dashboard.total_owners') }}</h5>
                                        <h3 class="fw-bold text-info">
                                            {{ $modul->pluck('owners')->flatten()->unique('id')->count() }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($isGuru || $isAdmin)
                    <div class="card-body">
                        {{-- [DIUBAH] --}}
                        <div class="card-header">{{ __('admin.dashboard.class_title') }}</div>
                        <div class="row">
                            @include('kelas.create')
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0 hover-card"
                                    style="cursor:pointer; transition: transform 0.2s ease;"
                                    data-bs-toggle="modal" data-bs-target="#createModalKelas">
                                    <div class="card-body py-5">
                                        <i class="fa fa-plus fa-3x text-primary mb-3"></i>
                                        {{-- [DIUBAH] --}}
                                        <h5 class="card-title text-primary mb-0">{{ __('admin.dashboard.create_class') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-book fa-3x text-success mb-3"></i>
                                        {{-- [DIUBAH] --}}
                                        <h5 class="card-title mb-1">{{ __('admin.dashboard.total_classes') }}</h5>
                                        <h3 class="fw-bold text-success">{{ $data->count() }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-users fa-3x text-info mb-3"></i>
                                        {{-- [DIUBAH] --}}
                                        <h5 class="card-title mb-1">{{ __('admin.dashboard.total_participants') }}</h5>
                                        <h3 class="fw-bold text-info">
                                            {{ $data->pluck('peserta')->flatten()->unique('id')->count() }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
// Inisialisasi Select2
    $(document).ready(function() {
        $('#phyphox_id').select2({
            dropdownParent: $('#createModal'),
            placeholder: '{{ __("admin.placeholders.select_phyphox") }}',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-phyphox') }}', // route pencarian user
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#owner').select2({
            dropdownParent: $('#createModal'),
            {{-- [DIUBAH] --}}
            placeholder: '{{ __("admin.placeholders.select_owner") }}',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-guru') }}', // route pencarian user
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.name
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // SweetAlert konfirmasi sebelum submit
        $('#btnCreate').on('click', function() {
            Swal.fire({
                {{-- [DIUBAH] --}}
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#storeForm').submit();
                }
            });
        });
    });

    // Inisialisasi Select2
    $(document).ready(function() {
        $('#guru_id').select2({
            dropdownParent: $('#createModalKelas'),
            {{-- [DIUBAH] --}}
            placeholder: '{{ __("admin.placeholders.select_teacher") }}',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-pengajar') }}', // route pencarian user
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.name
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // SweetAlert konfirmasi sebelum submit
        $('#btnCreateKelas').on('click', function() {
            Swal.fire({
                {{-- [DIUBAH] --}}
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#storeFormKelas').submit();
                }
            });
        });
    });
</script>
@endpush
