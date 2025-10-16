@extends('layouts.admin')
@section('title')
Profile
@endsection
@section('content')
@include('swal')
<section class="main-content mt-0">
    <!-- ROW-1 OPEN -->
    <div class="row" id="user-profile">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xl-6">
                                <div class="wideget-user-desc text-xl-right">
                                    <div class="wideget-user-img">
                                        @if($user->profile && $user->profile->foto)
                                            <img class="" style="height:300px; width:300px" src="{{ asset('storage/' . $user->profile->foto) }}" alt="Foto Profil">
                                         @else
                                            <img class="" style="height:300px; width:300px" src="{{ asset('assets/images/users/default.jpg') }}" alt="Foto Profil">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xl-6">
                                <div class="user-wrap">
                                    <h3>{{ $user->name ?? 'Guest' }}</h3>
                                    <h5 class="text-muted mb-3">{{ $user->roles->first()->name ?? 'Tanpa Role' }}</h5>
                                    {{-- <a href="javascript:void(0);" class="btn btn-primary mt-1 mb-1"><i class="fa fa-rss"></i> Follow</a>
                                    <a href="emailservices.html" class="btn btn-secondary mt-1 mb-1"><i class="fa fa-envelope"></i> E-mail</a> --}}
                                </div>
                                <div class="text-xl-left mt-4 mt-xl-0">
                                    <a href="{{route('admin.profile.edit')}}" class="btn btn-lg btn-primary mb-1">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-top">
                    <div class="wideget-user-tab">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu1">
                                <ul class="nav">
                                    <li class=""><a href="#tab-51" class="active show" data-bs-toggle="tab">Profile</a></li>
                                    {{-- <li><a href="#tab-61" data-bs-toggle="tab" class="">Friends</a></li>
                                    <li><a href="#tab-71" data-bs-toggle="tab" class="">Gallery</a></li>
                                    <li><a href="#tab-81" data-bs-toggle="tab" class="">Followers</a></li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-51">
                    <div id="profile-log-switch">
                        <div class="card">
                            <div class="card-body">
                                <div class="media-heading">
                                    <h5><strong>Informasi Pribadi</strong></h5>
                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-6 p-0">
                                            <tr>
                                                <td><strong>Nama Lengkap </strong></td>
                                                <td>: {{$user->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Asal Sekolah </strong></td>
                                                <td>: {{$user->profile->asal_sekolah}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. HP </strong></td>
                                                <td>: {{$user->profile->nomor_hp}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email </strong></td>
                                                <td>: {{$user->email}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- COL-END -->
    </div>
    <!-- ROW-1 CLOSED -->
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
