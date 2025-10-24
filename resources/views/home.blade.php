@extends('layouts.app')
@section('title')
    {{-- [DIUBAH] --}}
    {{ __('admin.dashboard.title') }}
@endsection
@section('content')
@include('swal')
<div class="main-container container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- [DIUBAH] --}}
                {{-- <div class="card-header">{{ __('admin.dashboard.header') }}</div> --}}
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
                                        <div class="row row-sm">
                                        @foreach( $modul as $m )
                                            @include('kelas.create')
                                            <div class="col-md-4 col-xl-4">
                                                <div class="card bg-info-transparent mb-4 shadow-md border-0">
                                                    <div class="card-status bg-blue br-tr-7 br-tl-7"></div>
                                                    <div class="card-header">
                                                        <h3 class="card-title">{{$m->judul}}</h3>
                                                    </div>
                                                    <div class="row card-body text-center justify-content-center">
                                                        {{-- Pastikan $m->modul tidak null sebelum mengakses relasi --}}
                                                        {{-- Pemeriksaan isNotEmpty() dilakukan pada relasi Phyphox yang ada di dalam Modul --}}
                                                        @if ($m->relatedPhyphox->isNotEmpty())
                                                            <img src="{{$m->image
                                                                ? asset('storage/'.$m->image)
                                                                : asset('assets/images/users/1.jpg')}}"
                                                                    alt="img" class="mb-5">
                                                            <strong class="text-start">Deskripsi :</strong>
                                                            <p>{{Str::limit(strip_tags($m->deskripsi ?? ''), 100, '...') }}</p>

                                                            <strong class="text-start">Alat :</strong>
                                                            @foreach ($m->relatedPhyphox as $phyphox)
                                                                <div class="col-md-12 ">
                                                                    <p class="mb-1">{{ $phyphox->nama }} ({{ $phyphox->kategori }})</p>
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
                                                                    <span class="badge bg-white rounded-pill">{{ $k->peserta->count() }}</span>
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
                                                        <h3 class="card-title">{{$m->judul}}</h3>
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
                                                            <img src="{{$m->image
                                                                ? asset('storage/'.$m->image)
                                                                : asset('assets/images/users/1.jpg')}}"
                                                                    alt="img" class="mb-5">
                                                            <strong class="text-start">Deskripsi :</strong>
                                                            <p>{{Str::limit(strip_tags($m->deskripsi ?? ''), 100, '...') }}</p>

                                                            <strong class="text-start">Alat :</strong>
                                                            @foreach ($m->relatedPhyphox as $phyphox)
                                                                <div class="col-md-12 ">
                                                                    <p class="mb-1">{{ $phyphox->nama }} ({{ $phyphox->kategori }})</p>
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
                                                                    <span class="badge bg-white rounded-pill">{{ $m->peserta->count() }}</span>
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
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
// Inisialisasi Select2
    $(document).ready(function() {
        // $('#phyphox_id').select2({
        //     dropdownParent: $('#createModal'),
        //     allowClear: true,
        //     // Hapus minimumInputLength (kecuali Anda ingin membatasi input di Search Box)
        //     // Hapus SELURUH BLOK 'ajax'
        // });

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
