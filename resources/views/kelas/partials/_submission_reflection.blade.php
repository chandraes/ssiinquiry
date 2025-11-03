{{-- File: resources/views/kelas/partials/_submission_reflection.blade.php --}}

<style>
/* Style kustom untuk "soft badge".
    Anda bisa memindahkan ini ke file CSS utama Anda jika diinginkan.
*/
.badge.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.15);
    color: #087990;
}
.badge.bg-secondary-soft {
    background-color: rgba(108, 117, 125, 0.15);
    color: #495057;
}
</style>

<div class="submission-content">
    <p class="text-muted small">Menampilkan jawaban refleksi siswa untuk sub-modul: <strong>{{ $subModule->title }}</strong>.</p>
    <hr>

    {{-- [PERBAIKAN] Menggunakan List Group untuk seluruh daftar --}}
    <div class="list-group list-group-flush">
        @forelse ($questions as $question)
            {{-- Setiap pertanyaan adalah list-group-item yang diformat seperti card --}}
            <div class="list-group-item flex-column align-items-start mb-3 py-3 px-0 border-bottom">

                {{-- Header Pertanyaan (Judul & Tipe) --}}
                <div class="d-flex w-100 justify-content-between px-3">
                    <h5 class="mb-1 text-primary">Pertanyaan {{ $loop->iteration }}</h5>
                    @if($question->type == 'multiple_choice')
                        <span class="badge bg-info-soft rounded-pill px-2 py-1">Pilihan Ganda</span>
                    @else
                        <span class="badge bg-secondary-soft rounded-pill px-2 py-1">Esai</span>
                    @endif
                </div>

                {{-- Teks Pertanyaan --}}
                <p class="mb-2 mt-2 fs-6 px-3" style="white-space: pre-wrap;">{{ $question->question_text }}</p>

                @php
                    $answer = $answers->get($question->id);
                @endphp

                {{-- Wrapper Jawaban --}}
                <div class="answer-wrapper mt-3 w-100">
                    @if ($question->type == 'multiple_choice')
                        {{-- TAMPILAN PILIHAN GANDA --}}
                        {{-- [PERBAIKAN] Menggunakan nested list-group --}}
                        <div class="list-group list-group-flush px-3">
                            @foreach ($question->options as $option)
                                @php
                                    $isStudentChoice = $answer && $answer->reflection_question_option_id == $option->id;
                                    $isCorrectKey = $option->is_correct;

                                    $class = '';
                                    $icon = 'fa-circle-o text-muted'; // Default icon

                                    if ($isStudentChoice && $isCorrectKey) {
                                        // 1. BENAR (Hijau)
                                        $class = 'list-group-item-success';
                                        $icon = 'fa-check-circle text-success';
                                    } elseif ($isStudentChoice && !$isCorrectKey) {
                                        // 2. SALAH (Merah)
                                        $class = 'list-group-item-danger';
                                        $icon = 'fa-times-circle text-danger';
                                    } elseif (!$isStudentChoice && $isCorrectKey) {
                                        // 3. KUNCI JAWABAN (Biru Pudar)
                                        $class = 'list-group-item-info bg-opacity-50';
                                        $icon = 'fa-key text-info';
                                    }
                                @endphp

                                <div class="list-group-item {{ $class }} d-flex align-items-center py-3">
                                    <i class="fa {{ $icon }} fa-fw me-3 fs-5"></i>
                                    <div>
                                        {{ $option->option_text }}

                                        @if ($isStudentChoice)
                                            <span class="badge bg-dark ms-2">Pilihan Siswa</span>
                                        @endif
                                        @if (!$isStudentChoice && $isCorrectKey)
                                             <span class="badge bg-info ms-2">Kunci Jawaban</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        {{-- TAMPILAN JAWABAN ESAI --}}
                        {{-- [PERBAIKAN] Menggunakan blockquote, menghapus 'rounded' --}}
                        <div class="px-3">
                            @if ($answer && !empty($answer->answer_text))
                                <blockquote class="blockquote bg-light p-3 border-start border-4 border-secondary mb-0">
                                    <p class="mb-0" style="white-space: pre-wrap;">{{ $answer->answer_text }}</p>
                                </blockquote>
                            @else
                                <p class="text-muted fst-italic mb-0">Siswa tidak memberikan jawaban untuk pertanyaan ini.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-light text-center">
                <p class="text-muted mb-0">Tidak ada pertanyaan refleksi untuk sub-modul ini.</p>
            </div>
        @endforelse
    </div>
</div>
