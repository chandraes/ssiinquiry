@extends('layouts.app')

@section('title')
{{ __('admin.modul.detail.title') }}: {{ $modul->judul }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('admin.modul.detail.module_info') }}</h5>
                </div>
                <div class="card-body">
                    <h4>{{ $modul->judul }}</h4>
                    <p class="text-muted">{!! $modul->deskripsi !!}</p>
                    <hr>

                    <p>
                        <strong>{{ __('admin.modul.detail.tools') }} :</strong>
                        <span class="badge bg-info">{{ count($modul->phyphox_id ?? []) }} {{__('admin.dashboard.tools')}}</span>
                    </p>
                    <p>
                        <strong>{{ __('admin.modul.detail.owner') }} :</strong> {{ $modul->owner->name ?? 'N/A' }}
                    </p>

                    <hr>

                    {{-- 🔹 Form Upload Rencana Pembelajaran --}}
                    <form action="{{ route('modul.uploadRPS', $modul->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="rps_file" class="form-label fw-bold">
                                <i class="fa fa-upload me-1"></i> {{__('admin.modul.detail.rps')}}
                            </label>
                            <input type="file" name="rps_file" id="rps_file" class="form-control"
                                accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">{{__('admin.modul.detail.rps_instruction')}}</small>
                        </div>

                        {{-- Tombol di kanan bawah --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save me-1"></i> {{__('admin.modul.detail.upload')}}
                            </button>

                            @if($modul->rps_file)
                                <a href="{{ asset('storage/'.$modul->rps_file) }}" target="_blank"
                                class="btn btn-info ms-2">
                                    <i class="fa fa-file me-1"></i> {{__('admin.modul.detail.view')}}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.modul.detail.submodule_list') }}</h5>

                    {{-- Tombol memicu modal input sub-modul --}}
                    {{-- [DIUBAH] Tombol "Tambah Sub Modul" sekarang menjadi Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addSubModulMenu"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus me-2"></i>{{ __('admin.modul.detail.add_submodule') }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="addSubModulMenu">
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'learning' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="learning">
                                    {{__('admin.modul.detail.dropdown_materi')}}
                                </a>
                            </li>
                            {{-- Tombol ini akan membuka modal yang sama, tapi mengirim data-type 'reflection' --}}
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="reflection">
                                    {{__('admin.modul.detail.dropdown_question')}}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#createSubModulModal" data-type="practicum">
                                    {{__('admin.modul.detail.dropdown_practicum')}}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                    data-bs-target="#createSubModulModal" data-type="forum">
                                    {{__('admin.modul.detail.dropdown_forum')}}
                                </a>
                            </li>
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
                                class="list-group-item list-group-item-action row">
                                <div class="col-md-12">
                                    <h6 class="mb-0">{{ $subModul->order }}. {{ $subModul->title }}</h6>
                                    <small class="text-muted">{!! $subModul->description !!}</small>
                                </div>

                                {{-- [PERUBAHAN] Kumpulan Tombol Aksi (Edit & Delete) --}}
                                {{-- Hentikan klik agar link <a> tidak terpicu --}}
                                    <div onclick="event.preventDefault();" class="col-md-12 text-end">
                                        <button class="btn btn-warning btn-sm"
                                            title="{{ __('admin.button.edit') }}"
                                            data-url="{{ route('submodul.show.json', $subModul->id) }}"
                                            data-update-url="{{ route('submodul.update', $subModul->id) }}"
                                            onclick="editSubModul(this)">
                                            <i class="fa fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm"
                                            title="{{ __('admin.button.delete') }}"
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
                                    {{ __('admin.modul.detail.no_submodule') }}
                                </div>
                                @endforelse
                    </div>
                </div>
                <div class="card-footer text-center">
                    <div class="col-md-12">
                        <a href="{{ route('modul') }}" class="btn btn-secondary button-lg">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back_to') }}{{__('admin.modul.page_title')}}
                        </a>
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
<script>

    // [PERUBAHAN DI SINI] Inisialisasi TinyMCE
    tinymce.init({
        // Gunakan selector class untuk menargetkan SEMUA editor
        selector: 'textarea.rich-text-editor',
        plugins: 'lists link code fullscreen',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link code | fullscreen',
        menubar: false,
        height: 250,
        license_key: 'gpl',
    });

    /**
     * 1. FUNGSI UNTUK MENGISI MODAL EDIT (DIBERSIHKAN)
     * Hanya mengisi field dasar + max_points.
     */
    function editSubModul(button) {
        var modal = $('#editSubModulModal');
        var form = $('#editSubModulForm');
        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');

        form.attr('action', updateUrl);

        $.get(dataUrl, function (data) {
            // === Mengisi Data Umum ===
            modal.find('#edit_submodul_type').val(data.type);
            modal.find('#edit_sub_title_id').val(data.title.id);
            modal.find('#edit_sub_title_en').val(data.title.en);
            modal.find('#edit_sub_order').val(data.order);
            modal.find('#edit-max-points').val(data.max_points);

            // === Update textarea secara manual dulu ===
            modal.find('#edit_sub_description_id').val(data.description.id);
            modal.find('#edit_sub_description_en').val(data.description.en);

            // === Update TinyMCE jika sudah aktif ===
            if (tinymce.get('edit_sub_description_id')) {
                tinymce.get('edit_sub_description_id').setContent(data.description.id || '');
            }
            if (tinymce.get('edit_sub_description_en')) {
                tinymce.get('edit_sub_description_en').setContent(data.description.en || '');
            }

            // === Logika poin maksimal ===
            var maxPointsField = modal.find('#edit_max_points_wrapper');
            var maxPointsInput = modal.find('#edit-max-points');
            if (data.type === 'learning') {
                maxPointsField.addClass('d-none');
                maxPointsInput.prop('required', false);
            } else {
                maxPointsField.removeClass('d-none');
                maxPointsInput.prop('required', true);
            }

            modal.modal('show');
        }).fail(function () {
            Swal.fire('Error', 'Gagal mengambil data sub modul.', 'error');
        });
    }


    /**
     * 2. EVENT LISTENER UNTUK MODAL CREATE (DIBERSIHKAN)
     * Hanya mengurus Poin Maksimal.
     */
    $('#createSubModulModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var subModulType = button.data('type');
        var modal = $(this);

        modal.find('#create_submodul_type').val(subModulType);

        // Temukan field poin
        var maxPointsField = modal.find('#create_max_points_wrapper');
        var maxPointsInput = modal.find('#create-max-points');

        // === LOGIKA FORUM DIHAPUS DARI SINI ===

        // Logika Poin Maksimal
        if (subModulType === 'learning') {
            maxPointsField.addClass('d-none');
            maxPointsInput.prop('required', false);
            maxPointsInput.val(0);
        } else {
            maxPointsField.removeClass('d-none');
            maxPointsInput.prop('required', true);
            maxPointsInput.val(10);
        }
    });

    /**
     * 3. Hancurkan TinyMCE saat modal ditutup
     * (SEMUA LOGIKA TINYMCE DIHAPUS DARI SINI)
     */
    // ... Tidak ada lagi yang perlu dilakukan di sini ...


    /**
     * 4. FUNGSI UNTUK KONFIRMASI SUBMIT FORM (SweetAlert)
     * (Disederhanakan, 'tinymce.triggerSave()' dihapus)
     */
    $(document).ready(function() {

        // Konfirmasi untuk form 'create'
        $('#storeSubModulForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            // tinymce.triggerSave() DIHAPUS
            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: "{{ __("admin.swal.save_text") }}",
                icon: 'question', showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => { if (result.isConfirmed) { form.submit(); } });
        });

        // Konfirmasi untuk form 'edit'
        $('#editSubModulForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            // tinymce.triggerSave() DIHAPUS
            Swal.fire({
                title: '{{ __("admin.swal.update_title") }}',
                text: "{{ __("admin.swal.update_text") }}",
                icon: 'question', showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
                reverseButtons: true
            }).then((result) => { if (result.isConfirmed) { form.submit(); } });
        });
    });

    /**
     * 5. FUNGSI UNTUK KONFIRMASI DELETE (SweetAlert)
     * (Tidak berubah)
     */
    function deleteSubModul(id) {
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: "{{ __("admin.swal.delete_text") }}",
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
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
