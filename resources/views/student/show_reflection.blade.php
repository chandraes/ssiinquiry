@extends('layouts.app')
@section('title')@endsection

@push('css')
<style>
    /* [BARU] Styling untuk Pilihan Ganda */
    .reflection-options .form-check {
        margin-bottom: 0.5rem;
        transition: background-color 0.2s;
    }
    .reflection-options .form-check-label {
        width: 100%;
        cursor: pointer;
        padding: 0.75rem 1.25rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    /* Saat radio di-check */
    .reflection-options input[type="radio"]:checked + .form-check-label {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    /* [BARU] Status Jawaban Benar/Salah (setelah AJAX) */
    .reflection-options .form-check-label.correct {
        border-color: #198754; /* Hijau */
        background-color: #d1e7dd;
        font-weight: bold;
    }
    .reflection-options .form-check-label.incorrect {
        border-color: #dc3545; /* Merah */
        background-color: #f8d7da;
    }
    /* Sembunyikan radio button aslinya */
    .reflection-options input[type="radio"] {
        display: none;
    }
</style>
@endpush

@section('content')

{{-- 1. Kotak Nilai & Umpan Balik (Tidak Berubah) --}}
@include('student.partials.grade_feedback_box')

<div class="container-fluid">
    {{-- 2. Tombol Navigasi (Tidak Berubah) --}}
    <!-- <div class="col-md-12 mb-5">
        <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
        </a>
    </div> -->

    {{-- 3. Header Sub-Modul (Tidak Berubah) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title"><i class="fa fa-pencil-square text-info me-2"></i>{{ $subModule->title }}</h2>
            <p class="text-muted">{{ $subModule->description }}</p>
            <p class="lead">{{__('admin.siswa.show_reflection.lead')}}.</p>
        </div>
    </div>

    {{-- 4. Form Utama (Tombol Selesai) --}}
    <div class="card shadow-sm">

        {{-- [PERBAIKAN] Hapus <form> yang membungkus semuanya --}}
        {{-- Kita hanya perlu form untuk tombol 'Selesai' di bagian bawah --}}

        <div class="card-body">

            {{-- Notifikasi (Tidak Berubah) --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- [PERBAIKAN TOTAL] Loop Pertanyaan --}}
            @php
                // Tentukan status "Terkunci" di awal
                $isLocked = ($currentProgress && $currentProgress->completed_at);
            @endphp

            @forelse ($subModule->reflectionQuestions->sortBy('order') as $question)
                @php
                    // Ambil jawaban siswa yang tersimpan
                    $answer = $myAnswers->get($question->id);
                @endphp
                <div class="mb-4 p-3 border shadow-sm reflection-question-item">
                    {{-- Pertanyaan --}}
                    <div class="mb-3">
                        <span class="badge bg-primary me-2">Pertanyaan {{ $loop->iteration }}</span>
                        <h5 class="d-inline">{{ $question->question_text }}</h5>
                    </div>

                    {{-- Container Jawaban --}}
                    <div class="answer-container"
                         data-question-id="{{ $question->id }}"
                         data-question-type="{{ $question->type }}">

                        @if ($question->type == 'multiple_choice')
                            {{-- ======================== --}}
                            {{-- TAMPILAN PILIHAN GANDA --}}
                            {{-- ======================== --}}
                            <div class="reflection-options">
                                @foreach ($question->options as $option)
                                    @php
                                        $isChecked = ($answer && $answer->reflection_question_option_id == $option->id);
                                        $isCorrect = $option->is_correct;
                                        $isIncorrect = ($isChecked && !$isCorrect);

                                        $labelClass = '';
                                        if ($isLocked && $isChecked && $isCorrect) $labelClass = 'correct';
                                        if ($isLocked && $isCorrect && !$isChecked) $labelClass = 'correct'; // Tampilkan kunci jawaban
                                        if ($isLocked && $isIncorrect) $labelClass = 'incorrect';
                                    @endphp
                                    <div class="form-check">
                                        <input class="form-check-input mc-option-input"
                                               type="radio"
                                               name="question_{{ $question->id }}"
                                               id="option_{{ $option->id }}"
                                               value="{{ $option->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               {{ $isLocked ? 'disabled' : '' }}>

                                        <label class="form-check-label {{ $labelClass }}" for="option_{{ $option->id }}">
                                            {{ $option->option_text }}

                                            {{-- [BARU] Ikon untuk status (setelah terkunci) --}}
                                            @if($isLocked && $isCorrect)
                                                <i class="fa fa-check-circle text-success float-end mt-1"></i>
                                            @elseif($isLocked && $isIncorrect)
                                                <i class="fa fa-times-circle text-danger float-end mt-1"></i>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            {{-- ======================== --}}
                            {{-- TAMPILAN ESAI (LAMA) --}}
                            {{-- ======================== --}}
                            <textarea class="form-control reflection-essay-input"
                                      id="question_{{ $question->id }}_text"
                                      rows="5"
                                      placeholder="{{ $isLocked ? 'Jawaban sudah terkunci.' : 'Tuliskan jawaban refleksi Anda di sini...' }}"
                                      {{ $isLocked ? 'readonly' : '' }}
                                      >{{ $answer?->answer_text }}</textarea>

                            @if(!$isLocked)
                                <button class="btn btn-primary btn-sm mt-2 save-answer-btn"
                                        id="question_{{ $question->id }}_save_btn"
                                        type="button"
                                        onclick="saveAnswer({{ $question->id }})">
                                    Simpan Jawaban
                                </button>
                            @endif
                        @endif

                        {{-- Status Penyimpanan AJAX --}}
                        <span class="text-muted small ms-2" id="question_{{ $question->id }}_status"></span>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    Belum ada pertanyaan refleksi untuk sub-modul ini.
                </div>
            @endforelse
        </div>

        {{-- Tombol Navigasi Bawah (Selesai/Lanjut) --}}
        <div class="card-footer d-flex justify-content-between">
            {{-- Form untuk Tombol 'Selesai' (sekarang terpisah) --}}
            <form action="{{ route('student.reflection.store', [$kelas->id, $subModule->id]) }}" method="POST" id="reflectionForm">
                @csrf

                {{-- Tombol 'Selesai' (Hanya jika belum selesai) --}}
                @if(!$isLocked)
                    <button type="submit" name="action" value="complete"
                            class="btn btn-success btn-lg px-4"
                            id="completeButton">
                        <i class="fa fa-check-circle me-2"></i> {{__('admin.siswa.show_reflection.complete')}}
                    </button>
                @else
                    {{-- Tampilkan pesan jika sudah selesai --}}
                    <span class="badge bg-success p-3 fs-16">
                        <i class="fa fa-check-circle me-2"></i> Anda sudah menyelesaikan sub-modul ini.
                    </span>
                @endif

                {{-- Tombol 'Lanjut' (Selalu Tampil) --}}
                <button type="submit" name="action" value="save_and_next"
                        class="btn btn-primary btn-lg px-4"
                        id="nextButton">
                    {{__('admin.siswa.show_reflection.next_submodule')}} <i class="fa fa-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
        <!-- <div class="card shadow-sm">
            <div class="card-footer text-center">
                <a href="{{ route('student.class.show', $kelas->id) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i> {{__('admin.siswa.back_to_curriculum')}}
                </a>
            </div>
        </div> -->

    </div>
</div>
@endsection
@push('js')
<script>
$(document).ready(function() {

    // Variabel Global (dari Blade)
    const KELAS_ID = {{ $kelas->id }};
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const SAVE_URL = "{{ route('reflection_question.save_answer') }}";

    // ===================================================================
    // LOGIKA SIMPAN JAWABAN (AJAX) - (Tidak Berubah)
    // ===================================================================

    // --- Helper AJAX Universal ---
    function sendAnswerToController(questionId, dataPayload, statusSpan) {
        statusSpan.html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        var $container = $(`[data-question-id="${questionId}"]`);
        $container.find('.form-check-label').removeClass('correct incorrect');

        $.ajax({
            url: SAVE_URL,
            type: "POST",
            data: dataPayload,
            success: function(response) {
                statusSpan.html('<i class="fa fa-check text-success"></i> Jawaban disimpan.');
            },
            error: function(xhr) {
                let errorMsg = 'Gagal menyimpan jawaban.';
                if (xhr.status === 403) {
                    errorMsg = xhr.responseJSON.error || 'Sub-modul sudah dikunci.';
                    $('.mc-option-input').prop('disabled', true);
                    $('.reflection-essay-input').prop('readonly', true);
                    $('.save-answer-btn').hide();
                }
                statusSpan.html('<i class="fa fa-times text-danger"></i> ' + errorMsg);
            }
        });
    }

    // --- Handler untuk Pilihan Ganda (Otomatis Simpan) ---
    $('.mc-option-input').on('change', function() {
        var $this = $(this);
        var questionId = $this.closest('.answer-container').data('question-id');
        var optionId = $this.val();
        var statusSpan = $('#question_' + questionId + '_status');
        var payload = {
            _token: CSRF_TOKEN,
            question_id: questionId,
            class_id: KELAS_ID,
            option_id: optionId
        };
        sendAnswerToController(questionId, payload, statusSpan);
    });

    // --- Handler untuk Esai (Tombol Simpan) ---
    window.saveAnswer = function(questionId) {
        var statusSpan = $('#question_' + questionId + '_status');
        var answerText = $('#question_' + questionId + '_text').val();
        var payload = {
            _token: CSRF_TOKEN,
            question_id: questionId,
            class_id: KELAS_ID,
            answer_text: answerText
        };
        sendAnswerToController(questionId, payload, statusSpan);
    }

    // ===================================================================
    // [PERBAIKAN] LOGIKA TOMBOL SELESAI/LANJUT (SweetAlert)
    // ===================================================================
    $('#reflectionForm').on('submit', function(e) {
        e.preventDefault(); // Selalu cegah submit langsung

        // [PERBAIKAN 1] Dapatkan elemen <form> NATIVE, bukan jQuery object
        var form = this;
        // Kita tetap butuh jQuery object untuk 'find' dan 'append'
        var $form = $(this);

        var actionName = $(document.activeElement).attr('name');
        var actionValue = $(document.activeElement).attr('value');

        var swalTitle, swalText, swalConfirmText;

        if (actionValue === 'save_and_next') {
            swalTitle = 'Lanjut ke Sub-Modul Berikutnya?';
            swalText = 'Kami akan menyimpan progres Anda sebelum melanjutkan.';
            swalConfirmText = 'Ya, Lanjutkan';
        } else {
            swalTitle = 'Selesaikan Sub-Modul Ini?';
            swalText = 'Setelah selesai, Anda tidak dapat mengubah jawaban refleksi Anda lagi. Pastikan semua jawaban sudah disimpan.';
            swalConfirmText = 'Ya, Selesaikan';
        }

        Swal.fire({
            title: swalTitle,
            text: swalText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: swalConfirmText,
            cancelButtonText: '{{ __("admin.swal.cancel") }}',
        }).then((result) => {
            if (result.isConfirmed) {
                // Hapus input 'action' tersembunyi sebelumnya (jika ada)
                $form.find('input[type="hidden"][name="action"]').remove();

                // Tambahkan input tersembunyi baru dengan nilai 'action'
                $form.append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', actionName)
                        .attr('value', actionValue)
                );

                // [PERBAIKAN 2] Panggil submit() NATIVE
                // Ini akan submit form tanpa memicu listener ini lagi.
                form.submit();
            }
        });
    });
});
</script>
@endpush
