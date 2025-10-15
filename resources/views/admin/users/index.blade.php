@extends('layouts.admin')
@section('title')
DAFTAR PENGGUNA
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border d-flex justify-content-end">
                    {{-- <button
                        type="button" class="btn btn-success waves-effect waves-light mb-5 mx-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fa fa-upload me-2"></i>Upload Data
                    </button> --}}
                    <button
                        type="button" class="btn btn-primary waves-effect waves-light mb-5 mx-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fa fa-plus me-2"></i>Tambah Data
                    </button>
                    {{-- </div> --}}
                </div>
                 {{-- @include('admin.users.upload') --}}
                 @include('admin.users.create')
                <div class="box-body" style="height: 1200px">
                    <div class="table-responsive">
                        <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
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
                            @include('admin.users.edit')
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
                                    <button class="btn btn-rounded bg-warning" 
                                        title="Edit Data" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal" 
                                        onclick='editButton(@json($d), {{ $d->id }})'>
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    {{-- Tombol Delete --}}
                                    <button type="button"
                                            class="btn btn-rounded bg-danger my-2"
                                            title="Delete Data"
                                            onclick="deleteRuang({{$d->id}})"> <i class="fa fa-trash"></i>
                                    </button>

                                    <form action="{{ route('admin.user.delete', $d->id) }}"
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
</section>
@endsection

@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(function() {
        "use strict";
        $('#data').DataTable();
    });

    function editButton(data, id) {
        const form = document.getElementById('editForm');
        form.action = '/admin/pengguna/ubah/' + id;

        document.getElementById('edit_name').value = data.name ?? '';
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        // isi role
        const select = document.getElementById('edit_role');
        if (data.roles && data.roles.length > 0) {
            select.value = data.roles[0].id; // ambil role pertama
        } else {
            select.value = '';
        }
    }



    // Hapus data
    function deleteRuang(id) {
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

    // Hapus data (Langsung submit tanpa konfirmasi)
    // function deleteRuang(id) {
    //     const form = document.getElementById('delete-form-' + id);
    //     if (form) {
    //         form.submit(); // Langsung memicu aksi DELETE
    //     } else {
    //         console.error('Form penghapusan tidak ditemukan untuk ID:', id);
    //     }
    // }
</script>
@endpush
