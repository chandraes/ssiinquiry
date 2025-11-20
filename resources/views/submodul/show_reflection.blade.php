@extends('layouts.app')

@section('title')
    {{__('admin.reflection.title')}}: {{ $subModul->title }}
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
            <h5 class="mb-0">{{__('admin.reflection.header')}}</h5>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createQuestionModal">
                <i class="fa fa-plus me-2"></i>{{__('admin.reflection.add_reflection')}}
            </button>
        </div>

        <div class="card-body">
            @forelse($subModul->reflectionQuestions as $question)
                <div class="list-group-item d-flex justify-content-between align-items-start mb-3 border p-3">
                    <div>
                        <h6 class="mb-1">
                            {{-- [BARU] Tampilkan Tipe Pertanyaan --}}
                            @if($question->type == 'multiple_choice')
                                <span class="badge bg-info me-2">Pilihan Ganda</span>
                            @else
                                <span class="badge bg-secondary me-2">Esai</span>
                            @endif
                            {{ $question->question_text }}
                        </h6>
                        <small class="text-muted">Urutan: {{ $question->order }}</small>

                        {{-- [BARU] Tampilkan Opsi & Kunci Jawaban --}}
                        @if($question->type == 'multiple_choice')
                            <ul class="list-unstyled mt-2 mb-0">
                                @foreach($question->options as $option)
                                    <li class="{{ $option->is_correct ? 'text-success fw-bold' : 'text-muted' }}">
                                        <i class="fa {{ $option->is_correct ? 'fa-check-circle' : 'fa-circle-o' }} me-2"></i>
                                        {{ $option->option_text }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Tombol Aksi (Tidak Berubah) --}}
                    <div class="text-nowrap ms-3">
                        <button class="btn btn-warning btn-sm"
                            data-url="{{ route('reflection_question.edit', $question->id) }}"
                            data-bs-toggle="modal" data-bs-target="#editQuestionModal"
                            onclick="editButton(this)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"
                            onclick="deleteQuestion({{ $question->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                        <form action="{{ route('reflection_question.destroy', $question->id) }}" method="POST"
                            id="delete-question-form-{{ $question->id }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    {{__('admin.reflection.no_reflection')}}
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

{{-- [PERBAIKAN] Panggil kedua modal di sini --}}
@include('reflection_question.create_modal')
@include('reflection_question.edit_modal')

@endsection
@push('js')
@role(['admin', 'guru'])
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
    // ===================================================================
    // == [BARU] LOGIKA FORM DINAMIS (PILIHAN GANDA)
    // ===================================================================

    // --- Helper Function untuk membuat HTML Opsi ---
    function createOptionHtml(containerType, index, optionData = null) {
        const textId = optionData ? (optionData.option_text ? optionData.option_text.id : '') : '';
        const textEn = optionData ? (optionData.option_text ? optionData.option_text.en : '') : '';
        const isChecked = optionData ? (optionData.is_correct ? 'checked' : '') : (index === 0 ? 'checked' : '');

        return `
            <div class="option-row input-group mb-2">
                <div class="input-group-text">
                    <input class="form-check-input mt-0" type="radio" name="correct_option_index" value="${index}" ${isChecked} required
                           aria-label="Tandai sebagai jawaban benar">
                </div>
                <input type="text" name="options[${index}][id]" class="form-control" placeholder="Teks Opsi (ID)" value="${textId}" required>
                <input type="text" name="options[${index}][en]" class="form-control" placeholder="Option Text (EN)" value="${textEn}" required>
                <button class="btn btn-outline-danger remove-option-btn" type="button" title="Hapus Opsi">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;
    }

    // --- Fungsi untuk me-reset index radio button ---
    function reindexOptions(listContainer) {
        let needsRecheck = true;
        if (listContainer.find('input[type="radio"]:checked').length > 0) {
            needsRecheck = false;
        }

        listContainer.find('.option-row').each(function(index) {
            $(this).find('input[type="radio"]').val(index);
            $(this).find('input[name^="options"]').each(function() {
                this.name = this.name.replace(/options\[\d+\]/, `options[${index}]`);
            });

            if (needsRecheck && index === 0) {
                 $(this).find('input[type="radio"]').prop('checked', true);
            }
        });
    }

    $(document).ready(function() {
        const createContainer = $('#create_mc_options_container');
        const createList = $('#create_options_list');
        const editContainer = $('#edit_mc_options_container');
        const editList = $('#edit_options_list');

        // --- Logika untuk Modal CREATE ---
        $('#create_question_type').on('change', function() {
            if ($(this).val() === 'multiple_choice') {
                createContainer.slideDown();
                if(createList.children().length === 0) {
                    createList.append(createOptionHtml('create', 0));
                    createList.append(createOptionHtml('create', 1));
                }
            } else {
                createContainer.slideUp();
                createList.empty();
            }
        });
        $('#create_add_option_btn').on('click', function() {
            const newIndex = createList.children().length;
            createList.append(createOptionHtml('create', newIndex));
        });

        // --- Logika untuk Modal EDIT ---
        $('#edit_question_type').on('change', function() {
            if ($(this).val() === 'multiple_choice') {
                editContainer.slideDown();
                if(editList.children().length === 0) {
                    editList.append(createOptionHtml('edit', 0));
                    editList.append(createOptionHtml('edit', 1));
                }
            } else {
                editContainer.slideUp();
                editList.empty();
            }
        });
        $('#edit_add_option_btn').on('click', function() {
            const newIndex = editList.children().length;
            editList.append(createOptionHtml('edit', newIndex));
        });

        // --- [PERBAIKAN VALIDASI] Tombol "Hapus Opsi" ---
        $(document).on('click', '.remove-option-btn', function() {
            const listContainer = $(this).closest('.option-row').parent();

            // [FIX] Cek jumlah opsi SEBELUM menghapus
            if (listContainer.children('.option-row').length <= 2) {
                Swal.fire('Input Tidak Valid', 'Pilihan Ganda harus memiliki minimal 2 opsi.', 'warning');
                return; // Hentikan penghapusan
            }

            $(this).closest('.option-row').remove();
            reindexOptions(listContainer);
        });
    });


    // ===================================================================
    // == FUNGSI MODAL YANG SUDAH ADA (DIPERBARUI)
    // ===================================================================

    function editButton(button) {
        var url = $(button).data('url'); // URL untuk GET detail pertanyaan
        var modal = $('#editQuestionModal');
        var form = $('#editQuestionForm');
        var editList = $('#edit_options_list');

        form.trigger('reset');
        editList.empty();
        $('#edit_mc_options_container').hide();

        $.get(url, function(data) {
            // Gunakan route update Laravel
            var updateUrl = "{{ route('reflection_question.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', data.id); // Ganti :id dengan ID pertanyaan aktual

            form.attr('action', updateUrl);
            form.attr('method', 'POST'); // karena HTML form hanya support GET/POST
            // tambahkan _method=PUT agar cocok dengan Laravel
            if (!form.find('input[name="_method"]').length) {
                form.append('<input type="hidden" name="_method" value="PUT">');
            }

            // Isi field dengan data dari server
            modal.find('#edit_question_text_id').val(data.question_text.id ?? '');
            modal.find('#edit_question_text_en').val(data.question_text.en ?? '');
            modal.find('#edit_question_order').val(data.order ?? '');
            modal.find('#edit_question_type').val(data.type ?? '');

            // Tampilkan opsi jika multiple choice
            if (data.type === 'multiple_choice' && Array.isArray(data.options) && data.options.length > 0) {
                data.options.forEach((option, index) => {
                    const optionHtml = createOptionHtml('edit', index, option);
                    editList.append(optionHtml);
                });
                $('#edit_mc_options_container').show();
            }

            modal.modal('show'); // tampilkan modal edit
        });
    }


    // --- (Fungsi deleteQuestion tidak berubah) ---
    function deleteQuestion(id) {
        Swal.fire({
            title: '{{ __("admin.swal.delete_title") }}',
            text: '{{ __("admin.swal.delete_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("admin.swal.delete_confirm") }}',
            cancelButtonText: '{{ __("admin.swal.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-question-form-' + id).submit();
            }
        })
    }

    // --- [PERBAIKAN] Tambahkan Konfirmasi Swal untuk CREATE dan UPDATE ---
    $(document).ready(function() {

        // [PERBAIKAN] Konfirmasi Swal untuk CREATE
        // (Tombol submit ada di 'create_modal.blade.php', tapi form-nya 'reflectionQuestionStore')
        $('#reflectionQuestionStore').on('submit', function(e) {
            e.preventDefault(); // Mencegah form submit
            var form = this;

            // Validasi frontend tambahan
            var questionType = $('#create_question_type').val();
            if (questionType === 'multiple_choice') {
                var optionsCount = $('#create_options_list').children('.option-row').length;
                if (optionsCount < 2) {
                    Swal.fire('Input Tidak Valid', 'Pilihan Ganda harus memiliki minimal 2 opsi.', 'error');
                    return; // Hentikan submit
                }
            }

            // Lanjutkan dengan konfirmasi Swal
            Swal.fire({
                title: '{{ __("admin.swal.save_title") }}',
                text: 'Pastikan data yang Anda masukkan sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.swal.save_confirm") }}',
                cancelButtonText: '{{ __("admin.swal.cancel") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Lanjutkan submit jika dikonfirmasi
                }
            });
        });

        // [TIDAK BERUBAH] Konfirmasi Swal untuk UPDATE
        $('#editQuestionForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;

             // Validasi frontend tambahan
            var questionType = $('#edit_question_type').val();
            if (questionType === 'multiple_choice') {
                var optionsCount = $('#edit_options_list').children('.option-row').length;
                if (optionsCount < 2) {
                    Swal.fire('Input Tidak Valid', 'Pilihan Ganda harus memiliki minimal 2 opsi.', 'error');
                    return; // Hentikan submit
                }
            }

            Swal.fire({
                title: '{{ __("admin.swal.update_title") }}',
                text: '{{ __("admin.swal.update_text") }}',
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
    });

</script>
@endrole


{{-- JANGAN HAPUS BLOK INI - INI UNTUK SISWA --}}
<script>
    // Fungsi saveAnswer() Anda yang lama (jika ada) akan tetap di sini
    // Kita akan memodifikasinya di Tahap 5
</script>
@endpush
