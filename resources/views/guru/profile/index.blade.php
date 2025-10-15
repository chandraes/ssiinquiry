@extends('layouts.guru')
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
                                </div>
                                <div class="text-xl-left mt-4 mt-xl-0">
                                    <a href="{{route('guru.profile.edit')}}" class="btn btn-lg btn-primary mb-1">Edit Profile</a>
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
                                                <td>: {{$user->profile->asal_sekolah ?? '-'}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. HP </strong></td>
                                                <td>: {{$user->profile->nomor_hp ?? '-'}}</td>
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

</script>
@endpush
