@extends('layouts.app')

@section('title')
    Pertanyaan Refleksi: {{ $subModul->title }}
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
            <h5 class="mb-0">Daftar Pertanyaan Refleksi</h5>
        </div>

        <div class="card-body">
            @forelse($subModul->reflectionQuestions as $question)
                <form id="answer-form-{{ $question->id }}" data-question-id="{{ $question->id }}" method="POST">
                    @csrf
                    <input type="hidden" name="sub_modul_id" value="{{ $subModul->id }}">
                    <input type="hidden" name="question_id" value="{{ $question->id }}">

                    <div class="list-group-item p-3 border">
                        <div>
                            <strong>{{ $question->order }}. {{ $question->question_text }}</strong>
                        </div>
                        <div class="mt-3">
                            <label for="answer-{{ $question->id }}" class="form-label text-primary">Jawaban Anda:</label>
                            <textarea name="answer_text" id="answer-{{ $question->id }}" class="form-control" rows="4" placeholder="Tulis jawaban refleksi Anda di sini..." required>{{ $question->answers[0]->answer_text ?? '' }}</textarea>
                            <div class="invalid-feedback">Mohon isi jawaban Anda.</div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-3">
                            {{-- <span id="status-{{ $question->id }}" class="me-3 text-muted align-self-center small">
                                @if($question->answers)
                                    <i class="fe fe-check-circle text-success me-1"></i> Tersimpan
                                @else
                                    Menunggu Jawaban
                                @endif
                            </span> --}}
                            
                            <button type="submit" class="btn btn-primary btn-save" data-question-id="{{ $question->id }}">
                                <i class="fe fe-save me-1"></i> Simpan Jawaban
                            </button>
                        </div>
                    </div>
                </form>
            @empty
                <div class="alert alert-info text-center">
                    Belum ada pertanyaan refleksi untuk sub modul ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    $('form[id^="answer-form-"]').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const questionId = form.data('question-id');
        const saveButton = form.find('.btn-save');
        const statusSpan = $('#status-' + questionId);

        // Tampilkan konfirmasi SweetAlert sebelum menyimpan
        Swal.fire({
            title: 'Simpan Jawaban?',
            text: 'Apakah Anda yakin ingin menyimpan jawaban refleksi ini?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, simpan',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jalankan proses simpan via AJAX
                saveButton.attr('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...');
                statusSpan.html('Menyimpan...');

                $.ajax({
                    url: "{{ route('siswa.reflection.store') }}", 
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        saveButton.html('<i class="fe fe-save me-1"></i> Simpan Jawaban');
                        // statusSpan.html('<i class="fe fe-check-circle text-success me-1"></i> Berhasil disimpan');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Jawaban Anda telah disimpan.',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        saveButton.html('<i class="fe fe-alert-triangle me-1"></i> Coba Lagi');
                        statusSpan.html('<i class="fe fe-x-circle text-danger me-1"></i> Gagal menyimpan!');

                        const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: errorMsg
                        });
                    },
                    complete: function() {
                        saveButton.attr('disabled', false);
                    }
                });
            }
        });
    });

});
</script>
@endpush
