@extends('layouts.app')

@section('title')
    {{ __('admin.practicum.title') }}: {{ $subModul->title }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $subModul->title }}</h2>
            <p class="text-muted">{{ $subModul->description }}</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ __('admin.practicum.instructions') }}</h5>
                <small class="text-muted">{{ __('admin.practicum.instructions_desc') }}</small>
            </div>
            <div>
                @php
                    $instruction = $subModul->learningMaterials->where('type', 'rich_text')->first();
                @endphp

                @if($instruction)
                    {{-- Tombol Edit Petunjuk --}}
                    <button class="btn btn-warning btn-sm"
                            title="{{ __('admin.practicum.edit_instruction') }}"
                            data-url="{{ route('learning_material.edit.json', $instruction->id) }}"
                            data-update-url="{{ route('learning_material.update', $instruction->id) }}"
                            onclick="editMaterial(this)"> {{-- <- Menggunakan JS 'editMaterial' yang ada --}}
                        <i class="fa fa-pencil me-2"></i>{{ __('admin.practicum.edit_instruction') }}
                    </button>
                @else
                    {{-- Tombol Tambah Petunjuk --}}
                    <button class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#addRichTextModal"> {{-- <- Membuka modal 'rich_text' --}}
                        <i class="fa fa-plus me-2"></i>{{ __('admin.practicum.add_instruction') }}
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if($instruction)
                <div class="rich-text-content border p-3 rounded-2">
                    {!! $instruction->content !!}
                </div>
            @else
                <div class="alert alert-info text-center">
                    {{ __('admin.practicum.no_practicum_instruction') }}.
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
         <div class="card-header d-flex justify-content-between align-items-center">
             <div>
                <h5 class="mb-0">{{ __('admin.practicum.upload_slots') }}</h5>
                <small class="text-muted">{{ __('admin.practicum.upload_slots_desc') }}</small>
             </div>
             <div>
                {{-- [DIUBAH] Tombol ini sekarang memicu modal 'addSlotModal' --}}
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSlotModal">
                    <i class="fa fa-plus me-2"></i>{{ __('admin.practicum.add_slot') }}
                </button>
             </div>
         </div>
         <div class="card-body">
             <div class="list-group">
                @forelse($subModul->practicumUploadSlots as $slot)
                    <div class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 border">
                        <div>
                            <strong>{{ $slot->label }}</strong><br>
                            <small class="text-muted">
                                {{ $slot->experiment_group ? 'Grup: ' . $slot->experiment_group . ' | ' : '' }}
                                {{ $slot->description ? 'File: ' . $slot->description : '' }}
                            </small>
                        </div>

                        {{-- [BARU] Tombol Aksi untuk Slot --}}
                        <div>
                            <button class="btn btn-warning btn-sm"
                                    title="Edit Slot"
                                    data-url="{{ route('practicum_slot.edit.json', $slot->id) }}"
                                    data-update-url="{{ route('practicum_slot.update', $slot->id) }}"
                                    onclick="editSlot(this)">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger btn-sm"
                                    title="Hapus Slot"
                                    onclick="deleteSlot({{ $slot->id }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <form id="delete-slot-form-{{ $slot->id }}"
                          action="{{ route('practicum_slot.destroy', $slot->id) }}"
                          method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @empty
                    <div class="alert alert-info text-center">
                        {{ __('admin.practicum.no_slot') }}.
                    </div>
                @endforelse
             </div>
         </div>
         <div class="card-footer">
            <div class="col-md-12">
                <a href="{{ route('modul.show', $subModul->modul_id) }}" class="btn btn-secondary button-lg">
                    <i class="fa fa-arrow-left me-1"></i> {{ __('admin.button.back_to') }}{{ __('admin.modul.detail.title') }}
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Memuat SEMUA Modal yang Dibutuhkan Halaman Ini --}}
{{-- 1. Modal untuk Petunjuk (dari Learning Material) --}}
@include('learning_material.modals.rich_text_modal')
@include('learning_material.modals.edit_modal') {{-- Modal edit 'pintar' yang sudah ada --}}

{{-- 2. Modal untuk Slot Unggahan (BARU) --}}
@include('practicum.slot_create_modal')
@include('practicum.slot_edit_modal')

@endsection

@push('js')
{{-- Memuat SEMUA JavaScript yang Dibutuhkan Halaman Ini --}}
<script>
    // ===================================================================
    // FUNGSI UNTUK PETUNJUK (Menggunakan JS 'Learning Material' yang ada)
    // ===================================================================
    function editMaterial(button) {
        var modal = $('#editMaterialModal');
        var form = $('#editMaterialForm');
        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');
        form.attr('action', updateUrl);

        $.get(dataUrl, function(data) {
            // Isi judul
            modal.find('[name="title[id]"]').val(data.title.id);
            modal.find('[name="title[en]"]').val(data.title.en);

            // Karena ini HANYA edit petunjuk, kita HANYA tampilkan rich_text
            $('#edit-richtext-field').show();
            $('#edit-url-field').hide();
            modal.find('#edit_content_rich_text_id').val(data.content.id);
            modal.find('#edit_content_rich_text_en').val(data.content.en);
            modal.find('#edit_content_url').val('');

            modal.modal('show');
        }).fail(function() {
            Swal.fire('Error', '{{__("admin.swal.failed_get_instruction")}}', 'error');
        });
    }

    // ===================================================================
    // FUNGSI BARU UNTUK SLOT UNGGAHAN
    // ===================================================================

    /**
     * 1. FUNGSI UNTUK MENGISI MODAL EDIT SLOT
     */
    function editSlot(button) {
        var modal = $('#editSlotModal');
        var form = $('#editSlotForm');
        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');

        form.attr('action', updateUrl);

        $.get(dataUrl, function(data) {
            // Isi input Label
            modal.find('#edit_slot_label_id').val(data.label.id);
            modal.find('#edit_slot_label_en').val(data.label.en);

            // Isi input Deskripsi
            modal.find('#edit_slot_description_id').val(data.description.id);
            modal.find('#edit_slot_description_en').val(data.description.en);

            // Isi input Grup & Order
            modal.find('#edit_slot_experiment_group').val(data.experiment_group);
            modal.find('#edit_slot_order').val(data.order);

            modal.modal('show');
        }).fail(function() {
            Swal.fire('Error', '{{__("admin.swal.failed_get_slot")}}', 'error');
        });
    }

    /**
     * 2. FUNGSI KONFIRMASI DELETE SLOT
     */
    function deleteSlot(id) {
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: '{{ __("admin.swal.delete.text") }}', // Teks spesifik
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-slot-form-' + id).submit();
            }
        })
    }

    /**
     * 3. FUNGSI KONFIRMASI (SweetAlert)
     */
    $(document).ready(function() {
        // Konfirmasi untuk form 'Edit Petunjuk'
        $('#editMaterialForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '{{ __("admin.swal.update_title") }}',
                text: '{{ __("admin.swal.edit.text") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => { if (result.isConfirmed) { form.submit(); } });
        });

        // Konfirmasi untuk form 'Edit Slot'
        $('#editSlotForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '{{ __("admin.swal.update_title") }}',
                text: '{{ __("admin.swal.edit.text") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.update_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
            }).then((result) => { if (result.isConfirmed) { form.submit(); } });
        });
    });
</script>
<script>
 tinymce.init({
        selector: 'textarea.rich-text-editor',
        height: 250,
        menubar: false,
        license_key: 'gpl',

        plugins: 'advlist autolink lists link image charmap preview anchor ' +
            'searchreplace visualblocks code fullscreen ' +
            'insertdatetime media table paste help wordcount',

        toolbar:
            'undo redo | blocks | ' +
            'bold italic underline | link code |' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | ' +
            'link image media table | code fullscreen',

        // OPTIONAL: biar style heading lebih modern
        style_formats: [
            { title: 'Heading 1', format: 'h1' },
            { title: 'Heading 2', format: 'h2' },
            { title: 'Heading 3', format: 'h3' },
            { title: 'Paragraph', format: 'p' }
        ],
    });
</script>
@endpush
