@extends('layouts.app')

@section('title')
    {{ $subModul->title }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $subModul->title }}</h2>
            <p class="text-muted">{!! $subModul->description !!}</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Learning Materials</h5>

            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addMaterialMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-plus me-2"></i>Tambah Materi
                </button>
                <ul class="dropdown-menu" aria-labelledby="addMaterialMenu">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addVideoModal">Video</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addArticleModal">Artikel (URL)</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addInfographicModal">Infografis (URL)</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addRegulationModal">Regulasi (URL)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addRichTextModal">Teks / Artikel (Rich Text)</a></li>
                </ul>
            </div>
        </div>

        <div class="card-body">
            @forelse($subModul->learningMaterials as $material)
                <div class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 border">
                    <div>
                        <strong>{{ $material->title }}</strong>
                        <small class="badge
                            @if($material->type == 'video') bg-danger
                            @elseif($material->type == 'article') bg-info
                            @elseif($material->type == 'infographic') bg-success
                            @elseif($material->type == 'rich_text') bg-dark
                            @else bg-warning @endif
                        ">{{ $material->type }}</small>

                        {{-- Logika Tampilan (dari jawaban sebelumnya) --}}
                        @if($material->type == 'rich_text')
                            <div class="rich-text-content border p-2 rounded-2 mt-2" style="max-height: 200px; overflow-y: auto;">
                                {!! $material->content !!}
                            </div>
                        @else
                            @php
                                $url = null;
                                if (is_array($material->content) && isset($material->content['url'])) {
                                    $url = $material->content['url'];
                                } else {
                                    $rawContent = json_decode($material->getRawOriginal('content'), true);
                                    if (is_array($rawContent) && isset($rawContent['url'])) {
                                        $url = $rawContent['url'];
                                    }
                                }
                            @endphp

                            @if($url)
                                <p class="mb-0 text-muted">
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $url }}
                                    </a>
                                </p>
                            @endif
                        @endif
                    </div>

                    {{-- [PERUBAHAN DI SINI] Tombol Edit & Delete --}}
                    <div>
                        <button class="btn btn-warning btn-sm"
                                title="{{ __('admin.material_modal.edit_title') }}"
                                data-url="{{ route('learning_material.edit.json', $material->id) }}"
                                data-update-url="{{ route('learning_material.update', $material->id) }}"
                                onclick="editMaterial(this)">
                            <i class="fa fa-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm"
                                title="{{ __('admin.kelas.delete_title') }}"
                                onclick="deleteMaterial({{ $material->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>

                <form id="delete-material-form-{{ $material->id }}"
                      action="{{ route('learning_material.destroy', $material->id) }}"
                      method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>

            @empty
                <div class="alert alert-info text-center">
                    Belum ada materi pembelajaran untuk sub modul ini.
                </div>
            @endforelse
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

{{-- Memuat SEMUA Modal yang Mungkin Dibutuhkan --}}
@include('learning_material.modals.video_modal')
@include('learning_material.modals.article_modal')
@include('learning_material.modals.infographic_modal')
@include('learning_material.modals.regulation_modal')
@include('learning_material.modals.rich_text_modal')
@include('learning_material.modals.edit_modal') {{-- <-- [BARU] Sertakan Modal Edit --}}

@endsection

@push('js')
{{-- [BARU] JavaScript untuk Edit dan Delete Material --}}
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
     * 1. FUNGSI UNTUK MENGISI MODAL EDIT
     */
    function editMaterial(button) {
        var modal = $('#editMaterialModal');
        var form = $('#editMaterialForm');

        var dataUrl = $(button).data('url');
        var updateUrl = $(button).data('update-url');

        // Set action form
        form.attr('action', updateUrl);

        // Ambil data JSON dari controller
        $.get(dataUrl, function(data) {

            // Isi input judul
            modal.find('[name="title[id]"]').val(data.title.id);
            modal.find('[name="title[en]"]').val(data.title.en);

            // Reset semua editor sebelum digunakan
            if (tinymce.get('edit_content_rich_text_id')) {
                tinymce.get('edit_content_rich_text_id').setContent('');
            }
            if (tinymce.get('edit_content_rich_text_en')) {
                tinymce.get('edit_content_rich_text_en').setContent('');
            }

            // Cek tipe konten
            if (data.type === 'rich_text') {
                // Tampilkan field Rich Text, sembunyikan URL
                $('#edit-richtext-field').show();
                $('#edit-url-field').hide();

                // Isi textarea (TinyMCE pakai API)
                if (tinymce.get('edit_content_rich_text_id')) {
                    tinymce.get('edit_content_rich_text_id').setContent(data.content.id ?? '');
                }
                if (tinymce.get('edit_content_rich_text_en')) {
                    tinymce.get('edit_content_rich_text_en').setContent(data.content.en ?? '');
                }

                // Kosongkan field URL
                modal.find('#edit_content_url').val('');

            } else {
                // Tampilkan field URL, sembunyikan Rich Text
                $('#edit-richtext-field').hide();
                $('#edit-url-field').show();

                // Isi input URL
                modal.find('#edit_content_url').val(data.content_url ?? '');

                // Kosongkan isi editor Rich Text
                if (tinymce.get('edit_content_rich_text_id')) {
                    tinymce.get('edit_content_rich_text_id').setContent('');
                }
                if (tinymce.get('edit_content_rich_text_en')) {
                    tinymce.get('edit_content_rich_text_en').setContent('');
                }
            }

            // Tampilkan modal
            modal.modal('show');

        }).fail(function() {
            Swal.fire('Error', 'Gagal mengambil data materi.', 'error');
        });
    }


    /**
     * 2. FUNGSI KONFIRMASI DELETE
     */
    function deleteMaterial(id) {
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
                // Submit form delete yang sesuai
                document.getElementById('delete-material-form-' + id).submit();
            }
        })
    }

    /**
* 3. FUNGSI KONFIRMASI UPDATE (SweetAlert)
     */
    $(document).ready(function() {
        $('#editMaterialForm').on('submit', function(e) {
            e.preventDefault();
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
                    form.submit();
                }
            });
        });

        // (Anda bisa tambahkan konfirmasi untuk form 'create' di sini jika mau)
    });
</script>
@endpush
