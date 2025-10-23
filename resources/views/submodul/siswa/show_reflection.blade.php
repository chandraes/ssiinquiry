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
                @php
                    // MENCARI JAWABAN YANG SUDAH ADA DARI USER YANG SEDANG LOGIN
                    // Ini mengasumsikan relasi Question->answers() sudah didefinisikan dan difilter di Controller
                    // atau menggunakan relasi hasOne/hasMany yang difilter di sini.
                    // Jika data jawaban di-eager load dari Controller, ubah logika ini.
                    
                    // Metode paling aman: ambil jawaban dari relasi yang sudah di-load (misal: reflectionQuestion->userAnswer)
                    $existingAnswer = $question->userAnswer ?? null; 
                    $answerText = $existingAnswer ? $existingAnswer->answer_text : '';
                @endphp
                <form id="answer-form-{{ $question->id }}" data-question-id="{{ $question->id }}" method="POST">
                    @csrf
                    {{-- Hidden input untuk Submodul dan Pertanyaan --}}
                    <input type="hidden" name="sub_modul_id" value="{{ $subModul->id }}">
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <!-- Contoh di view Blade Anda (reflection_questions_siswa.blade.php) -->
                    {{-- <input type="hidden" name="class_id" value="{{ $modul->kelas->id ?? '' }}"> --}}
                    <!-- Anda harus memastikan variabel $kelas_aktif_id tersedia di view -->

                    <div class="list-group-item p-3 border rounded">
                        <div>
                            <strong>{{ $question->order }}. {{ $question->question_text }}</strong>
                        </div>
                        <div class="mt-3">
                            <label for="answer-{{ $question->id }}" class="form-label text-primary">Jawaban Anda:</label>
                            <textarea name="answer_text" id="answer-{{ $question->id }}" class="form-control" rows="4" placeholder="Tulis jawaban refleksi Anda di sini..." required>{{ $answerText }}</textarea>
                            <div class="invalid-feedback">Mohon isi jawaban Anda.</div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-3">
                            {{-- Tampilkan status saat ini --}}
                            <span id="status-{{ $question->id }}" class="me-3 text-muted align-self-center small">
                                @if($existingAnswer)
                                    <i class="fe fe-check-circle text-success me-1"></i> Tersimpan
                                @else
                                    Menunggu Jawaban
                                @endif
                            </span>
                            
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
{{-- [BARU] JavaScript untuk Edit dan Delete Pertanyaan --}}
<script>
     $(document).ready(function() {
        
        // Fungsi untuk menangani pengiriman formulir AJAX
        $('form[id^="answer-form-"]').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const questionId = form.data('question-id');
            const saveButton = form.find('.btn-save');
            const statusSpan = $('#status-' + questionId);
            
            // Tampilkan Loading State
            saveButton.attr('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...');
            statusSpan.html('Menyimpan...');

            $.ajax({
                // Ganti dengan Rute API POST Anda yang sebenarnya
                url: "{{ route('siswa.reflection.store') }}", 
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Update Status Sukses
                    saveButton.html('<i class="fe fe-save me-1"></i> Simpan Jawaban');
                    statusSpan.html('<i class="fe fe-check-circle text-success me-1"></i> Berhasil disimpan');
                    
                    // Opsional: Tampilkan notifikasi toast/alert sukses jika UI Anda memilikinya
                    console.log('Jawaban berhasil disimpan:', response); 
                },
                error: function(xhr) {
                    // Update Status Error
                    saveButton.html('<i class="fe fe-alert-triangle me-1"></i> Coba Lagi');
                    statusSpan.html('<i class="fe fe-x-circle text-danger me-1"></i> Gagal menyimpan!');

                    // Tampilkan pesan error jika ada
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan server.';
                    alert('Gagal menyimpan jawaban: ' + errorMsg);
                    
                    console.error('Error saat menyimpan jawaban:', xhr);
                },
                complete: function() {
                    // Hilangkan Loading State setelah selesai (baik sukses maupun error)
                    saveButton.attr('disabled', false);
                }
            });
        });
    });
</script>
@endpush
