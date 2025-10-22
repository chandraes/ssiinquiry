@extends('layouts.app')
@section('title')
Kelas
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                 {{-- @include('admin.Kelas.upload') --}}
                @php
                    // Cek apakah user login termasuk owner atau admin
                    $isGuru = $userLogin->roles->contains('name', 'Guru');
                    $isAdmin = $userLogin->roles->contains('name', 'Administrator');
                    $isSiswa = $userLogin->roles->contains('name', 'Siswa');
                @endphp
                 @include('kelas.create')
                <div class="box-body" style="height: 1200px">
                    <!-- Row -->
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header justify-content-between">
                                    <h2 class="card-title">Daftar Kelas</h2>
                                    <button
                                        type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#createModalKelas">
                                        <i class="fa fa-plus me-2"></i>Tambah Data
                                    </button>
                                </div>

                                <div class="ms-auto pageheader-btn">

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom w-100" id="data">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle">No</th>
                                                    <th class="text-start align-middle">Modul</th>
                                                    <th class="text-start align-middle">Kelas</th>
                                                    <th class="text-center align-middle">Peserta</th>
                                                    <th class="text-start align-middle">Guru Pengajar</th>
                                                    <th class="text-center align-middle">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $d)
                                                    @include('kelas.edit')
                                                    <tr>
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td class="text-start align-middle">{{ $d->modul->judul_id }}</td>
                                                        <td class="text-start align-middle">{{ $d->nama_kelas }}</td>
                                                        <td class="text-center align-middle">
                                                            @if ($d->peserta()->count() == 0)
                                                                <a href="{{ route('kelas.peserta', $d->id) }}" class="btn btn-md btn-success" title="Tambah Peserta">
                                                                    <i class="fa fa-users"></i> Tambah Peserta
                                                                </a>
                                                             @else
                                                                <span class="me-2"></span>
                                                                <a href="{{ route('kelas.peserta', $d->id) }}" title="Lihat Peserta">
                                                                    {{ $d->peserta()->count() }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td class="text-start align-middle">{{ $d->guru->name}}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            {{-- Tombol Edit --}}
                                                            @if ($isGuru || $isAdmin)
                                                                <button class="btn btn-icon bg-warning"
                                                                        title="Edit Data"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editModal"
                                                                        data-id="{{ $d->id }}"
                                                                        data-modul="{{ $d->modul_id }}"
                                                                        data-nama="{{ $d->nama_kelas }}"
                                                                        onclick="editButton(this)">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>

                                                            @endif

                                                            {{-- Tombol Delete --}}
                                                            @if ($isGuru || $isAdmin)
                                                                <button type="button"
                                                                        class="btn btn-icon bg-danger my-2"
                                                                        title="Delete Data"
                                                                        onclick="deleteButton({{ $d->id }})">
                                                                    <i class="fe fe-trash"></i>
                                                                </button>

                                                                <form action="{{ route('kelas.delete', $d->id) }}"
                                                                    method="POST"
                                                                    id="delete-form-{{ $d->id }}">
                                                                    @csrf
                                                                    @method('delete')
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
{{-- <script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> --}}
<script>
    $(function() {
        "use strict";
        $('#data').DataTable();
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
                    $('#storeForm').submit();
                }
            });
        });
    });

    function editButton(button) {
        const id = $(button).data('id');
        const modul = $(button).data('modul');
        const nama = $(button).data('nama');

        // ubah action form edit
        const form = document.getElementById('editForm');
        form.action = '/kelas/ubah/' + id;

        // isi field input dari data tombol
        $('#edit_modul_id').val(modul);
        $('#edit_nama_kelas').val(nama);
    }


    // Konfirmasi simpan perubahan
    document.getElementById('btnUpdate').addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Apakah Anda yakin ingin menyimpan perubahan pada kelas ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editForm').submit();
            }
        });
    });


    // Konfirmasi Hapus data
    function deleteButton(id) {
        Swal.fire({
            title: 'Delete Data',
            text: "Apakah anda yakin ingin menghapus data?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

</script>
@endpush
