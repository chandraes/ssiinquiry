@extends('layouts.app')
@section('title')
{{-- [PERBAIKAN] Menggunakan @role --}}
@role('siswa')
Dashboard Siswa
@else
{{ __('admin.dashboard.title') }}
@endrole
@endsection
@section('content')
@include('swal')
<div class="main-container container-fluid">
    @php
    // HAPUS BLOK @php $isGuru, $isAdmin, $isSiswa YANG LAMA
    // Kita akan gunakan @role langsung
    @endphp

    {{-- =================================================================== --}}
    {{-- = TAMPILAN UNTUK SISWA = --}}
    {{-- =================================================================== --}}
    {{-- [PERBAIKAN] Menggunakan @role('siswa') --}}
    @role('siswa')

    {{-- (Bagian 'Selamat Datang' tidak berubah) --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="background-color: #f8f9fa;">
                <div class="card-body">
                    <h2 class="card-title">Selamat Datang, {{ $userLogin->name }}!</h2>
                    <p class="text-muted mb-0">Pilih kelas di bawah ini untuk memulai pembelajaran.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ... (Seluruh sisa tampilan Siswa sudah benar) ... --}}
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h3 class="card-title mb-0">Kelas Saya</h3>
    </div>
    <div class="row">
        @forelse($myClasses as $kelas)
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-status bg-blue br-tr-7 br-tl-7"></div>
                @php
                if ($kelas->modul->image) {
                $imageUrl = asset('storage/' . $kelas->modul->image);
                } else {
                $text = e($kelas->modul->judul);
                $svg = '<svg width="400" height="180" xmlns="http://www.w3.org/2000/svg"
                    style="background-color:#e9ecef;"><text x="50%" y="50%" font-family="Arial, sans-serif"
                        font-size="20" fill="#6c757d" text-anchor="middle" dominant-baseline="middle">' . $text .
                        '</text></svg>';
                $imageUrl = 'data:image/svg+xml;base64,' . base64_encode($svg);
                }
                @endphp
                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $kelas->modul->judul }}"
                    style="height: 180px; object-fit: cover;">
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
                Anda belum terdaftar di kelas manapun.
            </div>
        </div>
        @endforelse
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="card-title mb-0">Jelajahi Modul Lain</h3>
    </div>
    <div class="row">
        @forelse($allOtherModules as $modul)
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm border-0 h-100 bg-light-transparent">
                @php
                if ($modul->image) {
                $imageUrl = asset('storage/' . $modul->image);
                } else {
                $text = e($modul->judul);
                $svg = '<svg width="400" height="180" xmlns="http://www.w3.org/2000/svg"
                    style="background-color:#f8f9fa;"><text x="50%" y="50%" font-family="Arial, sans-serif"
                        font-size="20" fill="#6c757d" text-anchor="middle" dominant-baseline="middle">' . $text .
                        '</text></svg>';
                $imageUrl = 'data:image/svg+xml;base64,' . base64_encode($svg);
                }
                @endphp
                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $modul->judul }}"
                    style="height: 180px; object-fit: cover;">
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
    {{-- [PERBAIKAN] Gunakan @else --}}
    @else

    {{-- (Modal 'Tambah Kelas' sudah benar menggunakan @role) --}}
    @role(['admin','guru'])
    @include('kelas.create')
    @endrole

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">MODUL DAN KELAS</h3>

                    {{-- [PERBAIKAN] Gunakan @role('admin') --}}
                    @role('admin')
                    @include('modul.create')
                    <div class="card-options">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#createModal"
                            class="btn btn-primary btn-icon text-white me-2">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> Tambah Modul
                        </a>
                    </div>
                    @endrole
                </div>

                {{-- (Sisa kode Anda sudah benar menggunakan @role) --}}
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
                        <div class="col-md-4 col-xl-4">
                            <div class="card bg-info-transparent mb-4 shadow-md border-0 h-100">
                                <div class="card-status bg-blue br-tr-7 br-tl-7"></div>
                                <div class="card-header">
                                    <h3 class="card-title">{{$m->judul}}</h3>
                                    @role(['admin','guru'])
                                    <div class="card-options">
                                        <a type="button" class="btn btn-success btn-icon text-white btn-add-kelas"
                                            data-modul-id="{{ $m->id }}" data-modul-judul="{{ $m->judul }}">
                                            <span>
                                                <i class="fe fe-plus"></i>
                                            </span> Tambah Kelas
                                        </a>
                                    </div>
                                    @endrole
                                </div>

                                @php
                                if ($m->image) {
                                $imageUrl = asset('storage/' . $m->image);
                                } else {
                                $text = e($m->judul);
                                $svg = '<svg width="400" height="180" xmlns="http://www.w3.org/2000/svg"
                                    style="background-color:#e9ecef;"><text x="50%" y="50%"
                                        font-family="Arial, sans-serif" font-size="20" fill="#6c757d"
                                        text-anchor="middle" dominant-baseline="middle">' . $text . '</text></svg>';
                                $imageUrl = 'data:image/svg+xml;base64,' . base64_encode($svg);
                                }
                                @endphp
                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $m->judul }}"
                                    style="height: 180px; object-fit: cover;">

                                <div class="row card-body text-center justify-content-center">
                                    <strong class="text-start">Deskripsi :</strong>
                                    <p>{{Str::limit(strip_tags($m->deskripsi ?? ''), 100, '...') }}</p>

                                    @php $phyphoxIds = $m->phyphox_id ?? []; @endphp
                                    @if (!empty($phyphoxIds))
                                    <strong class="text-start">Alat :</strong>
                                    @foreach ($phyphoxIds as $phyphoxId)
                                    @if(isset($allPhyphoxTools[$phyphoxId]))
                                    @php $phyphox = $allPhyphoxTools[$phyphoxId]; @endphp
                                    <div class="col-md-12 ">
                                        <p class="mb-1">{{ $phyphox->nama }} ({{ $phyphox->kategori }})</p>
                                    </div>
                                    @endif
                                    @endforeach
                                    @else
                                    <p class="text-muted">Tidak ada alat Phyphox terkait.</p>
                                    @endif
                                </div>
                                <div class="card-footer text-center">
                                    @if($m->kelas->isEmpty())
                                    <p class="text-danger">Belum ada kelas untuk modul ini.</p>
                                    @else
                                    <p class="text-primary mb-1"><strong>Daftar Kelas :</strong></p>
                                    @foreach($m->kelas as $k)
                                    <div class="d-grid d-md-block col-12 mt-1 mb-1">
                                        <a href="{{ route('kelas.show', $k->id) }}" type="button"
                                            class="btn btn-info text-start d-flex justify-content-between align-items-center">
                                            <span>{{ $k->nama_kelas }}</span>
                                            <span class="badge bg-white rounded-pill text-dark">{{ $k->peserta_count
                                                }}</span>
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
    @endrole
    {{-- [PERBAIKAN] Penutup untuk @role('siswa') --}}
</div>
@endsection

@push('js')
{{-- [PERBAIKAN] Gunakan @role(['admin', 'guru']) --}}
@role(['admin', 'guru'])
<script>
    // --- Inisialisasi Modal Tambah Modul (Tidak Berubah) ---
    $(document).ready(function() {
        $('#owner').select2({
            dropdownParent: $('#createModal'),
            placeholder: '{{ __("admin.placeholders.select_owner") }}',
            allowClear: true, minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-guru') }}',
                dataType: 'json', delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return { results: $.map(data, function(item) {
                        return { id: item.id, text: item.name }
                    })};
                },
                cache: true
            }
        });

        $('#btnCreate').on('click', function() {
            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question', showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#storeForm').submit();
                }
            });
        });
    });

    // --- Inisialisasi Modal Tambah Kelas (Tidak Berubah) ---
    $(document).ready(function() {
        $('#guru_id').select2({
            dropdownParent: $('#createModalKelas'),
            placeholder: '{{ __("admin.placeholders.select_teacher") }}',
            allowClear: true, minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-pengajar') }}',
                dataType: 'json', delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return { results: $.map(data, function(item) {
                        return { id: item.id, text: item.name }
                    })};
                },
                cache: true
            }
        });

        $('.btn-add-kelas').on('click', function() {
            var modulId = $(this).data('modul-id');
            var modulJudul = $(this).data('modul-judul');
            var $modal = $('#createModalKelas');
            $modal.find('#modul_id_for_kelas').val(modulId);
            $modal.find('#createModalKelasJudul').text(modulJudul);
            $modal.modal('show');
        });

        $('#btnCreateKelas').on('click', function() {
            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question', showCancelButton: true,
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
@endrole
{{-- [PERBAIKAN] Penutup untuk @role(['admin', 'guru']) --}}
@endpush
