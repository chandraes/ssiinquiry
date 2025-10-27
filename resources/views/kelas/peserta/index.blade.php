@extends('layouts.app')
@section('title')
Peserta Kelas
@endsection
@section('content')
@include('swal')
<section class="content">
    @php
        // Cek apakah user login termasuk owner atau admin
        $isGuru = $userLogin->roles->contains('name', 'Guru');
        $isAdmin = $userLogin->roles->contains('name', 'Administrator');
        $isSiswa = $userLogin->roles->contains('name', 'Siswa');
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                {{-- <div class="box-body" style="height: 1200px"> --}}
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header justify-content-between">
                                    <div>
                                        <h2 class="card-title mb-10">Daftar Peserta Kelas {{$kelas->nama_kelas}}</h2>
                                    </div>
                                    <div class="justified-content-end">
                                        @if($isGuru || $isAdmin)
                                            <button type="button"
                                                class="btn btn-primary waves-effect waves-light"
                                                data-bs-toggle="modal"
                                                data-bs-target="#createModal">
                                                <i class="fa fa-plus me-2"></i>Tambah Peserta
                                            </button>
                                        @elseif($isSiswa && !$isJoined)
                                            <button type="button"
                                                class="btn btn-success waves-effect waves-light"
                                                data-bs-toggle="modal"
                                                data-bs-target="#joinModal">
                                                <i class="fa fa-sign-in-alt me-2"></i>Gabung Kelas
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom w-100" id="data">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle" width="5%">No</th>
                                                    <th class="text-start align-middle">Nama Peserta</th>
                                                    {{-- <th class="text-center align-middle">Status (Pro/Kontra)</th> --}}
                                                    <th class="text-center align-middle" width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($peserta as $p)
                                                    <tr>
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td class="text-start align-middle">{{ $p->user->name }}</td>
                                                        {{-- <td class="text-center align-middle">
                                                            @if ($p->pro_kontra_id == '1')
                                                                <span class="badge bg-success">Pro</span>
                                                            @elseif ($p->pro_kontra_id == '0')
                                                                <span class="badge bg-warning">Kontra</span>
                                                            @else
                                                                <span class="badge bg-danger">Belum ditentukan</span>
                                                            @endif
                                                        </td> --}}
                                                        <td class="text-center align-middle">
                                                        @if($isAdmin || $isGuru)
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm"
                                                                    onclick="deleteButton({{ $p->id }})"
                                                                    title="Delete">
                                                                <i class="fe fe-trash"></i>
                                                            </button>
                                                            <form action="{{ route('kelas.peserta.delete', $p->id) }}" method="POST" id="delete-form-{{ $p->id }}">
                                                                @csrf
                                                                @method('delete')
                                                            </form>
                                                        @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Belum ada peserta di kelas ini.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- Modal--}}

                                @if($isAdmin || $isGuru)
                                    @include('kelas.peserta.create')
                                @else
                                    @include('kelas.peserta.join')
                                @endif
                                {{-- @include('kelas.peserta.upload') --}}
                            </div>
                        </div>
                    </div>

                    {{-- @include('kelas.sub-modul.pengantar-konteks') --}}
                </div>
            </div>
        </div>
    </div>



</section>
@endsection

@push('js')
<script>
    $(function() {
        "use strict";
        $('#data').DataTable();
    });

    $(document).ready(function() {
        // Tombol konfirmasi simpan
        $('#btnCreate').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Data?',
                text: "Pastikan data peserta sudah benar!",
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

    // $(document).ready(function() {
    //     $('#btnUpload').on('click', function(e) {
    //         e.preventDefault();
    //         Swal.fire({
    //             title: 'Upload Peserta?',
    //             text: "Pastikan file sudah sesuai template!",
    //             icon: 'question',
    //             showCancelButton: true,
    //             confirmButtonText: 'Ya, Upload!',
    //             cancelButtonText: 'Batal',
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 $('#uploadForm').submit();
    //             }
    //         });
    //     });
    // });

    function proButton(id) {
        Swal.fire({
            title: 'Ubah Status Peserta?',
            text: "Peserta akan ditandai sebagai Tim Pro!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('pro-form-' + id).submit();
            }
        });
    }

    function kontraButton(id) {
        Swal.fire({
            title: 'Ubah Status Peserta?',
            text: "Peserta akan ditandai sebagai Tim Kontra!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('kontra-form-' + id).submit();
            }
        });
    }

    function deleteButton(id) {
        Swal.fire({
            title: 'Hapus Peserta?',
            text: "Peserta akan dihapus dari kelas ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
