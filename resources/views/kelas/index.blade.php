@extends('layouts.app')
@section('title')
{{ __('admin.kelas.title') }}
@endsection
@section('content')
@include('swal')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                @php
                // Variabel userLogin sudah dikirim dari controller
                $isGuru = $userLogin->roles->contains('name', 'Guru');
                $isAdmin = $userLogin->roles->contains('name', 'Administrator');
                @endphp

                {{-- Memuat modal (sudah benar) --}}
                @include('kelas.create')
                @include('kelas.edit')

                <div class="box-body" style="height: 1200px">
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header justify-content-between">
                                    <h2 class="card-title">{{ __('admin.kelas.list_title') }}</h2>
                                    @if ($isAdmin) {{-- Hanya Admin yang boleh buat kelas baru --}}
                                    <button type="button" class="btn btn-primary waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#createModalKelas">
                                        <i class="fa fa-plus me-2"></i>{{ __('admin.kelas.add_button') }}
                                    </button>
                                    @endif
                                </div>

                                <div class="ms-auto pageheader-btn"></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom w-100" id="data">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle">{{ __('admin.kelas.table_no') }}</th>
                                                    <th class="text-start align-middle">{{ __('admin.kelas.table_module') }}</th>
                                                    <th class="text-start align-middle">{{ __('admin.kelas.table_class') }}</th>
                                                    <th class="text-center align-middle">{{ __('admin.kelas.table_participants') }}</th>
                                                    <th class="text-start align-middle">{{ __('admin.kelas.table_teacher') }}</th>
                                                    <th class="text-center align-middle">{{ __('admin.kelas.table_action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- $data sekarang sudah difilter dan dioptimalkan oleh controller --}}
                                                @foreach ($data as $d)
                                                <tr>
                                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                    <td class="text-start align-middle">
                                                        {{-- Nullsafe: cek jika modul terhapus --}}
                                                        {{ $d->modul?->judul }}
                                                    </td>
                                                    <td class="text-start align-middle">
                                                        {{-- Link ke Gradebook (kelas.show) --}}
                                                        <a href="{{ route('kelas.show', $d->id) }}" title="Lihat Gradebook">{{ $d->nama_kelas }}</a>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{-- [PERBAIKAN] Gunakan 'peserta_count' (dari relasi 'peserta' baru) --}}
                                                        @if ($d->peserta_count == 0)
                                                        <a href="{{ route('kelas.peserta', $d->id) }}"
                                                            class="btn btn-md btn-success"
                                                            title="{{ __('admin.kelas.add_participant_title') }}">
                                                            <i class="fa fa-users"></i> {{ __('admin.kelas.add_participant_text') }}
                                                        </a>
                                                        @else
                                                        <span class="me-2"></span>
                                                        <a href="{{ route('kelas.peserta', $d->id) }}"
                                                            title="{{ __('admin.kelas.view_participant_title') }}">
                                                            {{ $d->peserta_count }} {{-- Ini sekarang HANYA menghitung siswa --}}
                                                        </a>
                                                        @endif
                                                    </td>
                                                   <td class="text-start align-middle">
                                                        {{-- [PERBAIKAN] Relasi 'guru' (owner) adalah belongsTo --}}
                                                        {{ $d->guru?->name ?? 'Belum Ditugaskan' }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        {{-- Tombol Edit (Hanya Admin) --}}
                                                        @if ($isAdmin)
                                                        <button class="btn btn-icon bg-warning"
                                                            title="{{ __('admin.kelas.edit_title') }}"
                                                            data-id="{{ $d->id }}"
                                                            {{-- Arahkan ke route JSON baru --}}
                                                            data-url="{{ route('kelas.json', $d->id) }}"
                                                            onclick="editButton(this)">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        @endif

                                                        {{-- Tombol Delete (Hanya Admin) --}}
                                                        @if ($isAdmin)
                                                        <button type="button" class="btn btn-icon bg-danger my-2"
                                                            title="{{ __('admin.kelas.delete_title') }}"
                                                            onclick="deleteButton({{ $d->id }})">
                                                            <i class="fe fe-trash"></i>
                                                        </button>

                                                        <form action="{{ route('kelas.delete', $d->id) }}" method="POST"
                                                            id="delete-form-{{ $d->id }}">
                                                            @csrf
                                                            @method('delete')
                                                        </form>
                                                        @endif

                                                        {{-- Tombol Masuk ke Gradebook (untuk Guru) --}}
                                                        @if ($isGuru)
                                                        <a href="{{ route('kelas.show', $d->id) }}" class="btn btn-icon btn-primary" title="Buka Gradebook">
                                                            <i class="fa fa-check-square-o"></i>
                                                        </a>
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
        // Select2 untuk Modal CREATE
        $('#guru_id').select2({
            dropdownParent: $('#createModalKelas'),
            placeholder: '{{ __("admin.placeholders.select_teacher") }}',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-pengajar') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return { id: item.id, text: item.name }
                        })
                    };
                },
                cache: true
            }
        });

        // Select2 untuk Modal EDIT
        // (Asumsi ID select di kelas/edit.blade.php adalah 'edit_guru_id')
        $('#edit_guru_id').select2({
            dropdownParent: $('#editModal'), // Target modal edit
            placeholder: '{{ __("admin.placeholders.select_teacher") }}',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-pengajar') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return { id: item.id, text: item.name }
                        })
                    };
                },
                cache: true
            }
        });

        // SweetAlert konfirmasi sebelum submit (Create)
        $('#btnCreateKelas').on('click', function() {
            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#storeFormKelas').submit();
                }
            });
        });
    });

    /**
     * [FUNGSI DIPERBAIKI]
     * Mengisi modal edit dengan data dari AJAX (route 'kelas.json')
     */
    function editButton(button) {
        const id = $(button).data('id');
        const url = $(button).data('url'); // Ambil URL JSON

        // 1. Set action form edit
        const form = document.getElementById('editForm');
        form.action = '{{ url("kelas/ubah") }}/' + id;

        // 2. Panggil AJAX
        $.get(url, function (data) {
            // data = { modul_id: ..., nama_kelas: {id, en}, guru: {id, name} }

            // 3. Isi field input di dalam modal edit
            $('#edit_modul_id').val(data.modul_id);
            $('#edit_nama_kelas_id').val(data.nama_kelas.id);
            $('#edit_nama_kelas_en').val(data.nama_kelas.en);

            // 4. Isi Select2 Guru (Owner)
            var $guruSelect = $('#edit_guru_id');
            if ($guruSelect.length) { // Cek jika elemen ada
                if (data.guru) {
                    // Buat <option> baru untuk guru yang ada
                    var option = new Option(data.guru.name, data.guru.id, true, true);
                    $guruSelect.append(option).trigger('change');
                } else {
                    // Kosongkan jika tidak ada guru
                    $guruSelect.val(null).trigger('change');
                }
            }

            // 5. Tampilkan modal-nya
            $('#editModal').modal('show');

        }).fail(function() {
            Swal.fire('Error', 'Gagal mengambil data kelas.', 'error');
        });
    }


    // Konfirmasi simpan perubahan (Update)
    document.getElementById('btnUpdate').addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
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
