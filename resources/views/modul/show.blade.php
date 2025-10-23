@extends('layouts.app')

@section('title')
{{ __('admin.modul_detail.title') }}: {{ $modul->judul }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('admin.modul_detail.module_info') }}</h5>
                </div>
                <div class="card-body">
                    {{-- Spatie otomatis menampilkan bahasa yang aktif --}}
                    <h4>{{ $modul->judul }}</h4>
                    <p class="text-muted">
                        {{ $modul->deskripsi }}
                    </p>
                    <hr>
                    <p>
                        <strong>Phyphox Tools:</strong>
                        {{-- Asumsi phyphox_id adalah array ID --}}
                        <span class="badge bg-info">{{ count($modul->phyphox_id ?? []) }} Alat</span>
                    </p>
                    <p>
                        <strong>Pembuat: {{ $modul->owner->name ?? 'N/A' }} </strong>
                        {{-- Baris ini dari kode Anda sebelumnya --}}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.modul_detail.submodule_list') }}</h5>

                    {{-- Tombol memicu modal input sub-modul --}}
                    {{-- [DIUBAH] Tombol "Tambah Sub Modul" sekarang menjadi Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addSubModulMenu"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus me-2"></i>{{ __('admin.modul_detail.add_submodule') }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="addSubModulMenu">
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'learning' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="learning">
                                    Sub Modul Materi (Video, Teks, dll)
                                </a>
                            </li>
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'reflection' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="reflection">
                                    Sub Modul Pertanyaan Refleksi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="practicum">
                                    Sub Modul Praktikum Phyphox
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createSubModulModal" data-type="forum">
                                Sub Modul Forum Debat
                            </a></li>
                            {{-- <li><a class="dropdown-item" href="#" data-type="forum">Sub Modul Forum (Segera)</a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        {{-- Loop semua sub-modul yang sudah di-load dari controller --}}
                        @forelse($modul->subModules as $subModul)

                        {{-- [PERUBAHAN] Item sekarang menjadi link <a> --}}
                            <a href="{{ route('submodul.show', $subModul->id) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $subModul->order }}. {{ $subModul->title }}</h6>
                                    <small class="text-muted">{{ $subModul->description }}</small>
                                </div>

                                {{-- [PERUBAHAN] Kumpulan Tombol Aksi (Edit & Delete) --}}
                                {{-- Hentikan klik agar link <a> tidak terpicu --}}
                                    <div onclick="event.preventDefault();">
                                        <button class="btn btn-warning btn-sm"
                                            title="{{ __('admin.modul_detail.edit') }}"
                                            data-url="{{ route('submodul.show.json', $subModul->id) }}"
                                            data-update-url="{{ route('submodul.update', $subModul->id) }}"
                                            onclick="editSubModul(this)">
                                            <i class="fa fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm"
                                            title="{{ __('admin.modul_detail.delete') }}"
                                            onclick="deleteSubModul({{ $subModul->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </a>

                                <form id="delete-form-{{ $subModul->id }}"
                                    action="{{ route('submodul.destroy', $subModul->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                @empty
                                <div class="list-group-item text-center">
                                    {{ __('admin.modul_detail.no_submodule') }}
                                </div>
                                @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Memuat Modal Create (Anda sudah punya file ini) --}}
@include('submodul.create_modal')

{{-- Memuat Modal Edit (dari jawaban saya sebelumnya) --}}
@include('submodul.edit_modal')

@endsection

@push('js')
{{-- JavaScript untuk mengaktifkan modal Edit dan Delete --}}
<script>
    /**
     * 1. FUNGSI UNTUK MENGISI MODAL EDIT
     * Dipanggil oleh tombol "Edit"
     */
    function editSubModul(button) {
        var modal = $('#editSubModulModal');
        var form = $('#editSubModulForm');

        // Ambil URL dari data- attribute
        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');

        // Set action form modal edit
        form.attr('action', updateUrl);

        // Ambil data JSON dari controller
        $.get(dataUrl, function(data) {
            // 'data' adalah JSON dari SubModulController@showJson

            // Isi semua input di modal
            modal.find('#edit_sub_title_id').val(data.title.id);
            modal.find('#edit_sub_title_en').val(data.title.en);

            modal.find('#edit_sub_description_id').val(data.description.id);
            modal.find('#edit_sub_description_en').val(data.description.en);

            modal.find('#edit_sub_order').val(data.order);

            // Tampilkan modal
            modal.modal('show');

        }).fail(function() {
            Swal.fire('Error', 'Gagal mengambil data sub modul.', 'error');
        });
    }

    $('#createSubModulModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var subModulType = button.data('type');
        var modal = $(this);

        // 1. Set hidden input 'type'
        var typeInput = modal.find('#create_submodul_type');
        typeInput.val(subModulType);

        // 2. Temukan field-field opsional
        var forumFields = modal.find('#forum_settings_fields');
        // (Jika Anda punya field lain untuk 'practicum' atau 'reflection', definisikan di sini)

        // 3. Logika Show/Hide
        if (subModulType === 'forum') {
            forumFields.show();
            // Inisialisasi ulang TinyMCE jika perlu, atau pastikan ia mentarget
            // textarea.rich-text-editor di dalam #forum_settings_fields
            tinymce.init({ selector: '#forum_settings_fields textarea.rich-text-editor',license_key: 'gpl'});
        } else {
            forumFields.hide();
            // Hancurkan (destroy) TinyMCE agar tidak menumpuk
            if (tinymce.get('debate_rules_id')) { // Beri ID pada textarea 'debate_rules'
                 tinymce.get('debate_rules_id').destroy();
            }
        }
    });

    /**
     * 2. FUNGSI UNTUK KONFIRMASI UPDATE (SweetAlert)
     * Dijalankan saat form edit di-submit
     */
    $(document).ready(function() {
        $('#editSubModulForm').on('submit', function(e) {
            e.preventDefault(); // Hentikan submit form standar
            var form = this;

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
                    form.submit(); // Lanjutkan submit form
                }
            });
        });

        // Anda juga bisa tambahkan konfirmasi untuk form 'create'
        $('#storeSubModulForm').on('submit', function(e) {
            e.preventDefault(); // Hentikan submit form standar
            var form = this;

            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Lanjutkan submit form
                }
            });
        });
    });

    /**
     * 3. FUNGSI UNTUK KONFIRMASI DELETE (SweetAlert)
     * Dipanggil oleh tombol "Delete"
     */
    function deleteSubModul(id) {
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: "{{ __("admin.swal.delete_text") }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                // Cari form tersembunyi dan submit
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush
