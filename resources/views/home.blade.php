@extends('layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
@include('swal')

<div class="main-container container-fluid">
    <div class="col-12">
        @php
            // Cek apakah user login termasuk owner atau admin
            $isGuru = $userLogin->roles->contains('name', 'Guru');
            $isAdmin = $userLogin->roles->contains('name', 'Administrator');
            $isSiswa = $userLogin->roles->contains('name', 'Siswa');
        @endphp
        @if($isSiswa)
            <!-- ROW-3 OPEN -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">MODUL DAN KELAS</h3>
                        </div>
                        @if($modul->isEmpty())
                            <div class="card-body">
                                <div class="alert alert-info" role="alert">
                                    Tidak ada modul tersedia. Silakan tambahkan modul baru.
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="row">
                                @foreach( $modul as $m )
                                    @include('kelas.create')
                                    <div class="col-md-4 col-xl-4">
                                        <div class="card bg-info-transparent mb-4 shadow-md border-0">
                                            <div class="card-status bg-blue br-tr-7 br-tl-7"></div>
                                            <div class="card-header">
                                                <h3 class="card-title">{{$m->judul_id}}</h3>
                                                @if($isAdmin || $isGuru)
                                                    <div class="card-options">
                                                        <a type="button" data-bs-toggle="modal" data-bs-target="#createModalKelas"
                                                            class="btn btn-success btn-icon text-white">
                                                            <span>
                                                                <i class="fe fe-plus"></i>
                                                            </span> Tambah Kelas
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row card-body text-center justify-content-center">
                                                {{-- Pastikan $m->modul tidak null sebelum mengakses relasi --}}
                                                {{-- Pemeriksaan isNotEmpty() dilakukan pada relasi Phyphox yang ada di dalam Modul --}}
                                                @if ($m->relatedPhyphox->isNotEmpty())
                                                    {{-- Loop melalui relasi kustom relatedPhyphox --}}
                                                    @foreach ($m->relatedPhyphox as $phyphox)
                                                        <div class="col-md-6">
                                                            {{-- TAMBAHKAN KELAS BACKGROUND DI SINI --}}
                                                            <span class="avatar avatar-xxl brround cover-image " style="background-color: #007bff;"  data-bs-image-src="{{asset('storage/phyphox/'. $phyphox->icon)}}"></span>
                                                            {{-- Atau jika Anda punya bg-blue kustom: --}}
                                                            {{-- <span class="avatar avatar-xxl brround cover-image bg-blue" data-bs-image-src="{{asset('storage/phyphox_icons/'. $phyphox->icon)}}"></span> --}}
                                                            <p>{{ $phyphox->nama }} ({{ $phyphox->kategori }})</p>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p>Tidak ada alat Phyphox terkait.</p>
                                                @endif
                                            </div>
                                            <div class="card-footer text-center">
                                                @if(!$m)
                                                    <p class="text-danger">Belum ada kelas untuk modul ini.</p>
                                                    {{-- <span class="badge bg-success me-1 mb-1">Tambah Kelas</span> --}}
                                                @else
                                                @foreach($m->kelas as $k)
                                                    <p class="text-primary mb-1"><strong>Daftar Kelas :</strong></p>
                                                    <div class="col-md-12">
                                                        <a href="{{route('siswa.kelas', $k->id)}}" type="button" class="btn btn-info  mt-1 mb-1 me-3">
                                                            <span>{{ $k->nama_kelas }}</span>
                                                            <span class="badge bg-white rounded-pill">{{ $k->kelas_user->count() }}</span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- ROW-3 OPEN -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">MODUL DAN KELAS</h3>
                            @if($isAdmin)
                                @include('modul.create')
                                <div class="card-options">
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#createModal"
                                        class="btn btn-primary btn-icon text-white me-2">
                                        <span>
                                            <i class="fe fe-plus"></i>
                                        </span> Tambah Modul
                                    </a>
                                    {{-- <a href="javascript:void(0);" class="btn btn-secondary btn-md ms-2">Action 2</a> --}}
                                </div>
                            @endif
                        </div>
                        @if($modul->isEmpty())
                            <div class="card-body">
                                <div class="alert alert-info" role="alert">
                                    Tidak ada modul tersedia. Silakan tambahkan modul baru.
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="row">
                                @foreach( $modul as $m )
                                    @include('kelas.create')
                                    <div class="col-md-4 col-xl-4">
                                        <div class="card bg-info-transparent mb-4 shadow-md border-0">
                                            <div class="card-status bg-blue br-tr-7 br-tl-7"></div>
                                            <div class="card-header">
                                                <h3 class="card-title">{{$m->judul_id}}</h3>
                                                @if($isAdmin || $isGuru)
                                                    <div class="card-options">
                                                        <a type="button" data-bs-toggle="modal" data-bs-target="#createModalKelas"
                                                            class="btn btn-success btn-icon text-white">
                                                            <span>
                                                                <i class="fe fe-plus"></i>
                                                            </span> Tambah Kelas
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row card-body text-center justify-content-center">
                                                @if ($m->relatedPhyphox->isNotEmpty())
                                                    {{-- Loop melalui relasi kustom relatedPhyphox --}}
                                                    @foreach ($m->relatedPhyphox as $phyphox)
                                                        <div class="col-md-6">
                                                            {{-- TAMBAHKAN KELAS BACKGROUND DI SINI --}}
                                                            <span class="avatar avatar-xxl brround cover-image " style="background-color: #007bff;"  data-bs-image-src="{{asset('storage/phyphox/'. $phyphox->icon)}}"></span>
                                                            {{-- Atau jika Anda punya bg-blue kustom: --}}
                                                            {{-- <span class="avatar avatar-xxl brround cover-image bg-blue" data-bs-image-src="{{asset('storage/phyphox_icons/'. $phyphox->icon)}}"></span> --}}
                                                            <p>{{ $phyphox->nama }} ({{ $phyphox->kategori }})</p>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p>Tidak ada alat Phyphox terkait.</p>
                                                @endif
                                            </div>
                                            <div class="card-footer text-center">
                                                @if($m->kelas->isEmpty())
                                                    <p class="text-danger">Belum ada kelas untuk modul ini.</p>
                                                    {{-- <span class="badge bg-success me-1 mb-1">Tambah Kelas</span> --}}
                                                @else
                                                    <p class="text-primary mb-1"><strong>Daftar Kelas :</strong></p>
                                                    @foreach($m->kelas as $m)
                                                    <div class="col-md-12">
                                                        <a href="{{route('kelas.peserta', $m->id)}}" type="button" class="btn btn-info  mt-1 mb-1 me-3">
                                                            <span>{{ $m->nama_kelas }}</span>
                                                            <span class="badge bg-white rounded-pill">{{ $m->kelas_user->count() }}</span>
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    
    <!-- ROW-3 CLOSED -->
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
