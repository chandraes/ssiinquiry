@extends('layouts.app')
@section('title')
{{-- Menampilkan judul dinamis berdasarkan peran --}}
@if(Auth::user()->roles->contains('name', 'Siswa'))
Dashboard Siswa
@else
{{ __('admin.dashboard.title') }}
@endif
@endsection
@section('content')
@include('swal')
<div class="main-container container-fluid">
    @php
    // Cek peran pengguna
    $isGuru = $userLogin->roles->contains('name', 'Guru');
    $isAdmin = $userLogin->roles->contains('name', 'Administrator');
    $isSiswa = $userLogin->roles->contains('name', 'Siswa');
    @endphp

    {{-- =================================================================== --}}
    {{-- = TAMPILAN UNTUK SISWA = --}}
    {{-- =================================================================== --}}
    @if($isSiswa)

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="background-color: #f8f9fa;">
                <div class="card-body">
                    <h2 class="card-title">Selamat Datang, {{ $userLogin->name }}!</h2>
                    <p class="text-muted mb-0">Pilih kelas di bawah ini untuk memulai pembelajaran, atau jelajahi modul
                        lain yang tersedia.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h3 class="card-title mb-0">Kelas Saya</h3>
    </div>
    <div class="row">
        {{-- Loop dari variable $myClasses (dari HomeController) --}}
        @forelse($myClasses as $kelas)
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-status bg-blue br-tr-7 br-tl-7"></div>

                {{-- [PERUBAHAN FOTO COVER DIMULAI DI SINI] --}}
                @if($kelas->modul->image)
                {{-- Tampilkan gambar jika ada --}}
                <img src="{{ asset('storage/' . $kelas->modul->image) }}" class="card-img-top"
                    alt="{{ $kelas->modul->judul }}" style="height: 180px; object-fit: cover;">
                @else
                {{-- Tampilkan placeholder jika tidak ada gambar --}}
                <div class="card-img-top d-flex align-items-center justify-content-center bg-light-transparent"
                    style="height: 180px;">
                    <i class="fe fe-book-open fa-3x text-muted"></i>
                </div>
                @endif
                {{-- [PERUBAHAN FOTO COVER SELESAI] --}}

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $kelas->modul->judul }}</h5>
                    <p class="card-subtitle mb-2 text-muted">
                        Nama Kelas: <strong>{{ $kelas->nama_kelas }}</strong>
                    </p>
                    <p class="card-text flex-grow-1">
                        {{ Str::limit(strip_tags($kelas->modul->deskripsi ?? ''), 100, '...') }}
                    </p>
                    <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-primary mt-auto">
                        Masuk Kelas <i class="fa fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                Anda belum terdaftar di kelas manapun. Silakan gunakan fitur "Jelajahi Modul" atau hubungi guru Anda.
            </div>
        </div>
        @endforelse
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="card-title mb-0">Jelajahi Modul Lain</h3>
    </div>
    <div class="row">
        {{-- Loop dari variable $allOtherModules (dari HomeController) --}}
        @forelse($allOtherModules as $modul)
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100 bg-light-transparent">

                {{-- [PERUBAHAN FOTO COVER DIMULAI DI SINI] --}}
                @if($modul->image)
                <img src="{{ asset('storage/' . $modul->image) }}" class="card-img-top" alt="{{ $modul->judul }}"
                    style="height: 180px; object-fit: cover;">
                @else
                <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                    style="height: 180px;">
                    <i class="fe fe-book-open fa-3x text-muted"></i>
                </div>
                @endif
                {{-- [PERUBAHAN FOTO COVER SELESAI] --}}

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $modul->judul }}</h5>
                    <p class="card-text flex-grow-1">
                        {{ Str::limit(strip_tags($modul->deskripsi ?? ''), 100, '...') }}
                    </p>
                    <a href="#" class="btn btn-outline-secondary mt-auto">
                        Gabung Kelas
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light" role="alert">
                Tidak ada modul lain yang tersedia saat ini.
            </div>
        </div>
        @endforelse
    </div>

    {{-- =================================================================== --}}
    {{-- = TAMPILAN UNTUK ADMIN & GURU = --}}
    {{-- =================================================================== --}}
    @else
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
                        @include('kelas.create') {{-- TODO: Pastikan ID modal ini unik --}}
                        <div class="col-md-4 col-xl-4">
                            <div class="card bg-info-transparent mb-4 shadow-md border-0 h-100">
                                <div class="card-status bg-blue br-tr-7 br-tl-7"></div>
                                <div class="card-header">
                                    <h3 class="card-title">{{$m->judul}}</h3>
                                    @if($isAdmin || $isGuru)
                                    <div class="card-options">
                                        <a type="button" data-bs-toggle="modal"
                                            data-bs-target="#createModalKelas-{{ $m->id }}"
                                            class="btn btn-success btn-icon text-white">
                                            <span>
                                                <i class="fe fe-plus"></i>
                                            </span> Tambah Kelas
                                        </a>
                                    </div>
                                    @endif
                                </div>

                                {{-- [PERUBAHAN FOTO COVER DIMULAI DI SINI] --}}
                                @if($m->image)
                                <img src="{{ asset('storage/' . $m->image) }}" class="card-img-top"
                                    alt="{{ $m->judul }}" style="height: 180px; object-fit: cover;">
                                @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light-transparent"
                                    style="height: 180px;">
                                    <i class="fe fe-book-open fa-3x text-muted"></i>
                                </div>
                                @endif
                                {{-- [PERUBAHAN FOTO COVER SELESAI] --}}

                                <div class="row card-body text-center justify-content-center">
                                    @if ($m->relatedPhyphox->isNotEmpty())
                                    {{-- Hapus <img> lama dari sini --}}
                                    <strong class="text-start">Deskripsi :</strong>
                                    <p>{{Str::limit(strip_tags($m->deskripsi ?? ''), 100, '...') }}</p>

                                    <strong class="text-start">Alat :</strong>
                                    @foreach ($m->relatedPhyphox as $phyphox)
                                    <div class="col-md-12 ">
                                        <p class="mb-1">{{ $phyphox->nama }} ({{ $phyphox->kategori }})</p>
                                    </div>
                                    @endforeach
                                    @else
                                    <strong class="text-start">Deskripsi :</strong>
                                    <p>{{Str::limit(strip_tags($m->deskripsi ?? ''), 100, '...') }}</p>
                                    <p class="text-muted">Tidak ada alat Phyphox terkait.</p>
                                    @endif
                                </div>
                                <div class="card-footer text-center">
                                    @if($m->kelas->isEmpty())
                                    <p class="text-danger">Belum ada kelas untuk modul ini.</p>
                                    @else
                                    <p class="text-primary mb-1"><strong>Daftar Kelas :</strong></p>

                                    @foreach($m->kelas as $k)
                                    <div class="col-md-12">
                                        <a href="{{ route('kelas.show', $k->id) }}" type="button"
                                            class="btn btn-info  mt-1 mb-1 me-3">
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
    @endif
</div>
@endsection

@push('js')
{{-- JavaScript ini HANYA untuk Admin/Guru --}}
@if($isAdmin || $isGuru)
<script>
    // Inisialisasi Select2 untuk Modal Tambah Modul
    $(document).ready(function() {
        $('#owner').select2({
            dropdownParent: $('#createModal'),
            placeholder: '{{ __("admin.placeholders.select_owner") }}',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-guru') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return { id: item.id, text: item.name }
                        })
                    };
                },
                cache: true
            }
        });

        // SweetAlert konfirmasi untuk Modal Tambah Modul
        $('#btnCreate').on('click', function() {
            Swal.fire({
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

    // Inisialisasi Select2 untuk Modal Tambah Kelas
    // Perhatikan: Ini perlu di-loop atau dibuat lebih dinamis jika ada banyak modal
    $(document).ready(function() {
        // Asumsi 'guru_id' adalah ID umum, tapi ini akan bermasalah jika ada banyak modal.
        // Cara yang lebih baik adalah menggunakan kelas, misal '.select2-guru'
        $('#guru_id').select2({
            dropdownParent: $('#createModalKelas'), // Ini akan selalu menarget modal PERTAMA
            placeholder: '{{ __("admin.placeholders.select_teacher") }}',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-pengajar') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return { id: item.id, text: item.name }
                        })
                    };
                },
                cache: true
            }
        });

        // SweetAlert konfirmasi untuk Modal Tambah Kelas
        $('#btnCreateKelas').on('click', function() {
            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ini juga akan menarget form PERTAMA
                    $('#storeFormKelas').submit();
                }
            });
        });
    });
</script>
@endif
@endpush
