@extends('layouts.app')
@section('title')
Modul
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                 {{-- @include('admin.modul.upload') --}}
                 @include('modul.create')
                <div class="box-body" style="height: 1200px">
                    <!-- Row -->
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header justify-content-between">
                                    <h2 class="card-title">Daftar Modul</h2>
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
                                                    <th class="text-start align-middle">Judul</th>
                                                    <th class="text-start align-middle">Deskripsi</th>
                                                    <th class="text-start align-middle">Owner</th>
                                                    <th class="text-center align-middle">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $d)
                                                    @include('modul.edit')
                                                    <tr>
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td class="text-start align-middle">{{ $d->judul_id }}</td>
                                                        <td class="text-start align-middle">{{ $d->deskripsi_id }}</td>
                                                        <td class="text-start align-middle">
                                                            @if(!empty($d->owners) && $d->owners->count())
                                                                @foreach($d->owners as $owner)
                                                                    <span class="badge bg-info me-1">{{ $owner->name }}</span>
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @php
                                                                // Cek apakah user login termasuk owner atau admin
                                                                $isOwner = $d->owners->contains('id', $userLogin->id);
                                                                $isAdmin = $userLogin->roles->contains('name', 'Administrator');
                                                            @endphp

                                                            {{-- Tombol Edit --}}
                                                            @if ($isOwner || $isAdmin)
                                                                <button class="btn btn-icon bg-warning"
                                                                        title="Edit Data"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editModal"
                                                                        onclick='editButton(@json($d->load("owners")), {{ $d->id }})'>
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                            @endif

                                                            {{-- Tombol Delete --}}
                                                            @if ($isOwner || $isAdmin)
                                                                <button type="button"
                                                                        class="btn btn-icon bg-danger my-2"
                                                                        title="Delete Data"
                                                                        onclick="deleteButton({{ $d->id }})">
                                                                    <i class="fe fe-trash"></i>
                                                                </button>

                                                                <form action="{{ route('modul.delete', $d->id) }}"
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
        $('#owner').select2({
            dropdownParent: $('#createModal'),
            placeholder: 'Pilih owner...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-guru') }}', // route pencarian user
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

        $('#phyphox_id').select2({
            dropdownParent: $('#createModal'),
            placeholder: 'Pilih Alat Ukur...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-phyphox') }}', // route pencarian user
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
                                text: item.text
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // SweetAlert konfirmasi sebelum submit
        $('#btnCreate').on('click', function() {
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

    function editButton(data, id) {
        const form = document.getElementById('editForm');
        form.action = '/modul/ubah/' + id;

        // Isi input text & textarea
        $('#edit_judul_id').val(data.judul_id ?? '');
        $('#edit_judul_en').val(data.judul_en ?? '');
        $('#edit_deskripsi_id').val(data.deskripsi_id ?? '');
        $('#edit_deskripsi_en').val(data.deskripsi_en ?? '');

        const ownerSelect = $('#edit_owner');

        // Hancurkan select2 lama agar tidak duplikat
        if (ownerSelect.hasClass("select2-hidden-accessible")) {
            ownerSelect.select2('destroy');
        }

        // Kosongkan lalu isi data owner lama
        ownerSelect.empty();
        if (data.owners && data.owners.length > 0) {
            data.owners.forEach(owner => {
                const option = new Option(owner.name, owner.id, true, true);
                ownerSelect.append(option);
            });
        }

        // Re-init Select2 tanpa tema Bootstrap
        ownerSelect.select2({
            dropdownParent: $('#editModal'),
            placeholder: 'Pilih owner...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('search-guru') }}",
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
                            };
                        })
                    };
                },
                cache: true
            }
        }).trigger('change');
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
