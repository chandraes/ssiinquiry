@extends('layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
@include('swal')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                @php
                    // Cek apakah user login termasuk owner atau admin
                    $isGuru = $userLogin->roles->contains('name', 'Guru');
                    $isAdmin = $userLogin->roles->contains('name', 'Administrator');
                @endphp
                @if ($isAdmin)
                    <div class="card-body">
                        <div class="card-header">Modul</div>
                        <div class="row">
                            @include('modul.create')
                            <!-- Card Tambah Modul -->
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0 hover-card"
                                    style="cursor:pointer; transition: transform 0.2s ease;"
                                    data-bs-toggle="modal" data-bs-target="#createModal">
                                    <div class="card-body py-5">
                                        <i class="fa fa-plus fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title text-primary mb-0">Buat Modul Baru</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Statistik atau Info lain -->
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-book fa-3x text-success mb-3"></i>
                                        <h5 class="card-title mb-1">Total Modul</h5>
                                        <h3 class="fw-bold text-success">{{ $modul->count() }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Card lainnya -->
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-users fa-3x text-info mb-3"></i>
                                        <h5 class="card-title mb-1">Total Owner</h5>
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
                        <div class="card-header">Kelas</div>
                        <div class="row">
                            @include('kelas.create')
                            <!-- Card Tambah Modul -->
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0 hover-card"
                                    style="cursor:pointer; transition: transform 0.2s ease;"
                                    data-bs-toggle="modal" data-bs-target="#createModalKelas">
                                    <div class="card-body py-5">
                                        <i class="fa fa-plus fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title text-primary mb-0">Buat Kelas Baru</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Statistik atau Info lain -->
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-book fa-3x text-success mb-3"></i>
                                        <h5 class="card-title mb-1">Total Kelas</h5>
                                        <h3 class="fw-bold text-success">{{ $data->count() }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Card lainnya -->
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-5">
                                        <i class="fa fa-users fa-3x text-info mb-3"></i>
                                        <h5 class="card-title mb-1">Total Peserta</h5>
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
            placeholder: 'Pilih Alat Ukur...',
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
            placeholder: 'Pilih owner...',
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
                title: 'Simpan Data?',
                text: "Pastikan semua data sudah benar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
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
            placeholder: 'Pilih Guru Pengajar...',
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
                title: 'Simpan Data?',
                text: "Pastikan semua data sudah benar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#storeFormKelas').submit();
                }
            });
        });
    });
</script>
@endpush
