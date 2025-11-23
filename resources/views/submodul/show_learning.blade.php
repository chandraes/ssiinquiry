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
@push('css')

@endpush
@push('js')
{{-- [BARU] JavaScript untuk Edit dan Delete Material --}}
<script>

    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .ui-datepicker, .modal").length) {
            e.stopImmediatePropagation();
        }
    });
    tinymce.init({
        selector: 'textarea.rich-text-editor',
        height: 250,
        menubar: false,
        license_key: 'gpl',
        z_index: 999999,

        // 1. PENTING: Load CSS KaTeX ke dalam iframe TinyMCE agar styling equation muncul
        content_css: [
            'https://cdn.jsdelivr.net/npm/katex@0.16.25/dist/katex.min.css'
        ],

        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',

        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | alignleft aligncenter alignright alignjustify| mathjax | bullist numlist| link image media table | code fullscreen',

        setup: function (editor) {

            // Fungsi untuk merender LaTeX
            const renderMathInEditor = () => {
                // Kita akses 'window.katex' dari parent (halaman utama)
                // atau pastikan library katex sudah terload di halaman utama
                const katex = window.katex || window.parent.katex;

                if (!katex) {
                    console.warn('KaTeX library not found');
                    return;
                }

                // Cari semua elemen dengan class 'latex-math' di dalam editor
                const mathElements = editor.dom.select('.latex-math');

                mathElements.forEach(el => {
                    // Cek apakah sudah dirender untuk mencegah re-render berulang
                    if (el.getAttribute('data-rendered') === 'true') return;

                    const latexCode = el.getAttribute('data-latex');
                    if (!latexCode) return;

                    try {
                        // Render ke HTML string
                        const renderedHtml = katex.renderToString(latexCode, {
                            throwOnError: false,
                            displayMode: false // Ubah true jika ingin display block
                        });

                        // Masukkan HTML yang sudah dirender ke dalam span
                        // Kita set contentEditable=false agar user tidak sengaja mengedit HTML hasil render
                        el.innerHTML = renderedHtml;
                        el.setAttribute('contenteditable', 'false');
                        el.setAttribute('data-rendered', 'true'); // Tandai sudah dirender

                        // Tambahkan event listener agar saat diklik bisa diedit (Opsional)
                        el.style.cursor = 'pointer';

                    } catch (e) {
                        el.innerHTML = '<span style="color:red">Error rendering LaTeX</span>';
                    }
                });
            };

            // Jalankan render saat konten berubah
            editor.on('NodeChange', renderMathInEditor);
            editor.on('SetContent', renderMathInEditor);

            // 2. PENTING: Tambahkan event double click untuk mengedit rumus
            editor.on('DblClick', function(e) {
                const target = e.target.closest('.latex-math');
                if (target) {
                    const currentLatex = target.getAttribute('data-latex');
                    // Buka kembali dialog insert dengan value yang ada
                    openMathDialog(currentLatex, target);
                }
            });

            // Fungsi Helper untuk membuka dialog
            const openMathDialog = (initialValue = '', targetElement = null) => {
                editor.windowManager.open({
                    title: 'Insert/Edit Math Formula (LaTeX)',
                    body: {
                        type: 'panel',
                        items: [{
                            type: 'textarea',
                            name: 'latex',
                            label: 'LaTeX Code',
                            placeholder: '\\frac{1}{2}mv^2',
                        }]
                    },
                    initialData: {
                        latex: initialValue
                    },
                    buttons: [
                        { type: 'cancel', text: 'Cancel' },
                        { type: 'submit', text: targetElement ? 'Update' : 'Insert', primary: true }
                    ],
                    onSubmit: function (api) {
                        const latex = api.getData().latex;
                        if (latex.trim() !== '') {
                            // 3. PENTING: Kita bungkus dengan SPAN dan CLASS yang spesifik
                            // Kita simpan kode asli di attribute 'data-latex'
                            const content = `<span class="latex-math" data-latex="${latex}">\\(${latex}\\)</span>&nbsp;`;

                            if (targetElement) {
                                // Jika mode edit, ganti elemen lama
                                editor.dom.replace(editor.dom.createFragment(content), targetElement);
                            } else {
                                // Jika insert baru
                                editor.insertContent(content);
                            }

                            // Trigger render manual
                            setTimeout(renderMathInEditor, 50);
                        }
                        api.close();
                    }
                });
            }

            editor.ui.registry.addButton('mathjax', {
                text: '∑ Math',
                tooltip: 'Insert Math Formula',
                onAction: function () {
                openMathDialog();
                }
            });
        }
    });

    // Fungsi untuk membersihkan status render agar dikalkulasi ulang oleh KaTeX
    function prepareContentForEditor(content) {
        if (!content) return '';

        // 1. Jika content adalah raw text LaTeX biasa (misal: \( E=mc^2 \))
        // dan belum dibungkus span (kasus migrasi data lama), kita bungkus dulu.
        // Regex ini mencari pola \( ... \) yang belum punya class latex-math
        if (!content.includes('class="latex-math"')) {
            content = content.replace(/\\\((.*?)\\\)/g, function(match, capture) {
                return `<span class="latex-math" data-latex="${capture}">\\(${capture}\\)</span>`;
            });
        }

        // 2. Reset atribut data-rendered dari konten yang sudah ada
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;

        const mathElements = tempDiv.querySelectorAll('.latex-math');
        mathElements.forEach(el => {
            // Hapus flag 'data-rendered' agar script init TinyMCE mau merender ulang
            el.removeAttribute('data-rendered');
            // Reset contentEditable
            el.setAttribute('contenteditable', 'false');
            // Opsional: Kembalikan isi ke format raw agar transisi render terlihat bersih
            // (Hanya jika innerHTML rusak, jika tidak baris ini bisa di-skip)
            // el.innerHTML = '\\(' + el.getAttribute('data-latex') + '\\)';
        });

        return tempDiv.innerHTML;
    }

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

            // --- BAGIAN RICH TEXT ---
            const editorId = tinymce.get('edit_content_rich_text_id');
            const editorEn = tinymce.get('edit_content_rich_text_en');

            // Reset editor
            if (editorId) editorId.setContent('');
            if (editorEn) editorEn.setContent('');

            if (data.type === 'rich_text') {
                $('#edit-richtext-field').show();
                $('#edit-url-field').hide();

                // Helper untuk set content dengan aman
                const setEditorContentSafe = (editor, contentRaw) => {
                    if (editor && contentRaw) {
                        // 1. Bersihkan konten (hapus data-rendered="true")
                        const cleanContent = prepareContentForEditor(contentRaw);

                        // 2. Set Content
                        editor.setContent(cleanContent);

                        // 3. PENTING: Clear Undo Manager
                        // Agar saat user tekan Ctrl+Z, editor tidak kosong (awal load dianggap state 0)
                        editor.undoManager.clear();
                    }
                };

                setEditorContentSafe(editorId, data.content.id);
                setEditorContentSafe(editorEn, data.content.en);

                modal.find('#edit_content_url').val('');

            } else {
                $('#edit-richtext-field').hide();
                $('#edit-url-field').show();
                modal.find('#edit_content_url').val(data.content_url ?? '');
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
