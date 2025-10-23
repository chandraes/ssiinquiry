@extends('layouts.app')
@section('title')
{{-- [DIUBAH] --}}
{{ __('admin.kelas.title') }}
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                @php
                $isGuru = $userLogin->roles->contains('name', 'Guru');
                $isAdmin = $userLogin->roles->contains('name', 'Administrator');
                @endphp

                {{-- Memuat modal create --}}
                @include('kelas.create')

                {{-- Memuat modal edit --}}
                {{-- Pastikan Anda menyertakan $modul di variabel yang dikirim ke view ini --}}
                @include('kelas.edit')

                <div class="box-body" style="height: 1200px">
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header justify-content-between">
                                    {{-- [DIUBAH] --}}
                                    <h2 class="card-title">{{ __('admin.kelas.list_title') }}</h2>
                                    <button type="button" class="btn btn-primary waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#createModalKelas">
                                        {{-- [DIUBAH] --}}
                                        <i class="fa fa-plus me-2"></i>{{ __('admin.kelas.add_button') }}
                                    </button>
                                </div>

                                <div class="ms-auto pageheader-btn"></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom w-100" id="data">
                                            <thead>
                                                <tr>
                                                    {{-- [DIUBAH] --}}
                                                    <th class="text-center align-middle">{{ __('admin.kelas.table_no')
                                                        }}</th>
                                                    <th class="text-start align-middle">{{
                                                        __('admin.kelas.table_module') }}</th>
                                                    <th class="text-start align-middle">{{ __('admin.kelas.table_class')
                                                        }}</th>
                                                    <th class="text-center align-middle">{{
                                                        __('admin.kelas.table_participants') }}</th>
                                                    <th class="text-start align-middle">{{
                                                        __('admin.kelas.table_teacher') }}</th>
                                                    <th class="text-center align-middle">{{
                                                        __('admin.kelas.table_action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $d)
                                                {{-- Kita @include('kelas.edit') di luar loop, di atas --}}
                                                <tr>
                                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                    <td class="text-start align-middle">
                                                        {{-- [PERBAIKAN KRITIS] Menggunakan Spatie --}}
                                                        {{ $d->modul->judul }}
                                                    </td>
                                                    <td class="text-start align-middle">
                                                        {{-- [INI SUDAH BENAR] Spatie otomatis mengambil locale aktif
                                                        --}}
                                                        {{ $d->nama_kelas }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if ($d->peserta()->count() == 0)
                                                        {{-- [DIUBAH] --}}
                                                        <a href="{{ route('kelas.peserta', $d->id) }}"
                                                            class="btn btn-md btn-success"
                                                            title="{{ __('admin.kelas.add_participant_title') }}">
                                                            <i class="fa fa-users"></i> {{
                                                            __('admin.kelas.add_participant_text') }}
                                                        </a>
                                                        @else
                                                        <span class="me-2"></span>
                                                        {{-- [DIUBAH] --}}
                                                        <a href="{{ route('kelas.peserta', $d->id) }}"
                                                            title="{{ __('admin.kelas.view_participant_title') }}">
                                                            {{ $d->peserta()->count() }}
                                                        </a>
                                                        @endif
                                                    </td>
                                                    <td class="text-start align-middle">{{ $d->guru->name}}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{-- Tombol Edit --}}
                                                        @if ($isGuru || $isAdmin)
                                                        {{-- [PERBAIKAN KRITIS PADA TOMBOL EDIT] --}}
                                                        <button class="btn btn-icon bg-warning"
                                                            title="{{ __('admin.kelas.edit_title') }}"
                                                            data-id="{{ $d->id }}" {{-- URL ini untuk diambil oleh AJAX
                                                            --}} data-url="{{ route('kelas.show', $d->id) }}"
                                                            onclick="editButton(this)">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        @endif

                                                        {{-- Tombol Delete --}}
                                                        @if ($isGuru || $isAdmin)
                                                        <button type="button" class="btn btn-icon bg-danger my-2" {{--
                                                            [DIUBAH] --}} title="{{ __('admin.kelas.delete_title') }}"
                                                            onclick="deleteButton({{ $d->id }})">
                                                            <i class="fe fe-trash"></i>
                                                        </button>

                                                        <form action="{{ route('kelas.delete', $d->id) }}" method="POST"
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
<script>
    $(function() {
        "use strict";
        $('#data').DataTable();
    });

    // Inisialisasi Select2
    $(document).ready(function() {
        $('#guru_id').select2({
            dropdownParent: $('#createModalKelas'),
            {{-- [DIUBAH] --}}
            placeholder: '{{ __("admin.placeholders.select_teacher") }}',
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

        // SweetAlert konfirmasi sebelum submit (Create)
        $('#btnCreateKelas').on('click', function() {
            Swal.fire({
                {{-- [DIUBAH] --}}
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Pastikan ID form create Anda benar
                    $('#storeFormKelas').submit();
                }
            });
        });
    });

    // [FUNGSI JAVASCRIPT DIUBAH TOTAL]
    // Fungsi ini sekarang mengambil data via AJAX
    function editButton(button) {
        const id = $(button).data('id');
        const url = $(button).data('url'); // Ambil URL dari data-url

        // 1. Set action form edit
        const form = document.getElementById('editForm');
        form.action = '/kelas/ubah/' + id; // Sesuaikan URL update Anda

        // 2. Panggil AJAX untuk mengambil data JSON
        $.get(url, function (data) {

            // 3. Isi field input di dalam modal edit
            // 'data' adalah JSON yang Anda kirim dari KelasController@show

            // Isi dropdown modul
            $('#edit_modul_id').val(data.modul_id);

            // Isi input nama kelas (ID dan EN)
            // data.nama_kelas adalah objek: {"id": "NAMA ID", "en": "NAMA EN"}
            $('#edit_nama_kelas_id').val(data.nama_kelas.id);
            $('#edit_nama_kelas_en').val(data.nama_kelas.en);

            // 4. Tampilkan modal-nya
            $('#editModal').modal('show');

        }).fail(function() {
            // Tampilkan error jika gagal mengambil data
            Swal.fire('Error', 'Gagal mengambil data kelas.', 'error');
        });
    }


    // Konfirmasi simpan perubahan (Update)
    document.getElementById('btnUpdate').addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            {{-- [DIUBAH] --}}
            title: '{{ __("admin.swal.update_title") }}',
            text: "{{ __("admin.swal.update_text") }}",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}',
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
            {{-- [DIUBAH] --}}
            title: '{{ __("admin.swal.delete_title") }}',
            text: "{{ __("admin.swal.delete_text") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

</script>
@endpush
