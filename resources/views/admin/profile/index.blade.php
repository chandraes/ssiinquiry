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
                                <div class="wideget-user-desc d-sm-flex">
                                    <div class="widget-user-img">
                                        <img class="" src="{{ asset('storage/' . $user->profile->foto) }}" alt="img">
                                    </div>
                                    <div class="user-wrap">
                                        <h4>{{ $user->name ?? 'Guest' }}</h4>
                                        <h6 class="text-muted mb-3">{{ $user->roles->first()->name ?? 'Tanpa Role' }}</h6>
                                        <a href="javascript:void(0);" class="btn btn-primary mt-1 mb-1"><i class="fa fa-rss"></i> Follow</a>
                                        <a href="emailservices.html" class="btn btn-secondary mt-1 mb-1"><i class="fa fa-envelope"></i> E-mail</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xl-6">
                                <div class="text-xl-right mt-4 mt-xl-0">
                                    {{-- <a href="emailservices.html" class="btn btn-white">Message</a> --}}
                                    <a href="{{route('admin.profile.edit')}}" class="btn btn-primary">Edit Profile</a>
                                </div>
                                <div class="mt-5">
                                    <div class="main-profile-contact-list float-lg-end d-lg-flex">
                                        <div class="me-5">
                                            <div class="media">
                                                <div class="media-icon bg-primary  me-3 mt-1">
                                                    <i class="fe fe-file-plus fs-20 text-white"></i>
                                                </div>
                                                <div class="media-body">
                                                    <span class="text-muted">Posts</span>
                                                    <div class="fw-semibold fs-25">
                                                        328
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="me-5 mt-5 mt-md-0">
                                            <div class="media">
                                                <div class="media-icon bg-success me-3 mt-1">
                                                    <i class="fe fe-users  fs-20 text-white"></i>
                                                </div>
                                                <div class="media-body">
                                                    <span class="text-muted">Followers</span>
                                                    <div class="fw-semibold fs-25">
                                                        937k
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="me-0 mt-5 mt-md-0">
                                            <div class="media">
                                                <div class="media-icon bg-orange me-3 mt-1">
                                                    <i class="fe fe-wifi fs-20 text-white"></i>
                                                </div>
                                                <div class="media-body">
                                                    <span class="text-muted">Following</span>
                                                    <div class="fw-semibold fs-25">
                                                        2,876
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
