@extends('layouts.app')
@section('title')
Pengguna
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                 {{-- @include('admin.users.upload') --}}
                 @include('users.create')
                <div class="box-body" style="height: 1200px">
                    <!-- Row -->
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header justify-content-between">
                                    <h2 class="card-title">Daftar Pengguna</h2>
                                    <button
                                        type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#createModal">
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
                                                    <th class="text-start align-middle">Nama</th>
                                                    <th class="text-start align-middle">Nama Pengguna</th>
                                                    {{-- <th class="text-start align-middle">Jenis Kelamin</th> --}}
                                                    <th class="text-start align-middle">Role</th>
                                                    <th class="text-start align-middle">Email</th>
                                                    <th class="text-center align-middle">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $d)
                                                @include('users.edit')
                                                <tr>
                                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                                    <td class="text-start align-middle">{{$d->name}}</td>
                                                    <td class="text-start align-middle">{{$d->username}}</td>
                                                    {{-- <td class="text-start align-middle">
                                                        @if($d->jenis_kelamin == 'L')
                                                            Laki-laki
                                                        @elseif($d->jenis_kelamin == 'P')
                                                            Perempuan
                                                        @elseif(empty($d->jenis_kelamin))
                                                            Tidak diisi
                                                        @else
                                                            -
                                                        @endif
                                                    </td> --}}
                                                    <td class="text-start align-middle">{{$d->roles[0]->name}}</td>
                                                    <td class="text-start align-middle">{{$d->email}}</td>
                                                    <td class="text-center align-middle">
                                                        {{-- Tombol Edit --}}
                                                        <button class="btn btn-icon bg-warning"
                                                            title="Edit Data"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editModal"
                                                            onclick='editButton(@json($d), {{ $d->id }})'>
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        {{-- Tombol Delete --}}
                                                        <button type="button"
                                                                class="btn btn-icon bg-danger my-2"
                                                                title="Delete Data"
                                                                onclick="deleteButton({{$d->id}})"> <i class="fe fe-trash"></i>
                                                        </button>

                                                        <form action="{{ route('user.delete', $d->id) }}"
                                                            method="POST"
                                                            id="delete-form-{{$d->id}}">
                                                            @csrf
                                                            @method('delete')
                                                        </form>

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

    // Konfirmasi Simpan
    document.getElementById('btnCreate').addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan disimpan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika user klik "Ya, Simpan!"
                document.getElementById('storeForm').submit();
            }
        });
    });

    function editButton(data, id) {
        const form = document.getElementById('editForm');
        form.action = '/pengguna/ubah/' + id;

        document.getElementById('edit_name').value = data.name ?? '';
        document.getElementById('edit_username').value = data.username ?? '';
        document.getElementById('edit_email').value = data.email ?? '';
        
        // isi role
        const select = document.getElementById('edit_role_id');
        if (data.roles && data.roles.length > 0) {
            select.value = data.roles[0].id; // ambil role pertama
        } else {
            select.value = '';
        }
    }

    // Konfirmasi simpan perubahan
    document.getElementById('btnUpdate').addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Apakah Anda yakin ingin menyimpan perubahan pada modul ini?",
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

    // Hapus data
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
