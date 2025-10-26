@extends('layouts.app')
@section('title')
{{__('admin.class_participants.title')}} {{ $kelas->nama_kelas }}
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
                                        <h2 class="card-title mb-10">{{__('admin.class_participants.list_title')}} {{$kelas->nama_kelas}}</h2>
                                    </div>
                                    <div class="justified-content-end">
                                        @if($isGuru || $isAdmin)
                                            <button type="button"
                                                class="btn btn-primary waves-effect waves-light"
                                                data-bs-toggle="modal"
                                                data-bs-target="#createModal">
                                                <i class="fa fa-plus me-2"></i>{{__('admin.class_participants.add_participants')}}
                                            </button>
                                        @elseif($isSiswa && !$isJoined)
                                            <button type="button"
                                                class="btn btn-success waves-effect waves-light"
                                                data-bs-toggle="modal"
                                                data-bs-target="#joinModal">
                                                <i class="fa fa-sign-in-alt me-2"></i>{{__('admin.class_participants.join_class')}}
                                            </button>
                                        @endif
                                    </div>                                    
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom w-100" id="data">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle" width="5%">{{__('admin.class_participants.table_no')}}</th>
                                                    <th class="text-start align-middle">{{__('admin.class_participants.table_name')}}</th>
                                                    <th class="text-center align-middle">{{__('admin.class_participants.table_status')}}</th>
                                                    <th class="text-center align-middle" width="15%">{{__('admin.class_participants.table_action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($peserta as $p)
                                                    <tr>
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td class="text-start align-middle">{{ $p->user->name }}</td>
                                                        <td class="text-center align-middle">
                                                            @if ($p->pro_kontra_id == '1')
                                                                <span class="badge bg-success">{{__('admin.class_participants.status_pro')}}</span>
                                                            @elseif ($p->pro_kontra_id == '0')
                                                                <span class="badge bg-warning">{{__('admin.class_participants.status_contra')}}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{__('admin.class_participants.no_status')}}</span>
                                                            @endif
                                                        </td>
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
                                                        <td colspan="4" class="text-center">{{__('admin.class_participants.no_participant')}}</td>
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
                title: '{{__('admin.class_participants.swal.create.title')}}',
                text: '{{__('admin.class_participants.swal.create.text')}}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{__('admin.class_participants.swal.create.confirm')}}',
                cancelButtonText: '{{__('admin.class_participants.swal.create.cancel')}}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#storeForm').submit();
                }
            });
        });
    });

    function deleteButton(id) {
        Swal.fire({
            title: '{{__('admin.class_participants.swal.delete.title')}}',
            text: '{{__('admin.class_participants.swal.delete.text')}}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{__('admin.class_participants.swal.delete.confirm')}}',
            cancelButtonText: '{{__('admin.class_participants.swal.delete.cancel')}}',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
